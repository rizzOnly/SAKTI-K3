<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\FitToWork;

class LaporanFitToWorkExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    private static int $no = 0;

    public function __construct(
        private string $dari,
        private string $sampai
    ) {}

    public function title(): string { return 'Fit to Work'; }

    public function query()
    {
        return FitToWork::whereBetween('created_at', [$this->dari, $this->sampai . ' 23:59:59'])
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal Daftar', 'Nama', 'Tipe', 'Perusahaan',
            'Jenis Kelamin', 'Pekerjaan Risiko Tinggi',
            'Tanggal Mulai', 'Tanggal Selesai',
            'No. WhatsApp', 'Status', 'Hasil', 'Catatan Dokter',
        ];
    }

    public function map($f): array
    {
        self::$no++;

        return [
            self::$no,
            $f->created_at->format('d/m/Y H:i'),
            $f->nama,
            ucfirst($f->tipe),
            $f->nama_perusahaan ?? ($f->tipe === 'vendor' ? '-' : 'Internal'),
            $f->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $f->nama_pekerjaan ?? '-',
            $f->tanggal_mulai?->format('d/m/Y') ?? '-',
            $f->tanggal_selesai?->format('d/m/Y') ?? '-',
            $f->no_wa ?? '-',
            match($f->status) {
                'fit'       => 'Fit to Work',
                'tidak_fit' => 'Tidak Fit',
                default     => 'Menunggu Pemeriksaan',
            },
            match($f->status) {
                'fit'       => '✅ Fit',
                'tidak_fit' => '❌ Tidak Fit',
                default     => '-',
            },
            $f->catatan_dokter ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0F766E']],
            ],
        ];
    }
}
