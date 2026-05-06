<?php

namespace App\Filament\AdminK3\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\{
    TextColumn,
    BadgeColumn
};
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ApdItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanInventarisExport;

class LaporanInventaris extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Laporan Inventaris';
    protected static ?string $navigationGroup = 'Laporan';
    protected static string $view = 'filament.admin-k3.pages.laporan-inventaris';

    public ?string $filter_start_date = null;
    public ?string $filter_end_date = null;

    public function mount(): void
    {
        $this->filter_start_date = now()->startOfMonth()->format('Y-m-d');
        $this->filter_end_date = now()->format('Y-m-d');
    }

    public function updated($name, $value): void
    {
        if (in_array($name, ['filter_start_date', 'filter_end_date'])) {
            $this->resetTable();
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            \Filament\Forms\Components\Grid::make(2)->schema([
                DatePicker::make('filter_start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live(),

                DatePicker::make('filter_end_date')
                    ->label('Tanggal Akhir')
                    ->required()
                    ->live(),
            ]),
        ]);
    }

    protected function getTableQuery(): Builder
    {
        $start = $this->filter_start_date;
        $end = $this->filter_end_date;

        return ApdItem::with(['stockAdjustments' => fn ($q) => $q->whereBetween('created_at', [$start, $end])]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('nama_barang')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('satuan')->label('Satuan')->sortable(),
                TextColumn::make('merk')->label('Merk')->sortable(),

                BadgeColumn::make('kondisi')
                    ->label('Kondisi')
                    ->colors(['success' => 'baik', 'danger' => 'rusak', 'warning' => 'expired']),

                TextColumn::make('stock_awal')
                    ->label('Stock Awal')
                    ->getStateUsing(function ($record) {
                        $adj = $record->stockAdjustments;
                        $masuk = $adj->where('tipe', 'tambah')->sum('jumlah');
                        $rusak = $adj->where('tipe', 'kurang')->sum('jumlah');
                        $keluar = $adj->where('tipe', 'penyesuaian')->sum('jumlah');
                        return max(0, $record->stok - $masuk + $rusak + $keluar);
                    }),

                TextColumn::make('keluar')
                    ->label('Keluar')
                    ->getStateUsing(fn ($record) => $record->stockAdjustments->where('tipe', 'penyesuaian')->sum('jumlah'))
                    ->color('danger'),

                TextColumn::make('masuk')
                    ->label('Masuk')
                    ->getStateUsing(fn ($record) => $record->stockAdjustments->where('tipe', 'tambah')->sum('jumlah'))
                    ->color('success'),

                TextColumn::make('rusak')
                    ->label('Rusak/Hilang')
                    ->getStateUsing(fn ($record) => $record->stockAdjustments->where('tipe', 'kurang')->sum('jumlah'))
                    ->color('danger'),

                TextColumn::make('min_stok')->label('Minimum'),

                TextColumn::make('stok')
                    ->label('Sisa')
                    ->sortable()
                    ->color(fn ($record) => $record->stok <= $record->min_stok ? 'danger' : 'success'),

                TextColumn::make('tindakan')
                    ->label('Tindakan')
                    ->getStateUsing(fn ($record) => $record->stok <= $record->min_stok ? 'PERLU TINDAKAN' : 'AMAN')
                    ->badge()
                    ->color(fn ($record) => $record->stok <= $record->min_stok ? 'danger' : 'success'),
            ])
            ->defaultSort('nama_barang')
            ->filters([
                SelectFilter::make('kondisi')
                    ->label('Kondisi')
                    ->options(['baik' => 'Baik', 'rusak' => 'Rusak', 'expired' => 'Expired']),

                \Filament\Tables\Filters\Filter::make('stok_kritis')
                    ->label('Stok Kritis')
                    ->query(fn ($query) => $query->whereColumn('stok', '<=', 'min_stok')),
            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->action(function () {
                        $start = $this->filter_start_date;
                        $end = $this->filter_end_date;
                        return Excel::download(
                            new LaporanInventarisExport($start, $end),
                            'laporan-inventaris-' . $start . '-sampai-' . $end . '.xlsx'
                        );
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-list';
    }
}
