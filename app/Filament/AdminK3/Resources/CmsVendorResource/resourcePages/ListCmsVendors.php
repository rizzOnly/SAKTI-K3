<?php
namespace App\Filament\AdminK3\Resources\CmsVendorResource\resourcePages;

use App\Filament\AdminK3\Resources\CmsVendorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsVendors extends ListRecords
{
    protected static string $resource = CmsVendorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Vendor')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
