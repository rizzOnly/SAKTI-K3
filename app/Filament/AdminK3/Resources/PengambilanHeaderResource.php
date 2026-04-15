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
use Filament\Forms\Components\{Select, TextInput, DatePicker, Textarea, Repeater};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables;
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

                TextColumn::make('user.name')
                    ->label('Pegawai')
                    ->searchable(),

                // TAMBAHAN: Kolom Bidang
                TextColumn::make('user.bidang')
                    ->label('Bidang')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('tanggal_pengajuan')
                    ->date('d/m/Y')
                    ->sortable(),

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
