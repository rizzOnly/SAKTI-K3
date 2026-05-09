<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromArray, WithHeadings, WithStyles,
    ShouldAutoSize, WithColumnWidths, WithTitle
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateApdItemsExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithColumnWidths,
    WithTitle
{
    public function title(): string
    {
        return 'Data APD';
    }

    public function array(): array
    {
        // Baris contoh
        return [
            [
                'APD0001',    // kode_barang
                'Helm Safety',// nama_barang
                'Pcs',        // satuan
                'SafetyPro',  // merk
                'baik',       // kondisi (baik/rusak/expired)
                50,           // stok
                10,           // min_stok
                1,            // is_consumable (1=true, 0=false)
                '2026-12-31', // exp_date (format: YYYY-MM-DD)
                '',           // image_path (kosongkan)
            ],
            [
                'APD0002',
                'Safety Shoes',
                'Pasang',
                'Catcher',
                'baik',
                30,
                10,
                1,
                '2026-06-30',
                '',
            ],
            [
                'APD0003',
                'Sarung Tangan',
                'Box',
                'Ninja',
                'baik',
                100,
                20,
                1,
                '',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'kode_barang',
            'nama_barang',
            'satuan',
            'merk',
            'kondisi',
            'stok',
            'min_stok',
            'is_consumable',
            'exp_date',
            'image_path',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // kode_barang
            'B' => 25, // nama_barang
            'C' => 12, // satuan
            'D' => 15, // merk
            'E' => 12, // kondisi
            'F' => 8,  // stok
            'G' => 12, // min_stok
            'H' => 15, // is_consumable
            'I' => 14, // exp_date
            'J' => 20, // image_path
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row styling
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
        ]);

        // Row contoh styling (ab-abu muda)
        $sheet->getStyle('A2:J4')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'font' => ['color' => ['rgb' => '64748B'], 'italic' => true],
        ]);

        // Catatan di baris ke-6
        $sheet->setCellValue('A6', '📌 PETUNJUK:');
        $sheet->setCellValue('A7', '• Hapus baris contoh (baris 2-4) sebelum import');
        $sheet->setCellValue('A8', '• Kolom wajib: kode_barang, nama_barang, satuan, kondisi, stok, min_stok');
        $sheet->setCellValue('A9', '• Kolom kondisi: baik, rusak, expired');
        $sheet->setCellValue('A10', '• is_consumable: 1 (true) atau 0 (false)');
        $sheet->setCellValue('A11', '• exp_date format: YYYY-MM-DD (contoh: 2026-12-31) atau kosongkan');
        $sheet->setCellValue('A12', '• image_path: kosongkan, diisi manual setelah upload foto');
        $sheet->setCellValue('A13', '• Jika Kode Barang sudah ada, data akan di-UPDATE (tidak duplikat)');

        $sheet->getStyle('A6:J13')->getFont()->setSize(9)->setColor(
            (new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'))
        );

        return [];
    }
}
