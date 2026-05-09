<?php

namespace App\Filament\Klinik\Resources\KlinikAlatResource\resourcePages;

use App\Filament\Klinik\Resources\KlinikAlatResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListKlinikAlats extends ListRecords
{
    protected static string $resource = KlinikAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import')
                ->label('Import Alat Medis')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->url(fn () => KlinikAlatResource::getUrl('import'))
                ->openUrlInNewTab(false),

            CreateAction::make()
                ->label('Tambah Alat Medis')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
