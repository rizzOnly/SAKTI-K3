<?php

namespace App\Filament\AdminK3\Resources\TemuanOpenResource\Pages;

use App\Filament\AdminK3\Resources\TemuanOpenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTemuanOpen extends EditRecord
{
    protected static string $resource = TemuanOpenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
