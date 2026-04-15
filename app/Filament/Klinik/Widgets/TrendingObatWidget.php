<?php
namespace App\Filament\Klinik\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\KlinikResepObat;
use Illuminate\Support\Facades\DB;

class TrendingObatWidget extends ChartWidget
{
    protected static ?string $heading   = 'Obat Paling Sering Diresepkan (30 Hari Terakhir)';
    protected static ?int    $sort      = 4;
    protected static ?string $maxHeight = '280px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = KlinikResepObat::select(
                'obat_id',
                DB::raw('SUM(jumlah) as total')
            )
            ->whereHas('rekamMedis', fn($q) =>
                $q->where('created_at', '>=', now()->subDays(30))
            )
            ->groupBy('obat_id')
            ->orderByDesc('total')
            ->take(8)
            ->with('obat')
            ->get();

        return [
            'datasets' => [[
                'label'           => 'Total Diresepkan',
                'data'            => $data->pluck('total')->toArray(),
                'backgroundColor' => array_fill(0, $data->count(), '#0F766E'),
                'borderRadius'    => 6,
            ]],
            'labels' => $data->map(fn($d) =>
                \Str::limit($d->obat->nama_barang ?? '?', 22)
            )->toArray(),
        ];
    }

    protected function getType(): string { return 'bar'; }
    protected function getOptions(): array
    {
        return [
            'plugins'   => ['legend' => ['display' => false]],
            'scales'    => ['y' => ['beginAtZero' => true]],
            'indexAxis' => 'y',
        ];
    }
}
