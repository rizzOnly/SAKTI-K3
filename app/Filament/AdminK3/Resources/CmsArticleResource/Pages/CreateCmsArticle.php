<?php
namespace App\Filament\AdminK3\Resources\CmsArticleResource\Pages;

use App\Filament\AdminK3\Resources\CmsArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsArticle extends CreateRecord
{
    protected static string $resource = CmsArticleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Artikel berhasil ditambahkan';
    }
}
