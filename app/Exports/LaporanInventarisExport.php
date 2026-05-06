<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithTitle,
    WithStyles,
    WithColumnWidths
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Alignment,
    Fill,
    Border,
    Font
};
use Illuminate\Support\Facades\DB;
use App\Models\ApdItem;
use App\Models\StockAdjustment;

class LaporanInventarisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles, WithColumnWidths
{
    private static int $no = 0;

    public function __construct(
        private string $start_date,
        private string $end_date
    ) {}

    public function collection()
    {
        // Get all APD items with their stock adjustments in the period
        $items = ApdItem::with(['stockAdjustments' => function ($q) {
            $q->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }])->orderBy('nama_barang')->get();

        return $items;
    }

    public function headings(): array
    {
        $start = $this->start_date;
        $end = $this->end_date;
        return [
            ["LAPORAN INVENTARIS APD", "Periode: {$start} s/d {$end}", '', '', '', '', '', '', '', '', '', ''],
            [
                'NO',
                'NAMA BARANG',
                'SATUAN',
                'MERK',
                'KONDISI',
                'STOCK AWAL',
                'KELUAR',
                'MASUK',
                'RUSAK',
                'MINIMUM',
                'SISA',
                'TINDAKAN'
            ],
        ];
    }

    public function map($item): array
    {
        self::$no++;

        $adjustments = $item->stockAdjustments;

        $masuk = $adjustments->where('tipe', 'tambah')->sum('jumlah');
        $rusak = $adjustments->where('tipe', 'kurang')->sum('jumlah');
        $keluar = $adjustments->where('tipe', 'penyesuaian')->sum('jumlah'); // assuming penyesuaian = keluar

        $current = $item->stok;
        $stock_awal = $current - $masuk + $rusak + $keluar;
        if ($stock_awal < 0) $stock_awal = 0;

        $sisa = $current;
        $tindakan = ($sisa <= $item->min_stok) ? 'PERLU TINDAKAN' : 'AMAN';

        return [
            self::$no,
            $item->nama_barang,
            $item->satuan,
            $item->merk ?? '-',
            strtoupper($item->kondisi),
            $stock_awal,
            $keluar,
            $masuk,
            $rusak,
            $item->min_stok,
            $sisa,
            $tindakan,
        ];
    }

    public function title(): string
    {
        return 'Inventaris APD';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 35,
            'C' => 10,
            'D' => 15,
            'E' => 12,
            'F' => 12,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'K' => 10,
            'L' => 18,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 13]],

            // Merge cells for title row
            'A1:L1' => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true],
            ],

            // Header row (row 2) style
            'A2:L2' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '003D7C']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],

            // Data rows borders
            'A3:L' . (count($this->collection()) + 2) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'AAAAAA'],
                    ],
                ],
            ],

            // TINDAKAN column color conditionally via cell style? Not straightforward; skip.

            // Alternating row colors optional: skip
        ];
    }
}
