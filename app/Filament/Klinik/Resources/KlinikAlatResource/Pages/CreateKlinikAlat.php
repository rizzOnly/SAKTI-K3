<?php
namespace App\Filament\Klinik\Resources\KlinikAlatResource\Pages;

use App\Filament\Klinik\Resources\KlinikAlatResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKlinikAlat extends CreateRecord
{
    protected static string $resource = KlinikAlatResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Alat medis berhasil ditambahkan';
    }
}
