<?php
namespace App\Filament\AdminK3\Resources\CmsBannerResource\Pages;

use App\Filament\AdminK3\Resources\CmsBannerResource;
use Filament\Resources\Pages\EditRecord;

class EditCmsBanner extends EditRecord
{
    protected static string $resource = CmsBannerResource::class;

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
        return 'Banner berhasil diperbarui';
    }
}
