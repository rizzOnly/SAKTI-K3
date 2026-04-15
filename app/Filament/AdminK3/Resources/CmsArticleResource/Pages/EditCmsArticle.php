<?php
namespace App\Filament\AdminK3\Resources\CmsArticleResource\Pages;

use App\Filament\AdminK3\Resources\CmsArticleResource;
use Filament\Resources\Pages\EditRecord;

class EditCmsArticle extends EditRecord
{
    protected static string $resource = CmsArticleResource::class;

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
        return 'Artikel berhasil diperbarui';
    }
}
