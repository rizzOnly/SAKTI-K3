<?php
namespace App\Filament\AdminK3\Resources\StockAdjustmentResource\Pages;

use App\Filament\AdminK3\Resources\StockAdjustmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStockAdjustment extends CreateRecord
{
    protected static string $resource = StockAdjustmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Stock adjustment berhasil dibuat';
    }
}
