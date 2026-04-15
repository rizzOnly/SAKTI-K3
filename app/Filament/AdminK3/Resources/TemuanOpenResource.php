<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\TemuanOpenResource\Pages;
use App\Models\TemuanOpen;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Toggle, Section, Grid};
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction};

class TemuanOpenResource extends Resource
{
    protected static ?string $model          = TemuanOpen::class;
    protected static ?string $navigationLabel = 'Temuan Open';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationIcon  = 'heroicon-o-exclamation-triangle';
    protected static ?int    $navigationSort  = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(3)->schema([
                    TextInput::make('bidang')
                        ->label('Nama Bidang')
                        ->required()
                        ->placeholder('Contoh: K3 & KEAMANAN'),

                    TextInput::make('jumlah_temuan')
                        ->label('Jumlah Temuan')
                        ->numeric()
                        ->required()
                        ->minValue(0),

                    TextInput::make('urutan')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),
                ]),
                Toggle::make('is_active')->label('Tampil di Landing Page')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')->label('No.')->sortable()->width(50),
                TextColumn::make('bidang')->label('Bidang')->searchable()->weight('semibold'),
                TextColumn::make('jumlah_temuan')
                    ->label('Jumlah Temuan')
                    ->badge()
                    ->color(fn($state) => $state > 10 ? 'danger' : ($state > 5 ? 'warning' : 'success')),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTemuanOpens::route('/'),
            'create' => Pages\CreateTemuanOpen::route('/create'),
            'edit'   => Pages\EditTemuanOpen::route('/{record}/edit'),
        ];
    }
}
