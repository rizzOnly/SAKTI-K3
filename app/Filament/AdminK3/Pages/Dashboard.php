<?php
namespace App\Filament\AdminK3\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\AdminK3\Widgets\{StatsOverviewK3, StokKritisWidget, EwsExpiredWidget, TrendingApdWidget, TrendingPeminjamanWidget, ApdByBidangWidget};

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverviewK3::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewK3::class,
            StokKritisWidget::class,
            EwsExpiredWidget::class,
            TrendingApdWidget::class,
            TrendingPeminjamanWidget::class,
            ApdByBidangWidget::class,
        ];
    }
}
