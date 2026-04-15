<?php
namespace App\Filament\AdminK3\Resources\PatrolPeriodeResource\Pages;

use App\Filament\AdminK3\Resources\PatrolPeriodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatrolPeriodes extends ListRecords
{
    protected static string $resource = PatrolPeriodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
