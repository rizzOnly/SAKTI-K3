<?php
namespace App\Filament\Klinik\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\{KlinikAppointment, KlinikObat, KlinikAlat};

class StatsOverviewKlinik extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $antrean = KlinikAppointment::where('tanggal', today())
            ->where('status', 'scheduled')
            ->count();

        $obatExpired = KlinikObat::where('tanggal_exp', '<=', now()->addDays(30))->count();
        $kalibrasiWarning = KlinikAlat::where('tgl_kalibrasi_ulang', '<=', now()->addDays(7))->count();

        return [
            Stat::make('Antrean Hari Ini', $antrean)
                ->description(today()->translatedFormat('l, d F Y'))
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Obat Akan Expired', $obatExpired)
                ->description('Dalam 30 hari ke depan')
                ->color($obatExpired > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-beaker'),

            Stat::make('Kalibrasi Alat', $kalibrasiWarning)
                ->description('Perlu kalibrasi ulang < 7 hari')
                ->color($kalibrasiWarning > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-wrench-screwdriver'),
        ];
    }
}
