<?php
namespace App\Filament\Klinik\Resources\RekamMedisResource\Pages;

use App\Filament\Klinik\Resources\RekamMedisResource;
use Filament\Resources\Pages\EditRecord;

class EditRekamMedis extends EditRecord
{
    protected static string $resource = RekamMedisResource::class;

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
        return 'Rekam medis berhasil diperbarui';
    }
}
