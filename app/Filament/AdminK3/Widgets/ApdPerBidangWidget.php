<?php

namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PengambilanHeader;
use App\Models\PeminjamanHeader;

class ApdPerBidangWidget extends ChartWidget
{
    protected static ?int    $sort      = 7;
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'this_month';

    // FIX: Diubah menjadi public
    public function getHeading(): string
    {
        return 'Pengambilan & Peminjaman APD per Bidang';
    }

    protected function getFilters(): ?array
    {
        return [
            'this_month' => 'Bulan Ini',
            'last_month' => 'Bulan Lalu',
            '3_months'   => '3 Bulan Terakhir',
            'this_year'  => 'Tahun Ini',
            'all'        => 'Semua Waktu',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $queryAmbil = PengambilanHeader::with('user')->whereIn('status', ['approved', 'returned']);
        $queryPinjam = PeminjamanHeader::with('user')->whereIn('status', ['approved', 'returned']);

        if ($activeFilter === 'this_month') {
            $queryAmbil->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $queryPinjam->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        } elseif ($activeFilter === 'last_month') {
            $queryAmbil->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
            $queryPinjam->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
        } elseif ($activeFilter === '3_months') {
            $queryAmbil->where('created_at', '>=', now()->subMonths(3)->startOfMonth());
            $queryPinjam->where('created_at', '>=', now()->subMonths(3)->startOfMonth());
        } elseif ($activeFilter === 'this_year') {
            $queryAmbil->whereYear('created_at', now()->year);
            $queryPinjam->whereYear('created_at', now()->year);
        }

        $allBidang = $queryAmbil->get()->merge($queryPinjam->get())
            ->pluck('user.bidang')
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(8);

        $colors = ['#003D7C','#0F766E','#D97706','#DC2626','#7C3AED','#DB2777','#059669','#2563EB'];

        return [
            'datasets' => [[
                'data'            => $allBidang->values()->toArray(),
                'backgroundColor' => array_slice($colors, 0, max($allBidang->count(), 1)),
            ]],
            'labels' => $allBidang->keys()->toArray(),
        ];
    }

    protected function getType(): string { return 'doughnut'; }
    protected function getOptions(): array { return ['plugins' => ['legend' => ['position' => 'right']]]; }
}
