<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1e293b; }
        .page { padding: 24px 28px; }
        .report-header { display: flex; align-items: center; justify-content: space-between; padding-bottom: 14px; border-bottom: 3px solid #0d9488; margin-bottom: 18px; }
        .report-title { font-size: 16px; font-weight: 900; color: #0d9488; text-transform: uppercase; }
        .report-date { font-size: 9px; color: #94a3b8; margin-top: 3px; }
        .period-box { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 8px; padding: 8px 16px; margin-bottom: 16px; font-size: 9px; color: #0f766e; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0d9488; }
        thead th { color: #fff; font-size: 8.5px; font-weight: 700; padding: 8px 6px; text-align: left; }
        tbody tr:nth-child(even) { background: #f0fdfa; }
        tbody td { padding: 7px 6px; font-size: 9px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .footer { margin-top: 16px; padding-top: 10px; border-top: 1px solid #e2e8f0; font-size: 8px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>
<div class="page">
    <div class="report-header">
        <div>
            <div style="font-size:13px;font-weight:700;color:#0d9488">PT PLN Nusantara Power</div>
            <div style="font-size:9px;color:#64748b">Unit Pembangkitan Sengkang – Klinik</div>
        </div>
        <div style="text-align:right">
            <div class="report-title">Laporan Kunjungan Klinik</div>
            <div class="report-date">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
        </div>
    </div>

    <div class="period-box">
        Periode: {{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}
        &nbsp;|&nbsp; Total Kunjungan: <strong>{{ $rekamMedis->count() }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th style="width:70px">NIP</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th style="width:60px;text-align:center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekamMedis as $i => $rm)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $rm->pasien->nip }}</td>
                <td style="font-weight:600">{{ $rm->pasien->name }}</td>
                <td>{{ $rm->dokter->name }}</td>
                <td>{{ $rm->diagnosa }}</td>
                <td>{{ $rm->tindakan ?? '-' }}</td>
                <td style="text-align:center">{{ $rm->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Sistem K3 PLN Sengkang &nbsp;|&nbsp; Halaman 1 dari 1</div>
</div>
</body>
</html>
