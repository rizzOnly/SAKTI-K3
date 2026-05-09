<?php

namespace App\Filament\Klinik\Resources\KlinikAlatResource\Pages;

use App\Filament\Klinik\Resources\KlinikAlatResource;
use App\Imports\KlinikAlatsImport;
use App\Exports\TemplateKlinikAlatsExport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class ImportKlinikAlats extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = KlinikAlatResource::class;
    protected static string $view     = 'filament.klinik.resources.klinik-alat-resource.pages.import-klinik-alats';

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
                    ->label('File Excel Alat Medis')
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
            $filePath = is_array($data['file']) ? reset($data['file']) : $data['file'];

            $import = new KlinikAlatsImport();

            Excel::import($import, $filePath, 'public');

            Notification::make()
                ->title("Import berhasil! {$import->getRowCount()} data alat medis dimasukkan.")
                ->success()
                ->send();

            $this->redirect(KlinikAlatResource::getUrl('index'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateKlinikAlatsExport(), 'template-import-alat-medis.xlsx');
    }
}
