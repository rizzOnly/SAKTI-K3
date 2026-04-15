<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\KlinikRekamMedis;

class LaporanRekamMedisExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles
{
    private static int $no = 0;

    public function __construct(
        private string $dari,
        private string $sampai
    ) {}

    public function title(): string { return 'Rekam Medis Klinik'; }

    public function query()
    {
        // TAMBAHAN: eager load 'appointment' untuk ambil keluhan awal
        return KlinikRekamMedis::with(['pasien', 'dokter', 'resepObat.obat', 'appointment'])
            ->whereBetween('created_at', [$this->dari, $this->sampai . ' 23:59:59'])
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'No', 'Tanggal', 'NIP', 'Nama Pasien', 'Bidang',
            'Jenis Kelamin', 'Dokter',
            'Keluhan Awal',  // TAMBAHAN
            'Diagnosa', 'Tindakan',
            'Resep Obat', 'Catatan',
        ];
    }

    public function map($r): array
    {
        self::$no++;

        $resepList = $r->resepObat->map(fn($res) =>
            ($res->obat->nama_barang ?? '?') . ' ' . $res->jumlah . ' ' .
            ($res->obat->satuan ?? '') .
            ($res->aturan_pakai ? ' (' . $res->aturan_pakai . ')' : '')
        )->implode('; ');

        return [
            self::$no,
            $r->created_at->format('d/m/Y H:i'),
            $r->pasien->nip ?? '-',
            $r->pasien->name,
            $r->pasien->bidang ?? '-',
            $r->pasien->jenis_kelamin === 'L' ? 'Laki-laki' :
                ($r->pasien->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
            $r->dokter->name,
            $r->appointment->keluhan ?? '-',  // TAMBAHAN: keluhan awal dari appointment
            $r->diagnosa,
            $r->tindakan ?? '-',
            $resepList ?: '-',
            $r->catatan ?? '-',
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
