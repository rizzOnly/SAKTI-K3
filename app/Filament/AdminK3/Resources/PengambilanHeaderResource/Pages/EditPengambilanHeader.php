<?php
namespace App\Filament\AdminK3\Resources\PengambilanHeaderResource\Pages;

use App\Filament\AdminK3\Resources\PengambilanHeaderResource;
use Filament\Resources\Pages\EditRecord;

class EditPengambilanHeader extends EditRecord
{
    protected static string $resource = PengambilanHeaderResource::class;

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
        return 'Pengambilan APD berhasil diperbarui';
    }
}
