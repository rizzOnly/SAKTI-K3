<?php
namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PeminjamanDetail;
use Illuminate\Support\Facades\DB;

class TrendingPeminjamanWidget extends ChartWidget
{
    protected static ?string $heading   = 'APD Paling Sering Dipinjam (30 Hari Terakhir)';
    protected static ?int    $sort      = 5;
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = PeminjamanDetail::select(
                'apd_item_id',
                DB::raw('SUM(jumlah) as total')
            )
            ->whereHas('peminjamanHeader', fn($q) =>
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
                'label'           => 'Jumlah Dipinjam',
                'data'            => $data->pluck('total')->toArray(),
                'backgroundColor' => array_fill(0, $data->count(), '#0F766E'),
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
            'scales'   => ['y' => ['beginAtZero' => true]],
            'indexAxis' => 'y',
        ];
    }
}
