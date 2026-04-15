<?php

namespace App\Filament\AdminK3\Resources\CmsVendorResource\Pages;

use App\Filament\AdminK3\Resources\CmsVendorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCmsVendors extends ListRecords
{
    protected static string $resource = CmsVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
