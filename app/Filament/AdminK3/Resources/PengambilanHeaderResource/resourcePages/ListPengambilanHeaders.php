<?php
namespace App\Filament\AdminK3\Resources\PengambilanHeaderResource\resourcePages;

use App\Filament\AdminK3\Resources\PengambilanHeaderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPengambilanHeaders extends ListRecords
{
    protected static string $resource = PengambilanHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Ambil APD')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
