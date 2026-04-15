<?php
namespace App\Filament\Klinik\Resources\KlinikObatResource\Pages;

use App\Filament\Klinik\Resources\KlinikObatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKlinikObat extends CreateRecord
{
    protected static string $resource = KlinikObatResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Obat berhasil ditambahkan';
    }
}
