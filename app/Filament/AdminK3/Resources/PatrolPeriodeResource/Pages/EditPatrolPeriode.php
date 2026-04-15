<?php
namespace App\Filament\AdminK3\Resources\PatrolPeriodeResource\Pages;

use App\Filament\AdminK3\Resources\PatrolPeriodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatrolPeriode extends EditRecord
{
    protected static string $resource = PatrolPeriodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
