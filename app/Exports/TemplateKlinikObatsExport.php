<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromArray, WithHeadings, WithStyles,
    ShouldAutoSize, WithColumnWidths, WithTitle
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateKlinikObatsExport implements
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize,
    WithColumnWidths,
    WithTitle
{
    public function title(): string
    {
        return 'Data Obat';
    }

    public function array(): array
    {
        // Baris contoh
        return [
            [
                'OBT001',            // kode_obat
                'Paracetamol 500mg',// nama_barang
                'Tablet',           // satuan
                100,                // stok
                20,                 // min_stok
                '2026-01-15',       // tanggal_masuk
                '2027-01-15',       // tanggal_exp
            ],
            [
                'OBT002',
                'Amoxicillin 250mg',
                'Kapsul',
                50,
                15,
                '2026-02-01',
                '2026-08-01',
            ],
            [
                'OBT003',
                'Bandage',
                'Roll',
                30,
                10,
                '2026-03-10',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'kode_obat',
            'nama_barang',
            'satuan',
            'stok',
            'min_stok',
            'tanggal_masuk',
            'tanggal_exp',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // kode_obat
            'B' => 25, // nama_barang
            'C' => 12, // satuan
            'D' => 8,  // stok
            'E' => 12, // min_stok
            'F' => 16, // tanggal_masuk
            'G' => 14, // tanggal_exp
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row styling
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '059669']],
        ]);

        // Row contoh styling
        $sheet->getStyle('A2:G4')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'font' => ['color' => ['rgb' => '64748B'], 'italic' => true],
        ]);

        // Catatan di baris ke-6
        $sheet->setCellValue('A6', '📌 PETUNJUK:');
        $sheet->setCellValue('A7', '• Hapus baris contoh (baris 2-4) sebelum import');
        $sheet->setCellValue('A8', '• Kolom wajib: kode_obat, nama_barang, satuan, stok, min_stok');
        $sheet->setCellValue('A9', '• Format tanggal: YYYY-MM-DD (contoh: 2026-12-31) atau kosongkan');
        $sheet->setCellValue('A10', '• Jika Kode Obat sudah ada, data akan di-UPDATE (tidak duplikat)');
        $sheet->setCellValue('A11', '• EWS: Notifikasi warning akan muncul jika tanggal_exp < 30 hari dari sekarang');

        $sheet->getStyle('A6:G11')->getFont()->setSize(9)->setColor(
            (new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'))
        );

        return [];
    }
}
