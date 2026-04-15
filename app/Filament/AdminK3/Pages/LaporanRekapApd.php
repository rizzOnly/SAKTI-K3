<?php
namespace App\Filament\AdminK3\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\{Select, TextInput, Grid};
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\{PengambilanDetail, PeminjamanDetail};
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapApdOtomatisExport;

class LaporanRekapApd extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Rekapan APD';
    protected static ?string $navigationGroup = 'Laporan';
    protected static string $view = 'filament.admin-k3.pages.laporan-rekap-apd';

    public ?int $filter_bulan = null;
    public ?int $filter_tahun = null;

    public function mount(): void
    {
        $this->filter_bulan = now()->month;
        $this->filter_tahun = now()->year;
        $this->form->fill([
            'filter_bulan' => $this->filter_bulan,
            'filter_tahun' => $this->filter_tahun,
        ]);
    }

    // KUNCI RESPONSIVITAS: Fungsi ini dipanggil otomatis oleh Livewire saat Form berubah
    public function updated($name, $value)
    {
        if ($name === 'filter_bulan' || $name === 'filter_tahun') {
            $this->resetTable();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Select::make('filter_bulan')
                        ->label('Pilih Bulan')
                        ->options([
                            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
                            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
                            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
                        ])
                        ->live(), // Diubah: Hanya butuh live()

                    TextInput::make('filter_tahun')
                        ->label('Tahun')
                        ->numeric()
                        ->live(onBlur: true), // Diubah: Trigger saat kursor keluar dari inputan
                ]),
            ]);
    }

    // Menggabungkan data Pengambilan & Peminjaman secara dinamis
    protected function getTableQuery(): Builder
    {
        // 1. Query Pengambilan (Ambil)
        $qPengambilan = PengambilanDetail::query()
            ->select([
                DB::raw("CONCAT('A-', pengambilan_details.id) as id"),
                DB::raw("'ambil' as status_transaksi"),
                'pengambilan_headers.approved_at as tanggal',
                'users.bidang',
                'apd_items.nama_barang as jenis_apd',
                'pengambilan_details.jumlah',
                'apd_items.satuan',
                DB::raw("'' as keterangan")
            ])
            ->join('pengambilan_headers', 'pengambilan_details.pengambilan_header_id', '=', 'pengambilan_headers.id')
            ->join('users', 'pengambilan_headers.user_id', '=', 'users.id')
            ->join('apd_items', 'pengambilan_details.apd_item_id', '=', 'apd_items.id')
            ->where('pengambilan_headers.status', 'approved')
            ->whereMonth('pengambilan_headers.approved_at', $this->filter_bulan ?? now()->month)
            ->whereYear('pengambilan_headers.approved_at', $this->filter_tahun ?? now()->year);

        // 2. Query Peminjaman (Pinjam)
        $qPeminjaman = PeminjamanDetail::query()
            ->select([
                DB::raw("CONCAT('P-', peminjaman_details.id) as id"),
                DB::raw("'pinjam' as status_transaksi"),
                'peminjaman_headers.approved_at as tanggal',
                'users.bidang',
                'apd_items.nama_barang as jenis_apd',
                'peminjaman_details.jumlah',
                'apd_items.satuan',
                'peminjaman_headers.kondisi_kembali as keterangan'
            ])
            ->join('peminjaman_headers', 'peminjaman_details.peminjaman_header_id', '=', 'peminjaman_headers.id')
            ->join('users', 'peminjaman_headers.user_id', '=', 'users.id')
            ->join('apd_items', 'peminjaman_details.apd_item_id', '=', 'apd_items.id')
            ->whereIn('peminjaman_headers.status', ['approved', 'returned'])
            ->whereMonth('peminjaman_headers.approved_at', $this->filter_bulan ?? now()->month)
            ->whereYear('peminjaman_headers.approved_at', $this->filter_tahun ?? now()->year);

        // Gabungkan dengan unionAll, lalu bungkus sebagai Eloquent Model
        return \App\Models\ApdItem::query()
            ->fromSub($qPengambilan->unionAll($qPeminjaman), 'combined_table')
            ->orderBy('bidang')
            ->orderBy('jenis_apd');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('bidang')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_apd')
                    ->label('Jenis APD')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('jumlah')
                    ->label('Jml')
                    ->sortable(),

                TextColumn::make('satuan')
                    ->label('Satuan'),

                BadgeColumn::make('status_transaksi')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => strtoupper($state))
                    ->colors([
                        'primary' => 'ambil',
                        'warning' => 'pinjam',
                    ]),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->formatStateUsing(fn ($state) => $state === 'baik' ? 'Sudah Kembali' : ($state ?? '-')),
            ])
            ->paginated(false)
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel (Sesuai Filter)')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(fn() => Excel::download(
                        new RekapApdOtomatisExport($this->filter_bulan ?? now()->month, $this->filter_tahun ?? now()->year),
                        'rekap-apd-otomatis-' . ($this->filter_bulan ?? now()->month) . '-' . ($this->filter_tahun ?? now()->year) . '.xlsx'
                    )),
            ]);
    }
}
