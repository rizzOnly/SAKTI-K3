<?php

namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PengambilanHeader;
use App\Models\PeminjamanHeader;

class ApdPerBidangWidget extends ChartWidget
{
    protected static ?string $heading   = 'Pengambilan & Peminjaman APD per Bidang (30 Hari Terakhir)';
    protected static ?int    $sort      = 7;
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $period = now()->subDays(90); // 90 hari terakhir

        // Get all approved/returned headers with user relationship within 90 days
        $pengambilanHeaders = PengambilanHeader::with('user')
            ->whereIn('status', ['approved', 'returned'])
            ->where('approved_at', '>=', $period)
            ->get();

        $peminjamanHeaders = PeminjamanHeader::with('user')
            ->whereIn('status', ['approved', 'returned'])
            ->where('approved_at', '>=', $period)
            ->get();

        // Merge and count by bidang
        $allBidang = $pengambilanHeaders->merge($peminjamanHeaders)
            ->pluck('user.bidang')
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(8);

        $colors = [
            '#003D7C','#0F766E','#D97706','#DC2626',
            '#7C3AED','#DB2777','#059669','#2563EB',
        ];

        return [
            'datasets' => [[
                'data'            => $allBidang->values()->toArray(),
                'backgroundColor' => array_slice($colors, 0, $allBidang->count()),
            ]],
            'labels' => $allBidang->keys()->toArray(),
        ];
    }

    protected function getType(): string { return 'doughnut'; }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'right'],
            ],
        ];
    }
}
