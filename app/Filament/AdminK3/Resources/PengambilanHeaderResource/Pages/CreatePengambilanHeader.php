<?php
namespace App\Filament\AdminK3\Resources\PengambilanHeaderResource\Pages;

use App\Filament\AdminK3\Resources\PengambilanHeaderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePengambilanHeader extends CreateRecord
{
    protected static string $resource = PengambilanHeaderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pengambilan APD berhasil dibuat';
    }
}
