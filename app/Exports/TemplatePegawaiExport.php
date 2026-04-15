<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    FromArray, WithHeadings, WithStyles,
    ShouldAutoSize, WithColumnWidths, WithTitle
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplatePegawaiExport implements
    FromArray, WithHeadings, WithStyles,
    ShouldAutoSize, WithColumnWidths, WithTitle
{
    public function title(): string { return 'Data Pegawai'; }

    public function array(): array
    {
        // Baris contoh
        return [
            ['1990001003', 'Budi Santoso',    'Produksi',     'budi@pln.com',  '081234567892'],
            ['1990001004', 'Siti Rahayu',     'Pemeliharaan', '',               '081234567893'],
            ['1990001005', 'Ahmad Fauzi',     'Operasi',      'ahmad@pln.com', ''],
        ];
    }

    public function headings(): array
    {
        return ['nip', 'nama', 'bidang', 'email', 'no_wa'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,  // nip
            'B' => 28,  // nama
            'C' => 20,  // bidang
            'D' => 32,  // email
            'E' => 18,  // no_wa
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row styling
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '003D7C']],
        ]);

        // Row contoh styling (abu-abu muda)
        $sheet->getStyle('A2:E4')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            'font' => ['color' => ['rgb' => '64748B'], 'italic' => true],
        ]);

        // Catatan di baris ke-6
        $sheet->setCellValue('A6', '📌 PETUNJUK:');
        $sheet->setCellValue('A7', '• Hapus baris contoh (baris 2-4) sebelum import');
        $sheet->setCellValue('A8', '• Kolom wajib: nip, nama');
        $sheet->setCellValue('A9', '• Kolom opsional: bidang, email, no_wa');
        $sheet->setCellValue('A10', '• Nomor WA format: 08xx atau 628xx');
        $sheet->setCellValue('A11', '• Jika NIP sudah ada, data akan di-UPDATE (tidak duplikat)');
        $sheet->setCellValue('A12', '• Password default: password123 (minta pegawai ganti setelah login)');

        $sheet->getStyle('A6:E12')->getFont()->setSize(9)->setColor(
            (new \PhpOffice\PhpSpreadsheet\Style\Color('64748B'))
        );

        return [];
    }
}
