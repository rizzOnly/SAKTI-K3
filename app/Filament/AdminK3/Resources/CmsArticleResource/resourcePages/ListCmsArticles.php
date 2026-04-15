<?php
namespace App\Filament\AdminK3\Resources\CmsArticleResource\resourcePages;

use App\Filament\AdminK3\Resources\CmsArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsArticles extends ListRecords
{
    protected static string $resource = CmsArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Artikel')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
