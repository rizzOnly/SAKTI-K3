<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles, WithColumnWidths};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{Alignment, Fill, Border};
use Illuminate\Support\Facades\DB;
use App\Models\{PengambilanDetail, PeminjamanDetail};

class RekapApdOtomatisExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle, WithStyles, WithColumnWidths
{
    private static int $no = 0;

    public function __construct(private int $bulan, private int $tahun) {}

    public function getNamaBulan(): string
    {
        return [
            1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
            5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
            9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember',
        ][$this->bulan] ?? '';
    }

    public function title(): string
    {
        return "Rekap APD {$this->getNamaBulan()} {$this->tahun}";
    }

    public function collection()
    {
        $qPengambilan = PengambilanDetail::query()
            ->select([DB::raw("'ambil' as status_transaksi"), 'users.bidang', 'apd_items.nama_barang as jenis_apd', 'pengambilan_details.jumlah', 'apd_items.satuan', DB::raw("'' as keterangan")])
            ->join('pengambilan_headers', 'pengambilan_details.pengambilan_header_id', '=', 'pengambilan_headers.id')
            ->join('users', 'pengambilan_headers.user_id', '=', 'users.id')
            ->join('apd_items', 'pengambilan_details.apd_item_id', '=', 'apd_items.id')
            ->where('pengambilan_headers.status', 'approved')
            ->whereMonth('pengambilan_headers.approved_at', $this->bulan)
            ->whereYear('pengambilan_headers.approved_at', $this->tahun);

        $qPeminjaman = PeminjamanDetail::query()
            ->select([DB::raw("'pinjam' as status_transaksi"), 'users.bidang', 'apd_items.nama_barang as jenis_apd', 'peminjaman_details.jumlah', 'apd_items.satuan', 'peminjaman_headers.kondisi_kembali as keterangan'])
            ->join('peminjaman_headers', 'peminjaman_details.peminjaman_header_id', '=', 'peminjaman_headers.id')
            ->join('users', 'peminjaman_headers.user_id', '=', 'users.id')
            ->join('apd_items', 'peminjaman_details.apd_item_id', '=', 'apd_items.id')
            ->whereIn('peminjaman_headers.status', ['approved', 'returned'])
            ->whereMonth('peminjaman_headers.approved_at', $this->bulan)
            ->whereYear('peminjaman_headers.approved_at', $this->tahun);

        return DB::table(DB::raw("({$qPengambilan->toSql()} UNION ALL {$qPeminjaman->toSql()}) as combined_table"))
            ->mergeBindings($qPengambilan->getQuery())
            ->mergeBindings($qPeminjaman->getQuery())
            ->orderBy('bidang')
            ->orderBy('jenis_apd')
            ->get();
    }

    public function headings(): array
    {
        $namaBulan = strtoupper($this->getNamaBulan());
        return [
            ["REKAPAN APD {$namaBulan} {$this->tahun}", '', '', '', '', '', '', ''],
            ['NO', 'BULAN', 'BIDANG', 'JENIS APD', 'JML', 'SATUAN', 'STATUS', 'KET'],
        ];
    }

    public function map($item): array
    {
        self::$no++;
        $ket = $item->keterangan === 'baik' ? 'Sudah Kembali' : ($item->keterangan ?? '');
        return [
            self::$no,
            $this->getNamaBulan(),
            strtoupper($item->bidang),
            $item->jenis_apd,
            $item->jumlah,
            $item->satuan,
            strtoupper($item->status_transaksi),
            $ket,
        ];
    }

    public function columnWidths(): array { return ['A'=>6,'B'=>14,'C'=>20,'D'=>35,'E'=>8,'F'=>10,'G'=>12,'H'=>18]; }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 13], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $sheet->getStyle('A2:H2')->applyFromArray(['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '003D7C']], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        return [];
    }
}
