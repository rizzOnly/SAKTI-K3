<?php
namespace App\Filament\AdminK3\Resources\ApdItemResource\resourcePages;

use App\Filament\AdminK3\Resources\ApdItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApdItems extends ListRecords
{
    protected static string $resource = ApdItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah APD')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
