<?php

namespace App\Filament\Klinik\Resources\FitToWorkResource\Pages;

use App\Filament\Klinik\Resources\FitToWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFitToWorks extends ListRecords
{
    protected static string $resource = FitToWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
