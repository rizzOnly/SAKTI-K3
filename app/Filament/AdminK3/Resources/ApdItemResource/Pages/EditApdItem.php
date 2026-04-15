<?php
namespace App\Filament\AdminK3\Resources\ApdItemResource\Pages;

use App\Filament\AdminK3\Resources\ApdItemResource;
use Filament\Resources\Pages\EditRecord;

class EditApdItem extends EditRecord
{
    protected static string $resource = ApdItemResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'APD berhasil diperbarui';
    }
}
