<?php
namespace App\Filament\Klinik\Resources\KlinikObatResource\resourcePages;

use App\Filament\Klinik\Resources\KlinikObatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKlinikObats extends ListRecords
{
    protected static string $resource = KlinikObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Obat')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
