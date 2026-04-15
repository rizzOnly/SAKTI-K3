<?php
namespace App\Filament\AdminK3\Resources\CmsVendorResource\Pages;

use App\Filament\AdminK3\Resources\CmsVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCmsVendor extends EditRecord
{
    protected static string $resource = CmsVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
