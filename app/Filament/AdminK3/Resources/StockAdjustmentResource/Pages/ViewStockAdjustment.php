<?php
namespace App\Filament\AdminK3\Resources\StockAdjustmentResource\Pages;

use App\Filament\AdminK3\Resources\StockAdjustmentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewStockAdjustment extends ViewRecord
{
    protected static string $resource = StockAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
