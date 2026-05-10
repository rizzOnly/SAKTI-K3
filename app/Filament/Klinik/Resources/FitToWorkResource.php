<?php

namespace App\Filament\Klinik\Resources;

use App\Models\FitToWork;
use App\Exports\LaporanFitToWorkExport;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{Select, Textarea, DatePicker, TextInput, Section};
use Filament\Tables;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Maatwebsite\Excel\Facades\Excel;

class FitToWorkResource extends Resource
{
    protected static ?string $model           = FitToWork::class;
    protected static ?string $navigationLabel = 'Fit to Work';
    protected static ?string $navigationIcon  = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Rekam Medis';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Data Pasien')->schema([
                TextInput::make('nama')->label('Nama')->disabled(),
                TextInput::make('nama_perusahaan')->label('Perusahaan')->disabled(),
                TextInput::make('jenis_kelamin')->label('Jenis Kelamin')->disabled(),
                TextInput::make('nama_pekerjaan')->label('Pekerjaan Risiko Tinggi')->disabled(),
                TextInput::make('tanggal_mulai')->label('Tanggal Mulai')->disabled(),
                TextInput::make('tanggal_selesai')->label('Tanggal Selesai')->disabled(),
            ])->columns(2),

            Section::make('Hasil Pemeriksaan Dokter')->schema([
                Select::make('status')
                    ->label('Kesimpulan')
                    ->options([
                        'menunggu'   => 'Menunggu Pemeriksaan',
                        'fit'        => 'Fit to Work ✅',
                        'tidak_fit'  => 'Tidak Fit ❌',
                    ])
                    ->required(),

                Textarea::make('catatan_dokter')
                    ->label('Catatan Dokter')
                    ->rows(3),

                DatePicker::make('tanggal_periksa')
                    ->label('Tanggal Periksa')
                    ->default(today()),

                TextInput::make('dokter_nama')
                    ->label('Nama Dokter')
                    ->default(fn() => auth()->user()?->name),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama')->searchable()->weight('bold'),
                TextColumn::make('nama_perusahaan')->label('Perusahaan')->default('Internal')->searchable(),
                TextColumn::make('tipe')->label('Tipe')
                    ->badge()
                    ->color(fn($state) => $state === 'vendor' ? 'warning' : 'info'),
                TextColumn::make('nama_pekerjaan')->label('Pekerjaan')->limit(40),
                TextColumn::make('tanggal_mulai')->label('Mulai')->date('d/m/Y'),
                TextColumn::make('tanggal_selesai')->label('Selesai')->date('d/m/Y'),
                TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'fit'       => 'success',
                        'tidak_fit' => 'danger',
                        default     => 'warning',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'fit'       => 'Fit to Work',
                        'tidak_fit' => 'Tidak Fit',
                        default     => 'Menunggu',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options([
                    'menunggu'  => 'Menunggu',
                    'fit'       => 'Fit to Work',
                    'tidak_fit' => 'Tidak Fit',
                ]),
                SelectFilter::make('tipe')->options([
                    'internal' => 'Internal',
                    'vendor'   => 'Vendor',
                ]),
            ])
            ->actions([EditAction::make()->label('Periksa & Isi Hasil')])
            ->headerActions([
                Tables\Actions\Action::make('export_fit_to_work')
                    ->label('Export Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('dari')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfMonth())
                            ->required(),
                        \Filament\Forms\Components\DatePicker::make('sampai')
                            ->label('Sampai Tanggal')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(fn(array $data) => Excel::download(
                        new LaporanFitToWorkExport($data['dari'], $data['sampai']),
                        'fit-to-work-' . $data['dari'] . '-sd-' . $data['sampai'] . '.xlsx'
                    )),
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
