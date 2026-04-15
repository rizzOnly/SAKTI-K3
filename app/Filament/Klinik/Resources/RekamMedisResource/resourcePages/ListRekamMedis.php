<?php
namespace App\Filament\Klinik\Resources\RekamMedisResource\resourcePages;

use App\Filament\Klinik\Resources\RekamMedisResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekamMedis extends ListRecords
{
    protected static string $resource = RekamMedisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Buat Rekam Medis')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
