<?php
namespace App\Filament\Klinik\Resources;

use App\Filament\Klinik\Resources\KlinikAlatResource\Pages\CreateKlinikAlat;
use App\Filament\Klinik\Resources\KlinikAlatResource\Pages\EditKlinikAlat;
use App\Filament\Klinik\Resources\KlinikAlatResource\resourcePages\ListKlinikAlats;
use App\Models\KlinikAlat;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, DatePicker, Grid};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;

class KlinikAlatResource extends Resource
{
    protected static ?string $model = KlinikAlat::class;
    protected static ?string $navigationLabel = 'Alat Medis';
    protected static ?string $navigationGroup = 'Master Data Klinik';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                TextInput::make('nama_barang')
                    ->label('Nama Alat')
                    ->required(),

                TextInput::make('satuan')
                    ->required(),

                TextInput::make('stok')
                    ->numeric()
                    ->required()
                    ->default(1),

                DatePicker::make('tgl_kalibrasi_terakhir')
                    ->label('Kalibrasi Terakhir'),

                DatePicker::make('tgl_kalibrasi_ulang')
                    ->label('Kalibrasi Ulang')
                    ->helperText('EWS: Warning muncul jika < 7 hari dari sekarang'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang')
                    ->label('Nama Alat')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('satuan'),

                TextColumn::make('stok'),

                TextColumn::make('tgl_kalibrasi_terakhir')
                    ->label('Kalibrasi Terakhir')
                    ->date('d/m/Y'),

                TextColumn::make('tgl_kalibrasi_ulang')
                    ->label('Kalibrasi Ulang')
                    ->date('d/m/Y')
                    ->color(fn($record) => $record->tgl_kalibrasi_ulang && $record->tgl_kalibrasi_ulang <= now()->addDays(7)
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
            'index'  => ListKlinikAlats::route('/'),
            'create' => CreateKlinikAlat::route('/create'),
            'edit'   => EditKlinikAlat::route('/{record}/edit'),
        ];
    }
}
