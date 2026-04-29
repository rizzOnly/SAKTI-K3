<?php

namespace App\Exports;

use App\Models\ApdItem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanInventarisExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    private int $no = 0; // Ubah dari static menjadi properti biasa

    public function title(): string
    {
        return 'Inventaris APD';
    }

    public function query()
    {
        return ApdItem::query()->orderBy('nama_barang');
    }

    public function headings(): array
    {
        return [
            'No', 'Kode', 'Nama Barang', 'Satuan', 'Merk', 'Kondisi',
            'Stok Saat Ini', 'Minimum Stok', 'Consumable', 'Exp Date',
        ];
    }

    public function map($item): array
    {
        $this->no++; // Panggil dengan $this

        return [
            $this->no,
            $item->kode_barang ?? '-',
            $item->nama_barang,
            $item->satuan,
            $item->merk ?? '-',
            ucfirst($item->kondisi),
            $item->stok,
            $item->min_stok,
            $item->is_consumable ? 'Ya' : 'Tidak',
            $item->exp_date?->format('d/m/Y') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '003D7C'],
                ],
            ],
        ];
    }
}
