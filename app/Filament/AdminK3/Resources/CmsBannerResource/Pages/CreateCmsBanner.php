<?php
namespace App\Filament\AdminK3\Resources\CmsBannerResource\Pages;

use App\Filament\AdminK3\Resources\CmsBannerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsBanner extends CreateRecord
{
    protected static string $resource = CmsBannerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Banner berhasil ditambahkan';
    }
}
