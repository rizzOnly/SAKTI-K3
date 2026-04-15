<?php
namespace App\Filament\Klinik\Resources;

use App\Filament\Klinik\Resources\RekamMedisResource\Pages\CreateRekamMedis;
use App\Filament\Klinik\Resources\RekamMedisResource\Pages\EditRekamMedis;
use App\Filament\Klinik\Resources\RekamMedisResource\Pages\ViewRekamMedis;
use App\Filament\Klinik\Resources\RekamMedisResource\resourcePages\ListRekamMedis;
use App\Models\{KlinikRekamMedis, KlinikAppointment, KlinikObat, User};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\{Select, Textarea, Repeater, TextInput};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanRekamMedisExport;

class RekamMedisResource extends Resource
{
    protected static ?string $model = KlinikRekamMedis::class;
    protected static ?string $navigationLabel = 'Rekam Medis';
    protected static ?string $navigationGroup = 'Pelayanan Klinik';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('appointment_id')
                ->label('Appointment')
                ->options(
                    KlinikAppointment::where('status', 'scheduled')
                        ->with('pasien')
                        ->get()
                        ->mapWithKeys(fn($a) => [
                            $a->id => "{$a->pasien->name} — {$a->tanggal->format('d/m/Y')} {$a->jam_slot}",
                        ])
                )
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $app = KlinikAppointment::with('pasien')->find($state);
                    if ($app) {
                        $set('user_id', $app->user_id);
                        $set('dokter_id', $app->dokter_id);
                    }
                }),

            Select::make('user_id')
                ->label('Pasien')
                ->relationship('pasien', 'name')
                ->searchable(),

            Select::make('dokter_id')
                ->label('Dokter')
                ->options(User::role('dokter')->pluck('name', 'id')),

            Textarea::make('diagnosa')
                ->required()
                ->rows(3),

            Textarea::make('tindakan')
                ->nullable()
                ->rows(2),

            Textarea::make('catatan')
                ->nullable()
                ->rows(2),

            Repeater::make('resepObat')
                ->relationship()
                ->label('Resep Obat')
                ->schema([
                    Select::make('obat_id')
                        ->label('Nama Obat')
                        ->options(fn() =>
                            KlinikObat::where('stok', '>', 0)
                                ->get()
                                ->mapWithKeys(fn($o) => [
                                    $o->id => "{$o->nama_barang} (Stok: {$o->stok})",
                                ])
                        )
                        ->required(),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->minValue(1),

                    TextInput::make('aturan_pakai')
                        ->placeholder('Contoh: 3x1 sesudah makan'),
                ])
                ->columns(3)
                ->addActionLabel('+ Tambah Resep'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pasien.name')
                    ->label('Pasien')
                    ->searchable(),

                TextColumn::make('pasien.nip')
                    ->label('NIP'),

                // TAMBAHAN: Kolom Bidang
                TextColumn::make('pasien.bidang')
                    ->label('Bidang')
                    ->default('-'),

                TextColumn::make('dokter.name')
                    ->label('Dokter'),

                TextColumn::make('diagnosa')
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_rekam_medis')
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
                        new LaporanRekamMedisExport($data['dari'], $data['sampai']),
                        'rekam-medis-' . $data['dari'] . '-sd-' . $data['sampai'] . '.xlsx'
                    )),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRekamMedis::route('/'),
            'create' => CreateRekamMedis::route('/create'),
            'view'   => ViewRekamMedis::route('/{record}'),
            'edit'   => EditRekamMedis::route('/{record}/edit'),
        ];
    }
}
