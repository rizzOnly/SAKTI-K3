<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\ApdItemResource\Pages\CreateApdItem;
use App\Filament\AdminK3\Resources\ApdItemResource\Pages\EditApdItem;
use App\Filament\AdminK3\Resources\ApdItemResource\resourcePages\ListApdItems;
use App\Models\ApdItem;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, Toggle, DatePicker, FileUpload, Grid, Textarea};
use Filament\Tables\Columns\{TextColumn, IconColumn, BadgeColumn};
use Filament\Tables\Filters\{SelectFilter, Filter, TernaryFilter};
use Filament\Tables;

use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\LaporanInventarisExport;

class ApdItemResource extends Resource
{
    protected static ?string $model = ApdItem::class;
    protected static ?string $navigationLabel = 'Master Data APD';
    protected static ?string $navigationGroup = 'Master Data K3';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('kode_barang')
                    ->label('Kode Barang')
                    ->unique(ignoreRecord: true)
                    ->nullable()
                    ->default(fn() => ApdItem::generateKode()),

                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required(),

                TextInput::make('satuan')
                    ->required(),

                TextInput::make('merk')
                    ->nullable(),

                Select::make('kondisi')
                    ->options([
                        'baik'    => 'Baik',
                        'rusak'   => 'Rusak',
                        'expired' => 'Expired',
                    ])
                    ->default('baik')
                    ->required(),

                TextInput::make('stok')
                    ->numeric()
                    ->required()
                    ->default(0),

                TextInput::make('min_stok')
                    ->label('Minimum Stok')
                    ->numeric()
                    ->required()
                    ->default(5),

                Toggle::make('is_consumable')
                    ->label('Consumable (habis pakai)?')
                    ->default(true)
                    ->helperText('Matikan jika barang returnable/dipinjam kembali'),

                DatePicker::make('exp_date')
                    ->label('Tanggal Kedaluwarsa')
                    ->nullable(),

                TextInput::make('lokasi_gudang')
                    ->label('Lokasi Gudang')
                    ->nullable(),

                FileUpload::make('image_path')
                    ->label('Foto APD')
                    ->image()
                    ->directory('apd-images')
                    ->nullable()
                    ->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('satuan'),

                BadgeColumn::make('kondisi')
                    ->colors([
                        'success' => 'baik',
                        'danger'  => 'rusak',
                        'warning' => 'expired',
                    ]),

                TextColumn::make('stok')
                    ->sortable()
                    ->color(fn($record) => $record->stok <= $record->min_stok ? 'danger' : 'success'),

                TextColumn::make('min_stok')
                    ->label('Min Stok'),

                IconColumn::make('is_consumable')
                    ->label('Consumable')
                    ->boolean(),

                TextColumn::make('exp_date')
                    ->label('Exp Date')
                    ->date('d/m/Y')
                    ->color(fn($record) => $record->exp_date && $record->exp_date <= now()->addDays(30)
                        ? 'danger' : null),

                TextColumn::make('lokasi_gudang')
                    ->label('Lokasi'),
            ])
            ->filters([
                SelectFilter::make('kondisi')
                    ->options([
                        'baik'    => 'Baik',
                        'rusak'   => 'Rusak',
                        'expired' => 'Expired',
                    ]),

                TernaryFilter::make('is_consumable')
                    ->label('Consumable'),

                Filter::make('stok_kritis')
                    ->label('Stok Kritis')
                    ->query(fn($query) => $query->stokKritis()),

                Filter::make('akan_expired')
                    ->label('Akan Expired (30 hari)')
                    ->query(fn($query) => $query->akanExpired()),
            ])

            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(fn() => Excel::download(new LaporanInventarisExport(), 'inventaris-apd-' . date('Ymd') . '.xlsx')),

                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->action(function() {
                        $items = ApdItem::orderBy('nama_barang')->get();
                        $pdf = Pdf::loadView('reports.inventaris', compact('items'));

                        // Menggunakan streamDownload agar tidak bentrok dengan Livewire JSON response
                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'inventaris-apd-' . date('Ymd') . '.pdf');
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListApdItems::route('/'),
            'create' => CreateApdItem::route('/create'),
            'edit'   => EditApdItem::route('/{record}/edit'),
        ];
    }
}
