<?php
namespace App\Filament\AdminK3\Resources\CmsBannerResource\resourcePages;

use App\Filament\AdminK3\Resources\CmsBannerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsBanners extends ListRecords
{
    protected static string $resource = CmsBannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Banner')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
