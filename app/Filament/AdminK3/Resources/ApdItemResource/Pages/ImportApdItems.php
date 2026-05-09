<?php

namespace App\Filament\AdminK3\Resources\ApdItemResource\Pages;

use App\Filament\AdminK3\Resources\ApdItemResource;
use App\Imports\ApdItemsImport;
use App\Exports\TemplateApdItemsExport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class ImportApdItems extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ApdItemResource::class;
    protected static string $view     = 'filament.admin-k3.resources.apd-item-resource.pages.import-apd-items';

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
                    ->label('File Excel APD')
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

            $import = new ApdItemsImport();

            Excel::import($import, $filePath, 'public');

            Notification::make()
                ->title("Import berhasil! {$import->getRowCount()} data APD dimasukkan.")
                ->success()
                ->send();

            $this->redirect(ApdItemResource::getUrl('index'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Import gagal: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new TemplateApdItemsExport(), 'template-import-apd.xlsx');
    }
}
