<?php

namespace App\Filament\AdminK3\Resources\ApdItemResource\resourcePages;

use App\Filament\AdminK3\Resources\ApdItemResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListApdItems extends ListRecords
{
    protected static string $resource = ApdItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import APD')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->url(fn () => ApdItemResource::getUrl('import'))
                ->openUrlInNewTab(false),

            CreateAction::make()
                ->label('Tambah APD')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
