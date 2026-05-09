<?php

namespace App\Filament\Klinik\Resources\KlinikObatResource\resourcePages;

use App\Filament\Klinik\Resources\KlinikObatResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListKlinikObats extends ListRecords
{
    protected static string $resource = KlinikObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Obat')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->url(fn () => KlinikObatResource::getUrl('import'))
                ->openUrlInNewTab(false),

            CreateAction::make()
                ->label('Tambah Obat')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
