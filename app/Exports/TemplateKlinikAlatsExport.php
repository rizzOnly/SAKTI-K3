<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromArray, WithHeadings, WithStyles,
    ShouldAutoSize, WithColumnWidths, WithTitle
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateKlinikAlatsExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithColumnWidths,
    WithTitle
{
    public function title(): string
    {
        return 'Data Alat Medis';
    }

    public function array(): array
    {
        // Baris contoh
        return [
            [
                'Stetoskop',               // nama_barang
                'Pcs',                     // satuan
                5,                         // stok
                '2025-06-15',              // tgl_kalibrasi_terakhir
                '2026-06-15',              // tgl_kalibrasi_ulang
            ],
            [
                'Sphygmomanometer',
                'Unit',
                3,
                '2025-08-20',
                '2026-02-20',
            ],
            [
                'Timbangan Bayi',
                'Unit',
                2,
                '2025-12-01',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'nama_barang',
            'satuan',
            'stok',
            'tgl_kalibrasi_terakhir',
            'tgl_kalibrasi_ulang',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28, // nama_barang
            'B' => 12, // satuan
            'C' => 8,  // stok
            'D' => 22, // tgl_kalibrasi_terakhir
            'E' => 22, // tgl_kalibrasi_ulang
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row styling
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
        ]);

        // Row contoh styling
        $sheet->getStyle('A2:E4')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'font' => ['color' => ['rgb' => '64748B'], 'italic' => true],
        ]);

        // Catatan di baris ke-6
        $sheet->setCellValue('A6', '📌 PETUNJUK:');
        $sheet->setCellValue('A7', '• Hapus baris contoh (baris 2-4) sebelum import');
        $sheet->setCellValue('A8', '• Kolom wajib: nama_barang, satuan, stok');
        $sheet->setCellValue('A9', '• Format tanggal: YYYY-MM-DD (contoh: 2026-12-31) atau kosongkan');
        $sheet->setCellValue('A10', '• Nama Alat yang sudah ada akan di-UPDATE (tidak duplikat)');
        $sheet->setCellValue('A11', '• EWS: Notifikasi warning akan muncul jika tgl_kalibrasi_ulang < 7 hari dari sekarang');

        $sheet->getStyle('A6:E11')->getFont()->setSize(9)->setColor(
            (new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'))
        );

        return [];
    }
}
