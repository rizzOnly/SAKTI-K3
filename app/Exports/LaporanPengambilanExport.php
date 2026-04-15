<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle};
use App\Models\PengambilanHeader;

class LaporanPengambilanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    private int $no = 0;

    public function __construct(
        private ?string $dari   = null,
        private ?string $sampai = null
    ) {}

    public function title(): string { return 'Pengambilan APD'; }

    public function query()
    {
        $q = PengambilanHeader::with(['user', 'approvedBy', 'details.apdItem'])
            ->where('status', 'approved')
            ->orderByDesc('approved_at');

        if ($this->dari && $this->sampai) {
            $sampaiFull = $this->sampai . ' 23:59:59';
            $q->whereBetween('approved_at', [$this->dari, $sampaiFull]);
        }

        return $q;
    }

    public function headings(): array
    {
        return [
            'No', 'No. Transaksi', 'NIP Pegawai', 'Nama Pegawai',
            'Item APD', 'Jumlah', 'Tgl Pengajuan', 'Tgl Approved', 'Disetujui Oleh',
        ];
    }

    public function map($header): array
    {
        $rows = [];
        // Memecah 1 transaksi menjadi beberapa baris Excel sesuai jumlah barang
        foreach ($header->details as $detail) {
            $this->no++;
            $rows[] = [
                $this->no,
                $header->nomor_transaksi,
                $header->user->nip,
                $header->user->name,
                $detail->apdItem->nama_barang,
                $detail->jumlah,
                $header->tanggal_pengajuan->format('d/m/Y'),
                $header->approved_at?->format('d/m/Y') ?? '-',
                $header->approvedBy?->name ?? '-',
            ];
        }
        return $rows;
    }
}
