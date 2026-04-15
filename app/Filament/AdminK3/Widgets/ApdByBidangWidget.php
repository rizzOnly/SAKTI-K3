<?php
namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PengambilanHeader;
use Illuminate\Support\Facades\DB;

class ApdByBidangWidget extends ChartWidget
{
    protected static ?string $heading   = 'Pengambilan APD per Bidang (30 Hari Terakhir)';
    protected static ?int    $sort      = 6;
    protected static ?string $maxHeight = '260px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = PengambilanHeader::select(
                'users.bidang',
                DB::raw('COUNT(*) as total')
            )
            ->join('users', 'users.id', '=', 'pengambilan_headers.user_id')
            ->where('pengambilan_headers.status', 'approved')
            ->where('pengambilan_headers.approved_at', '>=', now()->subDays(30))
            ->whereNotNull('users.bidang')
            ->groupBy('users.bidang')
            ->orderByDesc('total')
            ->get();

        $colors = [
            '#003D7C','#0F766E','#D97706','#DC2626',
            '#7C3AED','#DB2777','#059669','#2563EB',
        ];

        return [
            'datasets' => [[
                'data'            => $data->pluck('total')->toArray(),
                'backgroundColor' => array_slice($colors, 0, $data->count()),
            ]],
            'labels' => $data->pluck('bidang')->toArray(),
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
