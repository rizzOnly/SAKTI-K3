<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\CmsBannerResource\Pages\CreateCmsBanner;
use App\Filament\AdminK3\Resources\CmsBannerResource\Pages\EditCmsBanner;
use App\Filament\AdminK3\Resources\CmsBannerResource\resourcePages\ListCmsBanners;
use App\Models\CmsBanner;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{FileUpload, TextInput, Toggle};
use Filament\Tables\Columns\{TextColumn, IconColumn, ImageColumn};
use Filament\Tables;

class CmsBannerResource extends Resource
{
    protected static ?string $model = CmsBanner::class;
    protected static ?string $navigationLabel = 'Banner';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('image_path')
                ->label('Gambar Banner')
                ->image()
                ->required()
                ->directory('cms/banners'),

            TextInput::make('title')
                ->label('Judul')
                ->nullable(),

            TextInput::make('urutan')
                ->label('Urutan Tampil')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Preview')
                    ->height(60),

                TextColumn::make('title')
                    ->label('Judul'),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCmsBanners::route('/'),
            'create' => CreateCmsBanner::route('/create'),
            'edit'   => EditCmsBanner::route('/{record}/edit'),
        ];
    }
}
