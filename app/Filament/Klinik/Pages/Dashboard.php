<?php
namespace App\Filament\Klinik\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Klinik\Widgets\StatsOverviewKlinik;
use App\Filament\Klinik\Widgets\TrendingObatWidget;
use App\Filament\Klinik\Widgets\KunjunganByBidangWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            StatsOverviewKlinik::class,
            TrendingObatWidget::class,
            KunjunganByBidangWidget::class,
        ];
    }
}
