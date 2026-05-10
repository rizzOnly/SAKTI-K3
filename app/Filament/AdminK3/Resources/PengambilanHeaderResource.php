<?php
namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\PengambilanHeaderResource\Pages\CreatePengambilanHeader;
use App\Filament\AdminK3\Resources\PengambilanHeaderResource\Pages\EditPengambilanHeader;
use App\Filament\AdminK3\Resources\PengambilanHeaderResource\Pages\ViewPengambilanHeader;
use App\Filament\AdminK3\Resources\PengambilanHeaderResource\resourcePages\ListPengambilanHeaders;
use App\Models\{PengambilanHeader, ApdItem};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
// TAMBAHAN IMPORT FILEUPLOAD & SECTION
use Filament\Forms\Components\{Select, TextInput, DatePicker, Textarea, Repeater, FileUpload, Section};
// TAMBAHAN IMPORT ICONCOLUMN
use Filament\Tables\Columns\{TextColumn, BadgeColumn, IconColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;


class PengambilanHeaderResource extends Resource
{
    protected static ?string $model = PengambilanHeader::class;
    protected static ?string $navigationLabel = 'Pengambilan APD';
    protected static ?string $navigationGroup = 'Transaksi APD';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor_transaksi')
                ->default(fn() => PengambilanHeader::generateNomor())
                ->disabled()
                ->dehydrated(),

            // Guest flag (hidden input)
            Hidden::make('is_guest')->default(false),

            // PEGAWAI SECTION
            Section::make('Data Pegawai')
                ->schema([
                    Select::make('user_id')
                        ->label('Nama Pegawai')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(fn($record) => !$record || !$record->is_guest)
                        ->getOptionLabelFromRecordUsing(fn($record) => "[{$record->nid}] {$record->name} (" . ($record->bidang ?? 'Tanpa Bidang') . ")"),
                ])
                ->columns(1)
                ->hidden(fn($record) => $record && $record->is_guest)
                ->dehydratedWhenHidden(),

            // GUEST SECTION
            Section::make('Data Tamu')
                ->schema([
                    TextInput::make('guest_nama')
                        ->label('Nama Lengkap')
                        ->required(fn($record) => !$record || $record->is_guest)
                        ->maxLength(200),
                    TextInput::make('guest_perusahaan')
                        ->label('Perusahaan / Instansi')
                        ->maxLength(200),
                    TextInput::make('guest_no_wa')
                        ->label('No. WhatsApp')
                        ->maxLength(20),
                    TextInput::make('guest_email')
                        ->label('Email')
                        ->email()
                        ->maxLength(255),
                ])
                ->columns(2)
                ->hidden(fn($record) => !$record || !$record->is_guest)
                ->dehydratedWhenHidden(),

            DatePicker::make('tanggal_pengajuan')
                ->default(now())
                ->required(),

            Select::make('status')
                ->options([
                    'pending'  => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->default('pending'),

            Textarea::make('catatan')->nullable(),

            Repeater::make('details')
                ->relationship()
                ->schema([
                    Select::make('apd_item_id')
                        ->label('APD')
                        ->options(function () {
                            return ApdItem::where('is_consumable', true)
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
                ->addActionLabel('+ Tambah Item APD'),

            // TAMBAHAN: Upload Berkas Permit
            Section::make('Berkas Lampiran')->schema([
                FileUpload::make('berkas_permit')
                    ->label('Berkas Permit / JSA')
                    ->image()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->directory('apd/permit')
                    ->downloadable()
                    ->openable()
                    ->nullable(),
            ])->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_transaksi')
                    ->label('No. Transaksi')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('is_guest')
                    ->label('Tipe')
                    ->formatStateUsing(fn($state) => $state ? 'Tamu' : 'Pegawai')
                    ->colors([
                        'info' => false,
                        'warning' => true,
                    ])
                    ->toggleable()
                    ->hidden(fn($record) => !$record || !$record->is_guest),

                TextColumn::make('guest_nama')
                    ->label('Nama (Tamu)')
                    ->searchable()
                    ->toggleable()
                    ->hidden(fn($record) => !$record || !$record->is_guest),

                TextColumn::make('user.name')
                    ->label('Pegawai')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(fn($record) => !$record || $record->is_guest),

                TextColumn::make('user.bidang')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(fn($record) => !$record || $record->is_guest),

                TextColumn::make('tanggal_pengajuan')
                    ->date('d/m/Y')
                    ->sortable(),

                IconColumn::make('berkas_permit')
                    ->label('Berkas Permit')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->tooltip(fn($record) => $record->berkas_permit ? 'Ada berkas' : 'Tidak ada berkas'),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ]),

                TextColumn::make('approvedBy.name')
                    ->label('Disetujui Oleh')
                    ->placeholder('-'),

                TextColumn::make('approved_at')
                    ->label('Tgl Approve')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'  => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        DB::transaction(function () use ($record) {
                            foreach ($record->details as $detail) {
                                $item = ApdItem::find($detail->apd_item_id);
                                if ($item->stok < $detail->jumlah) {
                                    throw new \Exception("Stok {$item->nama_barang} tidak mencukupi!");
                                }
                                $item->decrement('stok', $detail->jumlah);
                            }

                            $record->update([
                                'status'      => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                            ]);
                        });

                        Notification::make()
                            ->title('Pengambilan disetujui!')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'           => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);

                        Notification::make()
                            ->title('Pengambilan ditolak')
                            ->danger()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPengambilanHeaders::route('/'),
            'create' => CreatePengambilanHeader::route('/create'),
            'edit'   => EditPengambilanHeader::route('/{record}/edit'),
            'view'   => ViewPengambilanHeader::route('/{record}'),
        ];
    }
}
