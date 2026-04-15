<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
        .page { padding: 24px 28px; }
        .report-header { display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 3px solid #003D7C; margin-bottom: 18px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #003D7C; }
        thead th { color: #fff; font-size: 8.5px; font-weight: 700; padding: 8px 6px; text-align: left; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 7px 6px; font-size: 9px; border-bottom: 1px solid #f1f5f9; }
        .badge-approved { display:inline-block; background:#dcfce7; color:#166534; padding:2px 7px; border-radius:9999px; font-size:8px; font-weight:700; }
    </style>
</head>
<body>
<div class="page">
    <div class="report-header">
        <div>
            <div style="font-size:13px;font-weight:700;color:#003D7C">PT PLN Nusantara Power</div>
            <div style="font-size:9px;color:#64748b">Unit Pembangkitan Sengkang – Laporan APD</div>
        </div>
        <div style="text-align:right">
            <div style="font-size:16px;font-weight:900;color:#003D7C;text-transform:uppercase">Laporan Pengambilan APD</div>
            <div style="font-size:9px;color:#94a3b8">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
        </div>
    </div>

    <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:8px 16px;margin-bottom:16px;font-size:9px;color:#1e40af">
        Periode: {{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th style="width:100px">No. Transaksi</th>
                <th style="width:65px">NIP</th>
                <th>Nama Pegawai</th>
                <th>Item APD</th>
                <th style="width:35px;text-align:right">Jml</th>
                <th style="width:60px;text-align:center">Tgl Approved</th>
                <th>Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($headers as $header)
                @foreach($header->details as $detail)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td style="font-family:monospace;font-size:8.5px">{{ $header->nomor_transaksi }}</td>
                    <td>{{ $header->user->nip }}</td>
                    <td style="font-weight:600">{{ $header->user->name }}</td>
                    <td>{{ $detail->apdItem->nama_barang }}</td>
                    <td style="text-align:right;font-weight:700">{{ $detail->jumlah }}</td>
                    <td style="text-align:center">{{ $header->approved_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $header->approvedBy?->name ?? '-' }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:16px;padding-top:10px;border-top:1px solid #e2e8f0;font-size:8px;color:#94a3b8;text-align:right">
        Sistem K3 PLN Sengkang
    </div>
</div>
</body>
</html>
