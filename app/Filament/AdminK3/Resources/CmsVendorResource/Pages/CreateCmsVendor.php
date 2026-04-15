<?php
namespace App\Filament\AdminK3\Resources\CmsVendorResource\Pages;

use App\Filament\AdminK3\Resources\CmsVendorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsVendor extends CreateRecord
{
    protected static string $resource = CmsVendorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
