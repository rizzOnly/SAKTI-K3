<?php
namespace App\Filament\AdminK3\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\{ApdItem, PengambilanHeader, PeminjamanHeader};

class StatsOverviewK3 extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stokKritis    = ApdItem::stokKritis()->count();
        $akanExpired   = ApdItem::akanExpired(30)->count();
        $pendingAmbil  = PengambilanHeader::where('status', 'pending')->count();
        $pendingPinjam = PeminjamanHeader::where('status', 'pending')->count();

        return [
            Stat::make('Total Item APD', ApdItem::count())
                ->description('Semua jenis APD dalam gudang')
                ->color('primary')
                ->icon('heroicon-o-cube'),

            Stat::make('Stok Kritis', $stokKritis)
                ->description('Item di bawah minimum stok')
                ->color($stokKritis > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),

            Stat::make('Akan Expired', $akanExpired)
                ->description('Item expired dalam 30 hari')
                ->color($akanExpired > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clock'),

            Stat::make('Pengajuan Pending', $pendingAmbil + $pendingPinjam)
                ->description("{$pendingAmbil} ambil · {$pendingPinjam} pinjam")
                ->color('warning')
                ->icon('heroicon-o-document-check'),
        ];
    }
}
