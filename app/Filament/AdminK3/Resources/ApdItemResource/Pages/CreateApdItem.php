<?php
namespace App\Filament\AdminK3\Resources\ApdItemResource\Pages;

use App\Filament\AdminK3\Resources\ApdItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApdItem extends CreateRecord
{
    protected static string $resource = ApdItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'APD berhasil ditambahkan';
    }
}
