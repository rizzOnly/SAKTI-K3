<?php
namespace App\Filament\AdminK3\Resources\PatrolPeriodeResource\Pages;

use App\Filament\AdminK3\Resources\PatrolPeriodeResource;
use App\Models\{PatrolPeriode, PatrolJadwal};
use Filament\Forms\Components\{FileUpload, Select, TextInput};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportPatrol extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = PatrolPeriodeResource::class;
    protected static string $view     = 'filament.admin-k3.pages.import-patrol';

    public ?array $data = [];

    public function mount(): void { $this->form->fill(); }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('bulan')
                ->label('Bulan Periode')
                ->options([
                    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                    9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
                ])
                ->default(now()->month)
                ->required(),

            TextInput::make('tahun')
                ->label('Tahun')
                ->numeric()
                ->default(now()->year)
                ->required(),

            FileUpload::make('file')
                ->label('File Excel Jadwal P2K3')
                ->acceptedFileTypes([
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                ])
                ->required()
                ->helperText(
                    'Format: Kolom A = Nama Pegawai, ' .
                    'kolom berikutnya = tanggal (header berisi teks tanggal), ' .
                    'isi sel = lokasi unit. Kosong = tidak bertugas.'
                ),
        ])->statePath('data');
    }

    public function import(): void
    {
        $data = $this->form->getState();

        try {
            $file = $data['file'];
            $path = $file->getRealPath();

            $spreadsheet = IOFactory::load($path);
            $ws          = $spreadsheet->getActiveSheet();
            $rows        = $ws->toArray(null, true, true, true);

            $headerRow = array_shift($rows);

            $colDates = [];
            foreach ($headerRow as $col => $val) {
                if ($col === 'A' || empty($val)) continue;
                try {
                    $date = \Carbon\Carbon::parse(trim($val));
                    $colDates[$col] = $date;
                } catch (\Exception $e) {
                    // skip
                }
            }

            if (empty($colDates)) {
                Notification::make()->title('Tidak ada kolom tanggal yang valid ditemukan.')->danger()->send();
                return;
            }

            $periode = PatrolPeriode::updateOrCreate(
                ['bulan' => $data['bulan'], 'tahun' => $data['tahun']],
                [
                    'judul'     => 'Jadwal Safety Patrol ' .
                                    PatrolPeriode::namaBulan($data['bulan']) . ' ' .
                                    $data['tahun'],
                    'is_active' => true,
                ]
            );

            $periode->jadwals()->delete();

            $imported = 0;
            $urutan   = 0; // ← set urutan saat import

            foreach ($rows as $row) {
                $namaPetugas = trim($row['A'] ?? '');
                if (empty($namaPetugas)) continue;

                foreach ($colDates as $col => $date) {
                    $val = trim($row[$col] ?? '');
                    if (empty($val)) continue;
                    if ((int)$date->month !== (int)$data['bulan']) continue;

                    PatrolJadwal::create([
                        'patrol_periode_id' => $periode->id,
                        'nama_petugas'      => strtoupper($namaPetugas),
                        'tanggal_patrol'    => $date->toDateString(),
                        'lokasi_unit'       => $val,
                        'sudah_lapor'       => false,
                        'urutan'            => $urutan++, // ← simpan urutan
                    ]);
                    $imported++;
                }
            }

            Notification::make()
                ->title("Import berhasil! {$imported} jadwal dimasukkan.")
                ->success()->send();

            $this->redirect(PatrolPeriodeResource::getUrl('index'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal: ' . $e->getMessage())
                ->danger()->send();
        }
    }
}
