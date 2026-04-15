<?php
namespace App\Filament\AdminK3\Resources\PeminjamanHeaderResource\Pages;

use App\Filament\AdminK3\Resources\PeminjamanHeaderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePeminjamanHeader extends CreateRecord
{
    protected static string $resource = PeminjamanHeaderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Peminjaman APD berhasil dibuat';
    }
}
