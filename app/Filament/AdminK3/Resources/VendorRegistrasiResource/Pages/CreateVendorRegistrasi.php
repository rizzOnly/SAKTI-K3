<?php
namespace App\Filament\AdminK3\Resources\VendorRegistrasiResource\Pages;

use App\Filament\AdminK3\Resources\VendorRegistrasiResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVendorRegistrasi extends CreateRecord
{
    protected static string $resource = VendorRegistrasiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
