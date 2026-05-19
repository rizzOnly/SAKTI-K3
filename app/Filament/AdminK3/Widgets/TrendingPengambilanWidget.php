<?php

namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PengambilanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrendingPengambilanWidget extends ChartWidget
{
    protected static ?int    $sort      = 6;
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'this_month';

    // FIX: Diubah menjadi public
    public function getHeading(): string
    {
        return 'APD Paling Sering Diambil';
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

        $data = PengambilanDetail::select(
                'apd_item_id',
                DB::raw('SUM(jumlah) as total')
            )
            ->whereHas('pengambilanHeader', function ($q) use ($activeFilter) {
                $q->where('status', 'approved');

                if ($activeFilter === 'this_month') {
                    $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                } elseif ($activeFilter === 'last_month') {
                    $q->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);
                } elseif ($activeFilter === '3_months') {
                    $q->where('created_at', '>=', now()->subMonths(3)->startOfMonth());
                } elseif ($activeFilter === 'this_year') {
                    $q->whereYear('created_at', now()->year);
                }
            })
            ->groupBy('apd_item_id')
            ->orderByDesc('total')
            ->orderBy('apd_item_id')
            ->take(8)
            ->with('apdItem')
            ->get();

        return [
            'datasets' => [[
                'label'           => 'Jumlah Diambil',
                'data'            => $data->pluck('total')->toArray(),
                'backgroundColor' => array_fill(0, max($data->count(), 1), '#2563EB'),
                'borderRadius'    => 6,
            ]],
            'labels' => $data->map(fn($d) =>
                Str::limit($d->apdItem->nama_barang ?? 'Unknown', 20)
            )->toArray(),
        ];
    }

    protected function getType(): string { return 'bar'; }
    protected function getOptions(): array { return ['plugins' => ['legend' => ['display' => false]], 'scales' => ['y' => ['beginAtZero' => true]], 'indexAxis' => 'y']; }
}
