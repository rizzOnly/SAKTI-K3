<?php
namespace App\Filament\Klinik\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\KlinikRekamMedis;
use Illuminate\Support\Facades\DB;

class KunjunganByBidangWidget extends ChartWidget
{
    protected static ?string $heading   = 'Kunjungan Klinik per Bidang (30 Hari Terakhir)';
    protected static ?int    $sort      = 5;
    protected static ?string $maxHeight = '260px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = KlinikRekamMedis::select(
                'users.bidang',
                DB::raw('COUNT(*) as total')
            )
            ->join('users', 'users.id', '=', 'klinik_rekam_medis.user_id')
            ->where('klinik_rekam_medis.created_at', '>=', now()->subDays(30))
            ->whereNotNull('users.bidang')
            ->groupBy('users.bidang')
            ->orderByDesc('total')
            ->get();

        $colors = ['#0F766E','#003D7C','#D97706','#DC2626','#7C3AED','#059669'];

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
        return ['plugins' => ['legend' => ['position' => 'right']]];
    }
}
