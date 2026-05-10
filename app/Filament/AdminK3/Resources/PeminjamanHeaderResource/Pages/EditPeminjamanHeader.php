<?php

namespace App\Filament\AdminK3\Resources\PeminjamanHeaderResource\Pages;

use App\Filament\AdminK3\Resources\PeminjamanHeaderResource;
use Filament\Resources\Pages\EditRecord;

class EditPeminjamanHeader extends EditRecord
{
    protected static string $resource = PeminjamanHeaderResource::class;

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
        return 'Peminjaman APD berhasil diperbarui';
    }
}
