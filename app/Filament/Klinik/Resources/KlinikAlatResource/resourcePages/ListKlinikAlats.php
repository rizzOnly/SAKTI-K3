<?php
namespace App\Filament\Klinik\Resources\KlinikAlatResource\resourcePages;

use App\Filament\Klinik\Resources\KlinikAlatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKlinikAlats extends ListRecords
{
    protected static string $resource = KlinikAlatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Alat Medis')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
