<?php

namespace App\Filament\AdminK3\Resources\ApdInspectionResource\Pages;

use App\Filament\AdminK3\Resources\ApdInspectionResource;
use Filament\Resources\Pages\EditRecord;

class EditApdInspection extends EditRecord
{
    protected static string $resource = ApdInspectionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Inspeksi berhasil diperbarui';
    }
}
