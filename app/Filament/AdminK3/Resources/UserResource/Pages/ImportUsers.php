<?php
namespace App\Filament\AdminK3\Resources\UserResource\Pages;

use App\Filament\AdminK3\Resources\UserResource;
use App\Imports\UsersImport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImportUsers extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = UserResource::class;
    protected static string $view     = 'filament.admin-k3.resources.user-resource.pages.import-users';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('file')
                    ->label('File Excel Pegawai')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                    ])
                    ->required()
                    ->helperText('Format: .xlsx atau .xls — Download template di bawah'),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $data = $this->form->getState();

        try {
            // PERBAIKAN: Ambil path file langsung (karena Filament mengembalikan string path, bukan object file)
            $filePath = is_array($data['file']) ? reset($data['file']) : $data['file'];

            $import = new UsersImport();

            // PERBAIKAN: Perintahkan Excel untuk mencari file tersebut di disk 'public'
            Excel::import($import, $filePath, 'public');

            Notification::make()
                ->title("Import berhasil! {$import->getRowCount()} pegawai ditambahkan.")
                ->success()
                ->send();

            $this->redirect(UserResource::getUrl('index'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadTemplate()
    {
        // PERBAIKAN: Type hinting dihapus agar tidak bentrok
        return Excel::download(new \App\Exports\TemplatePegawaiExport(), 'template-import-pegawai.xlsx');
    }
}
