<?php

namespace App\Filament\AdminK3\Widgets;

use App\Models\ApdItem;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StokKritisWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('⚠️ Item APD Stok Kritis')
            ->query(
                ApdItem::stokKritis()->orderBy('stok')
            )
            ->columns([
                TextColumn::make('kode_barang')->label('Kode'),
                TextColumn::make('nama_barang')->label('Nama Barang')->weight('bold'),
                TextColumn::make('satuan'),
                TextColumn::make('stok')
                    ->color('danger')
                    ->weight('bold'),
                TextColumn::make('min_stok')->label('Min Stok'),
            ]);
    }
}
