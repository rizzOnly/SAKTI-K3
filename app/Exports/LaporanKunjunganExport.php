<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle};
use App\Models\KlinikRekamMedis;

class LaporanKunjunganExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    private int $no = 0;

    public function __construct(
        private string $dari,
        private string $sampai
    ) {}

    public function title(): string { return 'Laporan Kunjungan Klinik'; }

    public function query()
    {
        // Tambahkan "23:59:59" agar data di hari terakhir tetap ikut terhitung
        $sampaiFull = $this->sampai . ' 23:59:59';

        return KlinikRekamMedis::with(['pasien', 'dokter'])
            ->whereBetween('created_at', [$this->dari, $sampaiFull])
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return ['No', 'NIP Pasien', 'Nama Pasien', 'Dokter', 'Diagnosa', 'Tindakan', 'Tanggal'];
    }

    public function map($r): array
    {
        $this->no++;
        return [
            $this->no,
            $r->pasien->nip,
            $r->pasien->name,
            $r->dokter->name,
            $r->diagnosa,
            $r->tindakan ?? '-',
            $r->created_at->format('d/m/Y'),
        ];
    }
}
