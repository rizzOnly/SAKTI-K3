<?php
namespace App\Filament\Klinik\Resources;

use App\Filament\Klinik\Resources\KlinikObatResource\Pages\CreateKlinikObat;
use App\Filament\Klinik\Resources\KlinikObatResource\Pages\EditKlinikObat;
use App\Filament\Klinik\Resources\KlinikObatResource\resourcePages\ListKlinikObats;
use App\Models\KlinikObat;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, DatePicker, Grid};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables;

class KlinikObatResource extends Resource
{
    protected static ?string $model = KlinikObat::class;
    protected static ?string $navigationLabel = 'Data Obat';
    protected static ?string $navigationGroup = 'Master Data Klinik';
    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('kode_obat')
                    ->label('Kode Obat')
                    ->unique(ignoreRecord: true)
                    ->nullable(),

                TextInput::make('nama_barang')
                    ->label('Nama Obat')
                    ->required(),

                TextInput::make('satuan')
                    ->required(),

                TextInput::make('stok')
                    ->numeric()
                    ->required(),

                TextInput::make('min_stok')
                    ->label('Minimum Stok')
                    ->numeric()
                    ->required()
                    ->default(10),

                DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk'),

                DatePicker::make('tanggal_exp')
                    ->label('Tanggal Expired')
                    ->helperText('EWS: Warning muncul jika < 30 hari dari sekarang'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_obat')
                    ->label('Kode')
                    ->searchable(),

                TextColumn::make('nama_barang')
                    ->label('Nama Obat')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('satuan'),

                TextColumn::make('stok')
                    ->sortable()
                    ->color(fn($record) => $record->stok <= $record->min_stok ? 'danger' : 'success'),

                TextColumn::make('min_stok')
                    ->label('Min Stok'),

                TextColumn::make('tanggal_exp')
                    ->label('Exp Date')
                    ->date('d/m/Y')
                    ->color(fn($record) => $record->tanggal_exp && $record->tanggal_exp <= now()->addDays(30)
                        ? 'danger' : null),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListKlinikObats::route('/'),
            'create' => CreateKlinikObat::route('/create'),
            'edit'   => EditKlinikObat::route('/{record}/edit'),
        ];
    }
}
