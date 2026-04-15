<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\StockAdjustmentResource\Pages\CreateStockAdjustment;
use App\Filament\AdminK3\Resources\StockAdjustmentResource\Pages\ViewStockAdjustment;
use App\Filament\AdminK3\Resources\StockAdjustmentResource\resourcePages\ListStockAdjustments;
use App\Models\StockAdjustment;
use App\Models\ApdItem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{Select, TextInput, Textarea};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables;

class StockAdjustmentResource extends Resource
{
    protected static ?string $model = StockAdjustment::class;
    protected static ?string $navigationLabel = 'Stock Adjustment';
    protected static ?string $navigationGroup = 'Master Data K3';
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?int $navigationSort = 2;

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
                    'tambah' => 'Tambah Stok',
                    'kurang' => 'Kurangi Stok',
                ])
                ->required(),

            TextInput::make('jumlah')
                ->numeric()
                ->required()
                ->minValue(1),

            Textarea::make('keterangan')
                ->required()
                ->helperText('Contoh: Barang rusak, stock opname, penerimaan baru'),
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
                    ->colors([
                        'success' => 'tambah',
                        'danger'  => 'kurang',
                    ]),

                TextColumn::make('jumlah')
                    ->sortable(),

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
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListStockAdjustments::route('/'),
            'create' => CreateStockAdjustment::route('/create'),
            'view'   => ViewStockAdjustment::route('/{record}'),
        ];
    }

    // Auto-set user_id saat create
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
