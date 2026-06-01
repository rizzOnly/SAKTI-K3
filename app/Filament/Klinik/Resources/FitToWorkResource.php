<?php

namespace App\Filament\Klinik\Resources;

use App\Models\FitToWork;
use App\Models\FitToWorkPekerja;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{Section, TextInput, Textarea, DatePicker, Select, Repeater, Grid};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Actions\{EditAction, ViewAction, DeleteAction};
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
            Section::make('Informasi Pekerjaan / Vendor')->schema([
                Grid::make(2)->schema([
                    Select::make('tipe')
                        ->label('Tipe')
                        ->options([
                            'internal' => 'Internal PLN',
                            'vendor'   => 'Vendor / Kontraktor',
                        ])
                        ->required()
                        ->default('internal'),

                    TextInput::make('nama_perusahaan')
                        ->label('Nama Perusahaan')
                        ->placeholder('Contoh: PT K3 Maju (Kosongkan jika Internal)')
                        ->nullable(),

                    TextInput::make('nama_pekerjaan')
                        ->label('Pekerjaan')
                        ->required()
                        ->columnSpan(2),

                    DatePicker::make('tanggal_mulai')
                        ->label('Tgl Mulai')
                        ->required(),

                    DatePicker::make('tanggal_selesai')
                        ->label('Tgl Selesai')
                        ->required(),
                ]),
            ]),

            Section::make('Hasil Pemeriksaan Per Pekerja')
                ->description('Tambahkan pekerja dan isi hasil pemeriksaan secara individual.')
                ->schema([
                    Repeater::make('pekerjas')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('nama')
                                    ->label('Nama Pekerja')
                                    ->required(),

                                Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                            ]),

                            Grid::make(2)->schema([
                                Select::make('status')
                                    ->label('Kesimpulan Pemeriksaan')
                                    ->options([
                                        'menunggu'  => 'Menunggu Pemeriksaan',
                                        'fit'       => '✅ Fit to Work',
                                        'tidak_fit' => '❌ Tidak Fit',
                                    ])
                                    ->default('menunggu')
                                    ->required(),

                                DatePicker::make('tanggal_periksa')
                                    ->label('Tanggal Periksa')
                                    ->default(today()),
                            ]),

                            Grid::make(2)->schema([
                                // ── PERBAIKAN DROPDOWN NAMA DOKTER ──
                                Select::make('dokter_nama')
                                    ->label('Nama Dokter')
                                    ->options(function () {
                                        return User::role('dokter')->pluck('name', 'name');
                                    })
                                    ->searchable()
                                    ->default(function () {
                                        $user = auth()->user();
                                        if ($user && $user->hasRole('dokter')) {
                                            return $user->name;
                                        }
                                        return null;
                                    }),

                                Textarea::make('catatan_dokter')
                                    ->label('Catatan')
                                    ->rows(2),
                            ]),
                        ])
                        ->addActionLabel('+ Tambah Pekerja')
                        ->collapsible()
                        ->itemLabel(fn(array $state): string =>
                            ($state['nama'] ?? 'Pekerja Baru') .
                            match($state['status'] ?? 'menunggu') {
                                'fit'       => ' ✅',
                                'tidak_fit' => ' ❌',
                                default     => ' ⏳',
                            }
                        ),
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
                        $record->pekerjas()->count() > 0 && $record->pekerjas()->where('status', 'menunggu')->count() === 0
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
                EditAction::make()->label('Edit / Periksa'),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => \App\Filament\Klinik\Resources\FitToWorkResource\Pages\ListFitToWorks::route('/'),
            'create' => \App\Filament\Klinik\Resources\FitToWorkResource\Pages\CreateFitToWork::route('/create'),
            'edit'   => \App\Filament\Klinik\Resources\FitToWorkResource\Pages\EditFitToWork::route('/{record}/edit'),
        ];
    }
}
