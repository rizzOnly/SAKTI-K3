<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\ApdItem;

class LaporanInventarisExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
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
            'Stok Saat Ini', 'Minimum Stok', 'Consumable', 'Exp Date', 'Lokasi Gudang',
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
            $item->lokasi_gudang ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => 'solid',
                    'startColor' => ['rgb' => '003D7C'],
                ],
            ],
        ];
    }
}
