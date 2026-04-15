<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\PatrolPeriodeResource\Pages;
use App\Models\{PatrolPeriode, PatrolJadwal};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    TextInput, Select, Toggle, Section, Grid,
    Repeater, DatePicker
};
use Filament\Tables\Columns\{TextColumn, IconColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables;
use Filament\Notifications\Notification;

class PatrolPeriodeResource extends Resource
{
    protected static ?string $model           = PatrolPeriode::class;
    protected static ?string $navigationLabel = 'Patrol iZAT';
    protected static ?string $navigationGroup = 'CMS';
    protected static ?string $navigationIcon  = 'heroicon-o-eye';
    protected static ?int    $navigationSort  = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Periode Patrol')->schema([
                Grid::make(3)->schema([
                    Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            1=>'Januari',  2=>'Februari', 3=>'Maret',
                            4=>'April',    5=>'Mei',       6=>'Juni',
                            7=>'Juli',     8=>'Agustus',   9=>'September',
                            10=>'Oktober', 11=>'November', 12=>'Desember',
                        ])
                        ->default(now()->month)
                        ->required(),

                    TextInput::make('tahun')
                        ->label('Tahun')
                        ->numeric()
                        ->default(now()->year)
                        ->required(),

                    Toggle::make('is_active')
                        ->label('Tampil di Landing Page')
                        ->default(true),
                ]),

                TextInput::make('judul')
                    ->label('Judul (opsional)')
                    ->placeholder('Contoh: Jadwal Safety Patrol April 2026')
                    ->nullable(),
            ]),

            Section::make('Daftar Jadwal Petugas')
                ->description('Input nama, tanggal patrol, lokasi/unit, dan status laporan.')
                ->schema([
                    Repeater::make('jadwals')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Grid::make(4)->schema([
                                TextInput::make('nama_petugas')
                                    ->label('Nama Petugas')
                                    ->required()
                                    ->columnSpan(2),

                                DatePicker::make('tanggal_patrol')
                                    ->label('Tanggal Patrol')
                                    ->required()
                                    ->columnSpan(1),

                                TextInput::make('lokasi_unit')
                                    ->label('Lokasi / Unit')
                                    ->placeholder('GT 22 / COMMON 1 / ST18')
                                    ->nullable()
                                    ->columnSpan(1),
                            ]),

                            Grid::make(2)->schema([
                                Toggle::make('sudah_lapor')
                                    ->label('Sudah Lapor iZAT')
                                    ->default(false)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('lapor_at', $state ? now() : null);
                                    }),

                                TextInput::make('lapor_at')
                                    ->label('Waktu Lapor')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder('Otomatis terisi'),
                            ]),
                        ])
                        ->orderColumn('urutan') // ← ganti dari 'tanggal_patrol' ke 'urutan'
                        ->addActionLabel('+ Tambah Petugas')
                        ->collapsible()
                        ->reorderable() // ← aktifkan drag-reorder
                        ->itemLabel(fn(array $state): string =>
                            ($state['nama_petugas'] ?? 'Petugas baru') .
                            ($state['tanggal_patrol'] ? ' — ' . \Carbon\Carbon::parse($state['tanggal_patrol'])->format('d/m/Y') : '') .
                            ($state['sudah_lapor'] ? ' ✓' : '')
                        ),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bulan_tahun')
                    ->label('Periode')
                    ->getStateUsing(fn($record) =>
                        PatrolPeriode::namaBulan($record->bulan) . ' ' . $record->tahun
                    )
                    ->weight('semibold')
                    ->sortable(['tahun', 'bulan']),

                TextColumn::make('jadwals_count')
                    ->label('Total Petugas')
                    ->counts('jadwals')
                    ->badge()
                    ->color('info'),

                TextColumn::make('sudah_lapor_count')
                    ->label('Sudah Lapor')
                    ->getStateUsing(fn($record) =>
                        $record->jadwals()->where('sudah_lapor', true)->count() .
                        ' / ' .
                        $record->jadwals()->count()
                    )
                    ->badge()
                    ->color(fn($record) =>
                        $record->jadwals()->where('sudah_lapor', true)->count() ===
                        $record->jadwals()->count()
                        ? 'success' : 'warning'
                    ),

                TextColumn::make('minggu_ini_count')
                    ->label('Bertugas Minggu Ini')
                    ->getStateUsing(fn($record) =>
                        $record->jadwalsMingguIni()->count()
                    )
                    ->badge()
                    ->color('primary'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('tahun', 'desc')
            ->actions([
                EditAction::make(),

                Tables\Actions\Action::make('tandai_semua_lapor')
                    ->label('Tandai Semua Lapor')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai semua petugas sudah lapor?')
                    ->action(function ($record) {
                        $record->jadwals()
                            ->where('sudah_lapor', false)
                            ->update([
                                'sudah_lapor' => true,
                                'lapor_at'    => now(),
                            ]);
                        Notification::make()
                            ->title('Semua petugas ditandai sudah lapor!')
                            ->success()->send();
                    }),

                DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('import_excel')
                    ->label('Import dari Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->url(fn() => static::getUrl('import')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPatrolPeriodes::route('/'),
            'create' => Pages\CreatePatrolPeriode::route('/create'),
            'edit'   => Pages\EditPatrolPeriode::route('/{record}/edit'),
            'import' => Pages\ImportPatrol::route('/import'),
        ];
    }
}
