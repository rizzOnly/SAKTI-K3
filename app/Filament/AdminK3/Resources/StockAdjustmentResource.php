<?php

namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\StockAdjustmentResource\resourcePages;
use App\Models\StockAdjustment;
use App\Models\ApdItem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    Select,
    TextInput,
    Textarea
};
use Filament\Tables\Columns\{
    TextColumn,
    BadgeColumn
};
use Filament\Tables;

class StockAdjustmentResource extends Resource
{
    protected static ?string $model = StockAdjustment::class;
    protected static ?string $navigationLabel = 'Stock Adjustment';
    protected static ?string $navigationGroup = 'Master Data K3';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('apd_item_id')
                ->label('Item APD')
                ->relationship('apdItem', 'nama_barang')
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $item = ApdItem::find($state);
                    $set('stok_saat_ini', $item?->stok ?? 0);
                }),

            TextInput::make('stok_saat_ini')
                ->label('Stok Saat Ini')
                ->disabled()
                ->dehydrated(false),

            Select::make('tipe')
                ->options([
                    'tambah' => 'Penambahan (Stok Masuk)',
                    'kurang' => 'Rusak / Hilang (Stok Kurang)',
                    'penyesuaian' => 'Penyesuaian (Koreksi Stok)',
                ])
                ->required()
                ->native(false)
                ->helperText('Pilih jenis penyesuaian yang sesuai'),

            TextInput::make('jumlah')
                ->numeric()
                ->required()
                ->minValue(1)
                ->helperText('Jumlah unit yang ditambahkan/dikurangi/dikoreksi'),

            Textarea::make('keterangan')
                ->required()
                ->helperText('Contoh: Barang rusak, penerimaan baru, koreksi salah input'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apdItem.nama_barang')
                    ->label('Item APD')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('tipe')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'tambah',
                        'danger'  => 'kurang',
                        'warning' => 'penyesuaian',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'tambah' => 'MASUK',
                        'kurang' => 'KURANG',
                        'penyesuaian' => 'PENYESUAIAN',
                    }),

                TextColumn::make('jumlah')
                    ->sortable()
                    ->color(fn ($record) => $record->tipe === 'tambah' ? 'success' : 'danger'),

                TextColumn::make('keterangan')
                    ->limit(50),

                TextColumn::make('user.name')
                    ->label('Oleh')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'tambah' => 'Penambahan',
                        'kurang' => 'Rusak/Hilang',
                        'penyesuaian' => 'Penyesuaian',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => resourcePages\ListStockAdjustments::route('/'),
            'create' => resourcePages\CreateStockAdjustment::route('/create'),
            'view'   => resourcePages\ViewStockAdjustment::route('/{record}'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
