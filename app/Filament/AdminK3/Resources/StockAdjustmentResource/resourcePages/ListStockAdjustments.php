<?php

namespace App\Filament\AdminK3\Resources\StockAdjustmentResource\resourcePages;

use App\Filament\AdminK3\Resources\StockAdjustmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockAdjustments extends ListRecords
{
    protected static string $resource = StockAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Stock Adjustment')
                ->icon('heroicon-o-plus')
                ->color('success'),
        ];
    }
}
