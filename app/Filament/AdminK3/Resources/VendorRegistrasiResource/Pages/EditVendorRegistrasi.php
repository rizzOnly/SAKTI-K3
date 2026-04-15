<?php
namespace App\Filament\AdminK3\Resources\VendorRegistrasiResource\Pages;

use App\Filament\AdminK3\Resources\VendorRegistrasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorRegistrasi extends EditRecord
{
    protected static string $resource = VendorRegistrasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
