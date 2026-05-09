<?php

namespace App\Filament\Klinik\Resources\KlinikObatResource\Pages;

use App\Filament\Klinik\Resources\KlinikObatResource;
use App\Imports\KlinikObatsImport;
use App\Exports\TemplateKlinikObatsExport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class ImportKlinikObats extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = KlinikObatResource::class;
    protected static string $view     = 'filament.klinik.resources.klinik-obat-resource.pages.import-klinik-obats';

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
                    ->label('File Excel Obat')
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

            $import = new KlinikObatsImport();

            Excel::import($import, $filePath, 'public');

            Notification::make()
                ->title("Import berhasil! {$import->getRowCount()} data obat dimasukkan.")
                ->success()
                ->send();

            $this->redirect(KlinikObatResource::getUrl('index'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateKlinikObatsExport(), 'template-import-obat.xlsx');
    }
}
