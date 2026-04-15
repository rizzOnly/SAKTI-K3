<?php
namespace App\Filament\AdminK3\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\ApdItem;
use Filament\Tables\Table;

class EwsExpiredWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('🕐 APD Akan Expired (30 Hari)')
            ->query(
                ApdItem::akanExpired(30)->orderBy('exp_date')
            )
            ->columns([
                TextColumn::make('kode_barang')->label('Kode'),
                TextColumn::make('nama_barang')->label('Nama Barang')->weight('bold'),
                TextColumn::make('satuan'),
                TextColumn::make('stok'),
                TextColumn::make('exp_date')
                    ->label('Tanggal Exp')
                    ->date('d/m/Y')
                    ->color('danger'),
                TextColumn::make('lokasi_gudang')->label('Lokasi'),
            ]);
    }
}
