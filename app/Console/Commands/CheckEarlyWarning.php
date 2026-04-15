<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{ApdItem, KlinikObat, KlinikAlat, KlinikAppointment, User};
use Illuminate\Support\Facades\Notification;
use App\Notifications\EwsNotification;
use App\Notifications\ReminderAppointmentNotification;
use App\Services\WhatsAppService;

class CheckEarlyWarning extends Command
{
    protected $signature   = 'k3:check-ews';
    protected $description = 'Cek Early Warning System harian (stok, expired, kalibrasi)';

    public function handle(): void
    {
        $adminK3 = User::role('admin_k3')->get();
        $dokter  = User::role('dokter')->get();

        // ==========================================
        // 1. Stok APD Kritis (WA ke Admin K3)
        // ==========================================
        $stokKritis = ApdItem::stokKritis()->get();
        if ($stokKritis->isNotEmpty()) {
            $itemList = $stokKritis->pluck('nama_barang')->implode(', ');
            Notification::send($adminK3, new EwsNotification('Stok APD Kritis', $itemList));

            foreach ($adminK3 as $admin) {
                if ($admin->no_hp) {
                    WhatsAppService::send($admin->no_hp,
                        "⚠️ *EWS K3 – Stok APD Kritis*\n" .
                        "━━━━━━━━━━━━━━━━━━\n" .
                        "Item: {$itemList}\n" .
                        "Segera lakukan pengadaan.\n" .
                        url('/admin')
                    );
                }
            }
            $this->line("⚠️  Stok kritis: {$stokKritis->count()} item");
        }

        // ==========================================
        // 2. APD Akan Expired (WA ke Admin K3)
        // ==========================================
        $apdExpired = ApdItem::akanExpired(30)->get();
        if ($apdExpired->isNotEmpty()) {
            $itemList = $apdExpired->pluck('nama_barang')->implode(', ');
            Notification::send($adminK3, new EwsNotification('APD Akan Expired (30 hari)', $itemList));

            foreach ($adminK3 as $admin) {
                if ($admin->no_hp) {
                    WhatsAppService::send($admin->no_hp,
                        "⚠️ *EWS K3 – APD Akan Expired*\n" .
                        "━━━━━━━━━━━━━━━━━━\n" .
                        "Item: {$itemList}\n" .
                        "Silakan cek gudang K3.\n" .
                        url('/admin')
                    );
                }
            }
            $this->line("⚠️  APD akan expired: {$apdExpired->count()} item");
        }

        // ==========================================
        // 3. Obat Akan Expired (WA ke Dokter)
        // ==========================================
        $obatExpired = KlinikObat::where('tanggal_exp', '<=', now()->addDays(30))->get();
        if ($obatExpired->isNotEmpty()) {
            $itemList = $obatExpired->pluck('nama_barang')->implode(', ');
            Notification::send($dokter, new EwsNotification('Obat Akan Expired (30 hari)', $itemList));

            foreach ($dokter as $dok) {
                if ($dok->no_hp) {
                    WhatsAppService::send($dok->no_hp,
                        "⚠️ *EWS Klinik – Obat Expired*\n" .
                        "━━━━━━━━━━━━━━━━━━\n" .
                        "Item: {$itemList}\n" .
                        "Silakan cek stok obat klinik.\n" .
                        url('/klinik')
                    );
                }
            }
            $this->line("⚠️  Obat akan expired: {$obatExpired->count()} item");
        }

        // ==========================================
        // 4. Kalibrasi Alat (WA ke Dokter)
        // ==========================================
        $kalibrasi = KlinikAlat::where('tgl_kalibrasi_ulang', '<=', now()->addDays(7))->get();
        if ($kalibrasi->isNotEmpty()) {
            $itemList = $kalibrasi->pluck('nama_barang')->implode(', ');
            Notification::send($dokter, new EwsNotification('Alat Perlu Kalibrasi (< 7 hari)', $itemList));

            foreach ($dokter as $dok) {
                if ($dok->no_hp) {
                    WhatsAppService::send($dok->no_hp,
                        "⚠️ *EWS Klinik – Kalibrasi Alat*\n" .
                        "━━━━━━━━━━━━━━━━━━\n" .
                        "Alat: {$itemList}\n" .
                        "Jadwal kalibrasi sudah dekat.\n" .
                        url('/klinik')
                    );
                }
            }
            $this->line("⚠️  Alat perlu kalibrasi: {$kalibrasi->count()} item");
        }

        // ==========================================
        // 5. Reminder Appointment H-1 (WA ke Pasien)
        // ==========================================
        $besok = KlinikAppointment::where('tanggal', now()->addDay()->toDateString())
            ->where('status', 'scheduled')
            ->whereNull('reminder_sent_at')
            ->with(['pasien', 'dokter']) // Pastikan relasi dokter juga dipanggil
            ->get();

        foreach ($besok as $appointment) {
            // Notifikasi via Email
            $appointment->pasien->notify(new ReminderAppointmentNotification($appointment));

            // Notifikasi via WhatsApp
            if ($appointment->pasien->no_hp) {
                WhatsAppService::send($appointment->pasien->no_hp,
                    "⏰ *Reminder Appointment Besok*\n" .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    "👤 Nama: {$appointment->pasien->name}\n" .
                    "👨‍⚕️ Dokter: " . ($appointment->dokter->name ?? 'Dokter Klinik') . "\n" .
                    "📅 Besok: " . $appointment->tanggal->format('d/m/Y') . "\n" .
                    "🕐 Jam: {$appointment->jam_slot}\n" .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    "Siapkan kartu pegawai dan hadir tepat waktu."
                );
            }

            $appointment->update(['reminder_sent_at' => now()]);
        }

        if ($besok->isNotEmpty()) {
            $this->line("📅 Reminder terkirim: {$besok->count()} appointment besok");
        }

        $this->info('✅ EWS check selesai: ' . now());
    }
}
