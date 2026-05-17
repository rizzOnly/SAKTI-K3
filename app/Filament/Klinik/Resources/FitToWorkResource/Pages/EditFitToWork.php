<?php

namespace App\Filament\Klinik\Resources\FitToWorkResource\Pages;

use App\Filament\Klinik\Resources\FitToWorkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFitToWork extends EditRecord
{
    protected static string $resource = FitToWorkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
