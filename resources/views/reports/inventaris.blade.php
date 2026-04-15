<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; background: #fff; }
        .page { padding: 24px 28px; }

        /* Header */
        .report-header { display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 3px solid #003D7C; margin-bottom: 18px; }
        .logo-block { display: flex; align-items: center; gap: 12px; }
        .logo-circle { width: 44px; height: 44px; border-radius: 50%; background: #003D7C; color: #FFC72C; font-weight: 900; font-size: 14px; display: flex; align-items: center; justify-content: center; text-align: center; line-height: 1.1; }
        .company-name { font-size: 13px; font-weight: 700; color: #003D7C; }
        .company-sub { font-size: 9px; color: #64748b; margin-top: 2px; }
        .report-title-block { text-align: right; }
        .report-title { font-size: 16px; font-weight: 900; color: #003D7C; text-transform: uppercase; letter-spacing: .5px; }
        .report-date { font-size: 9px; color: #94a3b8; margin-top: 3px; }

        /* Summary boxes */
        .summary { display: flex; gap: 10px; margin-bottom: 16px; }
        .summary-box { flex: 1; border-radius: 8px; padding: 10px 14px; }
        .summary-box.blue   { background: #eff6ff; border-left: 4px solid #003D7C; }
        .summary-box.red    { background: #fef2f2; border-left: 4px solid #dc2626; }
        .summary-box.amber  { background: #fffbeb; border-left: 4px solid #d97706; }
        .summary-box.green  { background: #f0fdf4; border-left: 4px solid #16a34a; }
        .summary-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: .4px; }
        .summary-value { font-size: 18px; font-weight: 800; margin-top: 2px; }
        .summary-box.blue   .summary-value { color: #003D7C; }
        .summary-box.red    .summary-value { color: #dc2626; }
        .summary-box.amber  .summary-value { color: #d97706; }
        .summary-box.green  .summary-value { color: #16a34a; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        thead tr { background: #003D7C; }
        thead th { color: #fff; font-size: 8.5px; font-weight: 700; padding: 8px 6px; text-align: left; text-transform: uppercase; letter-spacing: .3px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #eff6ff; }
        tbody td { padding: 7px 6px; font-size: 9px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .badge { display: inline-block; padding: 2px 7px; border-radius: 9999px; font-size: 8px; font-weight: 700; }
        .badge-baik     { background: #dcfce7; color: #166534; }
        .badge-rusak    { background: #fee2e2; color: #991b1b; }
        .badge-expired  { background: #fef3c7; color: #92400e; }
        .text-danger    { color: #dc2626; font-weight: 700; }
        .text-warning   { color: #d97706; font-weight: 700; }

        .footer { margin-top: 16px; padding-top: 10px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 8px; color: #94a3b8; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="report-header">
        <div class="logo-block">
            <div class="logo-circle">K3<br>PLN</div>
            <div>
                <div class="company-name">PT PLN Nusantara Power</div>
                <div class="company-sub">Unit Pembangkitan Sengkang – K3 & Lingkungan</div>
            </div>
        </div>
        <div class="report-title-block">
            <div class="report-title">Laporan Inventaris APD</div>
            <div class="report-date">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIT</div>
        </div>
    </div>

    {{-- Summary --}}
    @php
        $totalItem    = $items->count();
        $stokKritis   = $items->filter(fn($i) => $i->stok <= $i->min_stok)->count();
        $akanExpired  = $items->filter(fn($i) => $i->exp_date && $i->exp_date <= now()->addDays(30))->count();
        $kondisiBaik  = $items->where('kondisi', 'baik')->count();
    @endphp
    <div class="summary">
        <div class="summary-box blue">
            <div class="summary-label">Total Item APD</div>
            <div class="summary-value">{{ $totalItem }}</div>
        </div>
        <div class="summary-box red">
            <div class="summary-label">Stok Kritis</div>
            <div class="summary-value">{{ $stokKritis }}</div>
        </div>
        <div class="summary-box amber">
            <div class="summary-label">Akan Expired ≤30 hari</div>
            <div class="summary-value">{{ $akanExpired }}</div>
        </div>
        <div class="summary-box green">
            <div class="summary-label">Kondisi Baik</div>
            <div class="summary-value">{{ $kondisiBaik }}</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th style="width:28px">No</th>
                <th style="width:55px">Kode</th>
                <th>Nama Barang</th>
                <th style="width:40px">Satuan</th>
                <th style="width:50px">Kondisi</th>
                <th style="width:40px;text-align:right">Stok</th>
                <th style="width:45px;text-align:right">Min Stok</th>
                <th style="width:40px;text-align:center">C/R</th>
                <th style="width:58px;text-align:center">Exp Date</th>
                <th style="width:65px">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $item)
            @php
                $isKritis  = $item->stok <= $item->min_stok;
                $isExpired = $item->exp_date && $item->exp_date <= now()->addDays(30);
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kode_barang ?? '-' }}</td>
                <td style="font-weight:600">{{ $item->nama_barang }}</td>
                <td>{{ $item->satuan }}</td>
                <td>
                    <span class="badge badge-{{ $item->kondisi }}">{{ ucfirst($item->kondisi) }}</span>
                </td>
                <td style="text-align:right" class="{{ $isKritis ? 'text-danger' : '' }}">{{ $item->stok }}</td>
                <td style="text-align:right">{{ $item->min_stok }}</td>
                <td style="text-align:center">{{ $item->is_consumable ? 'C' : 'R' }}</td>
                <td style="text-align:center" class="{{ $isExpired ? 'text-warning' : '' }}">
                    {{ $item->exp_date?->format('d/m/Y') ?? '-' }}
                </td>
                <td>{{ $item->lokasi_gudang ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>Keterangan: C = Consumable (habis pakai) | R = Returnable (dipinjam) | <span style="color:#dc2626;font-weight:700">Merah</span> = Stok Kritis | <span style="color:#d97706;font-weight:700">Kuning</span> = Akan Expired</span>
        <span>Halaman 1 dari 1 &nbsp;|&nbsp; Sistem K3 PLN Sengkang</span>
    </div>

</div>
</body>
</html>
