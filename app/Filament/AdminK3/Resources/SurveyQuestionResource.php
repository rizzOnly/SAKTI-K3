<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\SurveyQuestionResource\Pages;
use App\Models\SurveyQuestion;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    TextInput, Textarea, Toggle, FileUpload,
    Repeater, Section, Grid
};
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction, DeleteBulkAction};
use Filament\Tables;
use Illuminate\Support\Str;

class SurveyQuestionResource extends Resource
{
    protected static ?string $model = SurveyQuestion::class;
    protected static ?string $navigationLabel = 'Soal Survey K3';
    protected static ?string $navigationGroup = 'Vendor & Gate Access';
    protected static ?string $navigationIcon  = 'heroicon-o-question-mark-circle';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Soal Pertanyaan')->schema([
                Grid::make(1)->schema([
                    Textarea::make('pertanyaan')
                        ->label('Teks Pertanyaan')
                        ->required()
                        ->rows(3)
                        ->placeholder('Contoh: Pada saat bekerja di ketinggian 1.8m, APD apa yang wajib digunakan?'),

                    FileUpload::make('gambar_soal')
                        ->label('Gambar Soal (opsional)')
                        ->image()
                        ->directory('survey/soal')
                        ->nullable()
                        ->helperText('Upload gambar pendukung soal jika diperlukan'),

                    Grid::make(2)->schema([
                        TextInput::make('urutan')
                            ->label('Urutan Soal')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka kecil = tampil lebih awal'),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan dari survey'),
                    ]),
                ]),
            ]),

            Section::make('Pilihan Jawaban')
                ->description('Tambahkan minimal 2 pilihan. Tandai satu sebagai jawaban BENAR.')
                ->schema([
                    Repeater::make('options')
                        ->relationship() // <-- Ini menghubungkan Soal dengan Opsi Jawaban
                        ->label('')
                        ->schema([
                            Grid::make(12)->schema([
                                Textarea::make('teks_opsi')
                                    ->label('Teks Jawaban')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpan(5),

                                FileUpload::make('gambar_opsi')
                                    ->label('Gambar (opsional)')
                                    ->image()
                                    ->directory('survey/opsi')
                                    ->nullable()
                                    ->columnSpan(5),

                                Grid::make(1)->schema([
                                    TextInput::make('urutan')
                                        ->label('Urutan')
                                        ->numeric()
                                        ->default(0),

                                    Toggle::make('is_benar')
                                        ->label('✓ Benar')
                                        ->helperText('Hanya 1 per soal'),
                                ])->columnSpan(2),
                            ]),
                        ])
                        ->orderColumn('urutan')
                        ->minItems(2)
                        ->maxItems(6)
                        ->addActionLabel('+ Tambah Pilihan Jawaban')
                        ->collapsible()
                        ->itemLabel(fn(array $state) => $state['teks_opsi']
                            ? Str::limit($state['teks_opsi'], 50)
                            : 'Pilihan baru'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('urutan')
                    ->label('No.')
                    ->sortable()
                    ->width(60),

                TextColumn::make('pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(80)
                    ->searchable()
                    ->wrap(),

                TextColumn::make('options_count')
                    ->label('Jml Pilihan')
                    ->counts('options')
                    ->badge()
                    ->color('info'),

                TextColumn::make('correct_option')
                    ->label('Jawaban Benar')
                    ->getStateUsing(fn($record) =>
                        $record->options()->where('is_benar', true)->value('teks_opsi') ?? '—'
                    )
                    ->limit(40)
                    ->color('success'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('urutan')
            ->reorderable('urutan') // <-- Bisa drag and drop urutan soal di tabel!
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
            ])
            ->headerActions([
                // Tombol Preview (Nanti kita buat halamannya)
                Tables\Actions\Action::make('preview_survey')
                    ->label('Preview Survey')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn() => url('/vendor/survey/preview'))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSurveyQuestions::route('/'),
            'create' => Pages\CreateSurveyQuestion::route('/create'),
            'edit'   => Pages\EditSurveyQuestion::route('/{record}/edit'),
        ];
    }
}
