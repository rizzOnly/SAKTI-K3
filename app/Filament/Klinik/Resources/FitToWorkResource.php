<?php
namespace App\Filament\Klinik\Resources;

use App\Models\FitToWork;
use App\Models\FitToWorkPekerja;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{Section, TextInput, Textarea, DatePicker, Select, Repeater, Grid};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Actions\{EditAction, ViewAction};
use Filament\Tables\Filters\SelectFilter;

class FitToWorkResource extends Resource
{
    protected static ?string $model           = FitToWork::class;
    protected static ?string $navigationLabel = 'Fit to Work';
    protected static ?string $navigationIcon  = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Rekam Medis';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Submission')->schema([
                Grid::make(2)->schema([
                    TextInput::make('nama_perusahaan')->label('Perusahaan')->disabled(),
                    TextInput::make('tipe')->label('Tipe')->disabled(),
                    TextInput::make('nama_pekerjaan')->label('Pekerjaan')->disabled()->columnSpan(2),
                    TextInput::make('tanggal_mulai')->label('Tgl Mulai')->disabled(),
                    TextInput::make('tanggal_selesai')->label('Tgl Selesai')->disabled(),
                ]),
            ]),

            Section::make('Hasil Pemeriksaan Per Pekerja')
                ->description('Isi hasil pemeriksaan untuk setiap pekerja secara individual.')
                ->schema([
                    Repeater::make('pekerjas')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('nama')
                                    ->label('Nama Pekerja')
                                    ->disabled(),

                                TextInput::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->disabled()
                                    ->formatStateUsing(fn($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                            ]),

                            Grid::make(2)->schema([
                                Select::make('status')
                                    ->label('Kesimpulan Pemeriksaan')
                                    ->options([
                                        'menunggu'  => 'Menunggu Pemeriksaan',
                                        'fit'       => '✅ Fit to Work',
                                        'tidak_fit' => '❌ Tidak Fit',
                                    ])
                                    ->required(),

                                DatePicker::make('tanggal_periksa')
                                    ->label('Tanggal Periksa')
                                    ->default(today()),
                            ]),

                            Grid::make(2)->schema([
                                TextInput::make('dokter_nama')
                                    ->label('Nama Dokter')
                                    ->default(fn() => auth()->user()?->name),

                                Textarea::make('catatan_dokter')
                                    ->label('Catatan')
                                    ->rows(2),
                            ]),
                        ])
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false)
                        ->itemLabel(fn(array $state): string =>
                            ($state['nama'] ?? 'Pekerja') .
                            match($state['status'] ?? 'menunggu') {
                                'fit'       => ' ✅',
                                'tidak_fit' => ' ❌',
                                default     => ' ⏳',
                            }
                        )
                        ->collapsible(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_perusahaan')
                    ->label('Perusahaan')
                    ->default('Internal PLN')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn($state) => $state === 'vendor' ? 'warning' : 'info'),

                TextColumn::make('nama_pekerjaan')
                    ->label('Pekerjaan')
                    ->limit(40),

                TextColumn::make('pekerjas_count')
                    ->label('Jumlah Pekerja')
                    ->counts('pekerjas')
                    ->badge()
                    ->color('info'),

                TextColumn::make('pekerjas_fit')
                    ->label('Sudah Fit')
                    ->getStateUsing(fn($record) =>
                        $record->pekerjas()->where('status', 'fit')->count() .
                        ' / ' .
                        $record->pekerjas()->count()
                    )
                    ->badge()
                    ->color(fn($record) =>
                        $record->pekerjas()->where('status', 'menunggu')->count() === 0
                            ? 'success' : 'warning'
                    ),

                TextColumn::make('tanggal_mulai')->label('Tgl Mulai')->date('d/m/Y'),
                TextColumn::make('tanggal_selesai')->label('Tgl Selesai')->date('d/m/Y'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('tipe')->options([
                    'internal' => 'Internal',
                    'vendor'   => 'Vendor',
                ]),
            ])
            ->actions([
                EditAction::make()->label('Periksa Pekerja'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Klinik\Resources\FitToWorkResource\Pages\ListFitToWorks::route('/'),
            'edit'  => \App\Filament\Klinik\Resources\FitToWorkResource\Pages\EditFitToWork::route('/{record}/edit'),
        ];
    }
}
