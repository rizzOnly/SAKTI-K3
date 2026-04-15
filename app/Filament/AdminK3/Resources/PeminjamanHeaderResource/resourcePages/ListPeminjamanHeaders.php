<?php
namespace App\Filament\AdminK3\Resources\PeminjamanHeaderResource\resourcePages;

use App\Filament\AdminK3\Resources\PeminjamanHeaderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanHeaders extends ListRecords
{
    protected static string $resource = PeminjamanHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Pinjam APD')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
