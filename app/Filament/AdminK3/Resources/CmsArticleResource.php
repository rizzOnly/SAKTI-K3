<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\CmsArticleResource\Pages\CreateCmsArticle;
use App\Filament\AdminK3\Resources\CmsArticleResource\Pages\EditCmsArticle;
use App\Filament\AdminK3\Resources\CmsArticleResource\resourcePages\ListCmsArticles;
use App\Models\CmsArticle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{TextInput, Select, FileUpload, RichEditor, Toggle, DateTimePicker};
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Filament\Tables;

class CmsArticleResource extends Resource
{
    protected static ?string $model = CmsArticle::class;
    protected static ?string $navigationLabel = 'Artikel K3';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->label('Judul Artikel')
                ->required(),

            Select::make('category')
                ->label('Kategori')
                ->options([
                    'kampanye' => 'Kampanye K3',
                    'berita'   => 'Berita',
                    'panduan'  => 'Panduan',
                    'lainnya'  => 'Lainnya',
                ])
                ->required(),

            FileUpload::make('thumbnail')
                ->image()
                ->directory('cms/articles'),

            RichEditor::make('content')
                ->label('Konten')
                ->required()
                ->toolbarButtons([
                    'bold', 'italic', 'underline', 'link',
                    'bulletList', 'orderedList', 'h2', 'h3',
                ]),

            Toggle::make('is_published')
                ->label('Publish sekarang?')
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set) =>
                    $set('published_at', $state ? now() : null)
                ),

            DateTimePicker::make('published_at')
                ->label('Tanggal Publish')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),

                BadgeColumn::make('category')
                    ->label('Kategori')
                    ->colors([
                        'danger'  => 'kampanye',
                        'primary' => 'berita',
                        'success' => 'panduan',
                        'warning' => 'lainnya',
                    ]),

                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),

                TextColumn::make('published_at')
                    ->label('Tgl Publish')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCmsArticles::route('/'),
            'create' => CreateCmsArticle::route('/create'),
            'edit'   => EditCmsArticle::route('/{record}/edit'),
        ];
    }
}
