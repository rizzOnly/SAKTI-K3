<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\PeminjamanHeaderResource\Pages\CreatePeminjamanHeader;
use App\Filament\AdminK3\Resources\PeminjamanHeaderResource\Pages\ViewPeminjamanHeader;
use App\Filament\AdminK3\Resources\PeminjamanHeaderResource\resourcePages\ListPeminjamanHeaders;
use App\Models\{PeminjamanHeader, ApdItem};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\{TextColumn, BadgeColumn, ImageColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\{Select, TextInput, DatePicker, Textarea, Repeater, FileUpload, Section};

class PeminjamanHeaderResource extends Resource
{
    protected static ?string $model = PeminjamanHeader::class;
    protected static ?string $navigationLabel = 'Peminjaman APD';
    protected static ?string $navigationGroup = 'Transaksi APD';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor_transaksi')
                ->default(fn() => PeminjamanHeader::generateNomor())
                ->disabled()
                ->dehydrated(),

            Select::make('user_id')
                ->label('Pegawai (NIP)')
                ->relationship('user', 'name')
                ->searchable()
                ->required()
                // UPDATE: Menampilkan Bidang di dropdown saat memilih pegawai
                ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->nip}] {$record->name} (" . ($record->bidang ?? 'Tanpa Bidang') . ")"),

            DatePicker::make('tanggal_pengajuan')
                ->default(now())
                ->required(),

            DatePicker::make('tanggal_kembali_rencana')
                ->label('Rencana Tanggal Kembali')
                ->minDate(now()->addDay())
                ->required(),

            Select::make('status')
                ->options([
                    'pending'  => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'returned' => 'Dikembalikan',
                ])
                ->default('pending'),

            Textarea::make('catatan')->nullable(),

            Repeater::make('details')
                ->relationship()
                ->schema([
                    Select::make('apd_item_id')
                        ->label('APD (Returnable)')
                        ->options(function () {
                            return ApdItem::where('is_consumable', false)
                                ->get()
                                ->mapWithKeys(fn($i) => [
                                    $i->id => "{$i->nama_barang} (Stok: {$i->stok})",
                                ]);
                        })
                        ->searchable()
                        ->required(),

                    TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->default(1),
                ])
                ->columns(2)
                ->minItems(1)
                ->addActionLabel('+ Tambah Item Pinjam'),

            // Form Dokumentasi Peminjam (Tampil saat edit/view)
            Section::make('Dokumentasi Peminjam')
                ->description('Upload foto sebagai bukti dokumentasi peminjam APD (hanya diisi saat approve)')
                ->schema([
                    FileUpload::make('foto_dokumentasi')
                        ->label('Foto Dokumentasi Peminjam')
                        ->image()
                        ->directory('peminjaman/dokumentasi')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('4:3')
                        ->imageResizeTargetWidth('800')
                        ->imageResizeTargetHeight('600')
                        ->nullable()
                        ->helperText('Foto peminjam saat mengambil APD (JPG/PNG, maks 5MB)')
                        ->maxSize(5120)
                        ->visibility('public'),
                ])
                ->collapsible()
                ->collapsed(fn($record) => $record === null || empty($record->foto_dokumentasi)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_dokumentasi')
                    ->label('Foto')
                    ->height(40)
                    ->width(40)
                    ->circular()
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Foto&background=0D8ABC&color=fff')
                    ->toggleable(),

                TextColumn::make('nomor_transaksi')
                    ->label('No. Transaksi')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Pegawai')
                    ->searchable(), // Tambah searchable agar mudah dicari

                // TAMBAHAN: Kolom Bidang
                TextColumn::make('user.bidang')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('tanggal_kembali_rencana')
                    ->label('Tgl Rencana Kembali')
                    ->date('d/m/Y'),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                        'info'    => 'returned',
                    ]),

                TextColumn::make('returned_at')
                    ->label('Tgl Kembali')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),

                TextColumn::make('kondisi_kembali')
                    ->label('Kondisi Kembali')
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'returned' => 'Dikembalikan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman APD')
                    ->modalDescription('Silakan upload foto peminjam sedang memegang barang sebagai bukti dokumentasi K3.')
                    ->modalWidth('md')
                    ->form([
                        FileUpload::make('foto_dokumentasi')
                            ->label('Foto Bukti Peminjaman')
                            ->image()
                            ->directory('peminjaman/dokumentasi')
                            ->required()
                            ->helperText('Wajib diisi (JPG/PNG, Maks 5MB).')
                            ->maxSize(5120),
                    ])
                    ->action(function ($record, array $data) {
                        try {
                            DB::transaction(function () use ($record, $data) {
                                foreach ($record->details as $detail) {
                                    $item = ApdItem::find($detail->apd_item_id);
                                    if ($item->stok < $detail->jumlah) {
                                        throw new \Exception("Stok {$item->nama_barang} tidak mencukupi!");
                                    }
                                    $item->decrement('stok', $detail->jumlah);
                                }

                                $record->update([
                                    'status'           => 'approved',
                                    'approved_by'      => auth()->id(),
                                    'approved_at'      => now(),
                                    'foto_dokumentasi' => $data['foto_dokumentasi'],
                                ]);
                            });

                            Notification::make()->title('Peminjaman disetujui & Foto disimpan!')->success()->send();
                        } catch (\Exception $e) {
                            Notification::make()->title('Gagal: ' . $e->getMessage())->danger()->send();
                        }
                    }),

                Tables\Actions\Action::make('kembalikan')
                    ->label('Kembalikan')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('info')
                    ->visible(fn($record) => $record->status === 'approved')
                    ->form([
                        Select::make('kondisi_kembali')
                            ->options([
                                'baik'   => 'Baik',
                                'rusak'  => 'Rusak',
                                'hilang' => 'Hilang',
                            ])
                            ->required()
                            ->reactive(),
                    ])
                    ->action(function ($record, array $data) {
                        DB::transaction(function () use ($record, $data) {
                            if ($data['kondisi_kembali'] !== 'hilang') {
                                foreach ($record->details as $detail) {
                                    ApdItem::find($detail->apd_item_id)
                                        ->increment('stok', $detail->jumlah);
                                }
                            }

                            $record->update([
                                'status'          => 'returned',
                                'returned_at'     => now(),
                                'kondisi_kembali' => $data['kondisi_kembali'],
                            ]);
                        });

                        Notification::make()
                            ->title('APD berhasil dikembalikan!')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPeminjamanHeaders::route('/'),
            'create' => CreatePeminjamanHeader::route('/create'),
            'view'   => ViewPeminjamanHeader::route('/{record}'),
        ];
    }
}
