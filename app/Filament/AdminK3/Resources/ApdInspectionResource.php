<?php

namespace App\Filament\AdminK3\Resources;

use App\Filament\AdminK3\Resources\ApdInspectionResource\Pages;
use App\Models\ApdInspection;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\{
    DatePicker,
    Select,
    Textarea,
    Grid,
    Repeater,
    Hidden,
    Toggle,
    TextInput,
    Section,
    CheckboxList
};
use Filament\Tables\Columns\{
    TextColumn,
    BadgeColumn,
    IconColumn
};
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ApdInspectionResource extends Resource
{
    protected static ?string $model = ApdInspection::class;

    protected static ?string $navigationLabel = 'Inspeksi Bulanan APD';

    protected static ?string $navigationGroup = 'Master Data K3';

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Hidden field for bulan (YYYY-MM) with uniqueness validation
            Hidden::make('bulan')
                ->default(fn() => now()->format('Y-m'))
                ->required()
                ->unique(table: 'apd_inspections', column: 'bulan', ignoreRecord: true),

            Grid::make(2)->schema([
                DatePicker::make('tanggal_inspeksi')
                    ->label('Tanggal Inspeksi')
                    ->default(now())
                    ->required()
                    ->closeOnDateSelection()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $date = now()->parse($state);
                            $set('bulan', $date->format('Y-m'));
                        }
                    }),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'final' => 'Final',
                    ])
                    ->default('draft')
                    ->required(),
            ]),

            Textarea::make('catatan')
                ->label('Catatan Inspeksi')
                ->columnSpanFull()
                ->rows(2),

            Section::make('Daftar APD untuk Diinspeksi')
                ->description('Pilih APD yang akan diinspeksi dan isi kriteria kecacatan')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('details')
                        ->relationship()
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('apd_item_id')
                                    ->label('Item APD')
                                    ->options(fn() => \App\Models\ApdItem::orderBy('nama_barang')
                                        ->get()
                                        ->mapWithKeys(fn($item) => [
                                            $item->id => "{$item->nama_barang} (Stok: {$item->stok} {$item->satuan})",
                                        ])
                                        ->toArray())
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $item = \App\Models\ApdItem::find($state);
                                        if ($item) {
                                            $set('kondisi_sebelum', $item->kondisi);
                                            // Generate default criteria based on APD name
                                            $criteria = \App\Helpers\ApdInspectionHelper::getCriteriaForItem($item->nama_barang);
                                            $checklist = collect($criteria)->map(function ($c) {
                                                return [
                                                    'nama' => $c['nama'],
                                                    'key' => $c['key'],
                                                    'is_unfit' => false,
                                                    'keterangan' => null,
                                                ];
                                            })->toArray();
                                            $set('kriteria_ceklist', $checklist);
                                        }
                                    }),

                                TextInput::make('kondisi_sebelum')
                                    ->label('Kondisi Saat Ini')
                                    ->readOnly(),
                            ]),

                            Grid::make(2)->schema([
                                Select::make('kondisi_sesudah')
                                    ->label('Kondisi Setelah Inspeksi')
                                    ->options([
                                        'baik' => 'Baik',
                                        'rusak' => 'Rusak',
                                        'expired' => 'Expired',
                                    ])
                                    ->default('baik')
                                    ->required()
                                    ->native(false),

                                Toggle::make('is_tidak_layak')
                                    ->label('Tidak Layak (Cacat)')
                                    ->helperText('Centang jika item ini tidak layak pakai')
                                    ->reactive(),
                            ]),

                            // Dynamic criteria checklist (visible when item is unfit)
                            Repeater::make('kriteria_ceklist')
                                ->label('Kriteria Pengecekan')
                                ->schema([
                                    Grid::make(3)->schema([
                                        TextInput::make('nama')
                                            ->label('Kriteria')
                                            ->readOnly(),

                                        Toggle::make('is_unfit')
                                            ->label('Cacat?'),

                                        TextInput::make('keterangan')
                                            ->label('Keterangan')
                                            ->placeholder('Opsional')
                                            ->columnSpan(2),
                                    ]),
                                ])
                                ->columnSpanFull()
                                ->addable(false)
                                ->deletable(false)
                                ->defaultItems(0)
                                ->visible(fn(callable $get) => $get('is_tidak_layak'))
                                ->reactive(),

                            Textarea::make('alasan_tidak_layak')
                                ->label('Alasan Tidak Layak')
                                ->placeholder('Jelaskan mengapa item ini tidak layak...')
                                ->rows(2)
                                ->visible(fn(callable $get) => $get('is_tidak_layak'))
                                ->reactive(),
                        ])
                        ->columns(1)
                        ->minItems(1)
                        ->addActionLabel('+ Tambah Item APD untuk Diinspeksi')
                        ->deletable(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->sortable(),

                TextColumn::make('tanggal_inspeksi')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Inspektur')
                    ->sortable(),

                TextColumn::make('details_count')
                    ->label('Jumlah Item')
                    ->getStateUsing(fn($record) => $record->details->count())
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'final',
                    ]),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'final' => 'Final',
                    ]),

                SelectFilter::make('bulan')
                    ->label('Bulan')
                    ->options(fn() => \App\Models\ApdInspection::select('bulan')
                        ->distinct()
                        ->orderBy('bulan', 'desc')
                        ->get()
                        ->mapWithKeys(fn($i) => [$i->bulan => $i->bulan])
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('finalize')
                    ->label('Final')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'draft')
                    ->action(function ($record) {
                        $record->update(['status' => 'final']);
                        \Filament\Notifications\Notification::make()
                            ->title('Inspeksi berhasil difinalkan')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('print_report')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->url(fn($record) => route('filament.admin-k3.resources.apd-inspections.report', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApdInspections::route('/'),
            'create' => Pages\CreateApdInspection::route('/create'),
            'edit' => Pages\EditApdInspection::route('/{record}/edit'),
            'view' => Pages\ViewApdInspection::route('/{record}'),
            'report' => Pages\ReportApdInspection::route('/{record}/report'),
        ];
    }

    // Auto-set user_id when creating
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    // Auto-set user_id when updating (in case changed)
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['user_id']) || empty($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        return $data;
    }
}
