<?php
namespace App\Filament\AdminK3\Resources\UserResource\Pages;

use App\Filament\AdminK3\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\ExportPegawaiExport;
use Maatwebsite\Excel\Facades\Excel;



class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pegawai'),
            // Tombol Import Excel — action terpisah
            Actions\Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->url(UserResource::getUrl('import')),

            Actions\Action::make('export_pegawai')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn() => Excel::download(
                    new ExportPegawaiExport(),
                    'daftar-pegawai-' . date('Ymd') . '.xlsx'
                )),
        ];
    }
}
