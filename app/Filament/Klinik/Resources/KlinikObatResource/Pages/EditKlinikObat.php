<?php
namespace App\Filament\Klinik\Resources\KlinikObatResource\Pages;

use App\Filament\Klinik\Resources\KlinikObatResource;
use Filament\Resources\Pages\EditRecord;

class EditKlinikObat extends EditRecord
{
    protected static string $resource = KlinikObatResource::class;

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
        return 'Obat berhasil diperbarui';
    }
}
