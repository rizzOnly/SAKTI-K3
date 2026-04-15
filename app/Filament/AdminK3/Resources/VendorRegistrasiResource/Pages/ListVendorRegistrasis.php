<?php
namespace App\Filament\AdminK3\Resources\VendorRegistrasiResource\Pages;

use App\Filament\AdminK3\Resources\VendorRegistrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorRegistrasis extends ListRecords
{
    protected static string $resource = VendorRegistrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Registrasi'),
        ];
    }
}
