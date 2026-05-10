<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// EWS harian jam 07:00
Schedule::command('k3:check-ews')->dailyAt('08:00');

// Reminder peminjaman APD — 3x sehari
// Pagi: cek H-1 dan terlambat
Schedule::command('k3:check-peminjaman-reminder')->dailyAt('08:00');
// Siang: repeat reminder untuk yang terlambat
Schedule::command('k3:check-peminjaman-reminder')->dailyAt('13:00');
// Sore: last call jatuh tempo hari ini
Schedule::command('k3:check-peminjaman-reminder')->dailyAt('15:30');
