<?php
namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\{PengambilanDetail};
use Illuminate\Support\Facades\DB;

class TrendingApdWidget extends ChartWidget
{
    protected static ?string $heading      = 'APD Paling Sering Diambil (30 Hari Terakhir)';
    protected static ?int    $sort         = 4;
    protected static ?string $maxHeight    = '280px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = PengambilanDetail::select(
                'apd_item_id',
                DB::raw('SUM(jumlah) as total')
            )
            ->whereHas('pengambilanHeader', fn($q) =>
                $q->where('status', 'approved')
                  ->where('approved_at', '>=', now()->subDays(30))
            )
            ->groupBy('apd_item_id')
            ->orderByDesc('total')
            ->take(8)
            ->with('apdItem')
            ->get();

        return [
            'datasets' => [[
                'label'           => 'Jumlah Diambil',
                'data'            => $data->pluck('total')->toArray(),
                'backgroundColor' => array_fill(0, $data->count(), '#003D7C'),
                'borderRadius'    => 6,
            ]],
            'labels' => $data->map(fn($d) =>
                \Str::limit($d->apdItem->nama_barang ?? '?', 20)
            )->toArray(),
        ];
    }

    protected function getType(): string { return 'bar'; }

    protected function getOptions(): array
    {
        return [
            'plugins'  => ['legend' => ['display' => false]],
            'scales'   => ['y' => ['beginAtZero' => true, 'ticks' => ['stepSize' => 1]]],
            'indexAxis' => 'y',  // horizontal bar chart
        ];
    }
}
