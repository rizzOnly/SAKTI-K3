<?php
namespace App\Filament\Klinik\Resources\KlinikAlatResource\Pages;

use App\Filament\Klinik\Resources\KlinikAlatResource;
use Filament\Resources\Pages\EditRecord;

class EditKlinikAlat extends EditRecord
{
    protected static string $resource = KlinikAlatResource::class;

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
        return 'Alat medis berhasil diperbarui';
    }
}
