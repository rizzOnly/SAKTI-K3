<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeminjamanHeader;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Notifications\PeminjamanReminderNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class CheckPeminjamanReminder extends Command
{
    protected $signature   = 'k3:check-peminjaman-reminder';
    protected $description = 'Cek dan kirim notifikasi pengingat peminjaman APD';

    public function handle(): void
    {
        $today    = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        // ─── 1. PENGINGAT H-1 ────────────────────────────────
        $besokKembali = PeminjamanHeader::where('status', 'approved')
            ->whereDate('tanggal_kembali_rencana', $tomorrow)
            ->whereNull('reminder_h1_sent_at')  // belum dikirim
            ->with(['user', 'details.apdItem'])
            ->get();

        foreach ($besokKembali as $peminjaman) {
            $this->kirimNotif($peminjaman, 'reminder_h1');
        }
        $this->info("📅 Reminder H-1: {$besokKembali->count()} peminjaman");


        // ─── 2. JATUH TEMPO HARI INI ─────────────────────────
        $hariIniKembali = PeminjamanHeader::where('status', 'approved')
            ->whereDate('tanggal_kembali_rencana', $today)
            ->whereNull('reminder_jatuh_tempo_sent_at')  // belum dikirim
            ->with(['user', 'details.apdItem'])
            ->get();

        foreach ($hariIniKembali as $peminjaman) {
            $this->kirimNotif($peminjaman, 'jatuh_tempo');
        }
        $this->info("⏰ Jatuh Tempo Hari Ini: {$hariIniKembali->count()} peminjaman");


        // ─── 3. SUDAH TERLAMBAT ───────────────────────────────
        $terlambat = PeminjamanHeader::where('status', 'approved')
            ->whereDate('tanggal_kembali_rencana', '<', $today)
            ->whereNull('reminder_terlambat_last_sent_at')  // belum dikirim
            ->with(['user', 'details.apdItem'])
            ->get();

        foreach ($terlambat as $peminjaman) {
            $this->kirimNotif($peminjaman, 'terlambat');
        }
        $this->info("🚨 Terlambat: {$terlambat->count()} peminjaman");

        $this->info('✅ Selesai: ' . now());
    }

    private function kirimNotif(PeminjamanHeader $peminjaman, string $tipe): void
    {
        $user        = $peminjaman->user;
        $itemList    = $peminjaman->details
            ->map(fn($d) => "• {$d->apdItem->nama_barang} ({$d->jumlah} {$d->apdItem->satuan})")
            ->implode("\n");
        $tglKembali  = $peminjaman->tanggal_kembali_rencana->format('d/m/Y');
        $noTransaksi = $peminjaman->nomor_transaksi;
        $hariTerlambat = now()->diffInDays($peminjaman->tanggal_kembali_rencana, false);
        $lamaStr = abs((int) $hariTerlambat) . " hari";
        // diffInDays false = negatif jika sudah lewat

        // ── Susun pesan sesuai tipe ──────────────────────────
        [$pesanPeminjam, $pesanAdmin] = match($tipe) {

            'reminder_h1' => [
                // Pesan ke peminjam
                "⏰ *Pengingat Pengembalian APD*\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "Halo *{$user->name}*,\n" .
                "APD yang kamu pinjam harus dikembalikan *besok* ({$tglKembali}).\n\n" .
                "📋 No. Transaksi: {$noTransaksi}\n" .
                "📦 Item APD:\n{$itemList}\n\n" .
                "Harap kembalikan tepat waktu ke pos K3.\n" .
                "Terima kasih 🙏",

                // Pesan ke admin
                "📅 *Reminder Pengembalian APD H-1*\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "Peminjam: {$user->name} ({$user->nid})\n" .
                "No: {$noTransaksi}\n" .
                "Rencana Kembali: *Besok* ({$tglKembali})\n" .
                "Item:\n{$itemList}",
            ],

            'jatuh_tempo' => [
                "🔔 *APD Jatuh Tempo Hari Ini!*\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "Halo *{$user->name}*,\n" .
                "Hari ini ({$tglKembali}) adalah batas pengembalian APD kamu.\n\n" .
                "📋 No. Transaksi: {$noTransaksi}\n" .
                "📦 Item APD:\n{$itemList}\n\n" .
                "⚠️ Segera kembalikan ke pos K3 sebelum tutup!\n" .
                "Hubungi Admin K3 jika ada kendala.",

                "⏰ *APD Jatuh Tempo Hari Ini*\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "Peminjam: {$user->name} ({$user->nid})\n" .
                "No: {$noTransaksi}\n" .
                "Batas Kembali: *Hari ini* ({$tglKembali})\n" .
                "Item:\n{$itemList}",
            ],

            'terlambat' => [
                "🚨 *APD TERLAMBAT DIKEMBALIKAN!*\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "Halo *{$user->name}*,\n" .
                "APD kamu sudah *{$lamaStr}* melewati batas pengembalian!\n\n" .
                "📋 No. Transaksi: {$noTransaksi}\n" .
                "📅 Seharusnya kembali: {$tglKembali}\n" .
                "📦 Item APD:\n{$itemList}\n\n" .
                "⛔ Segera kembalikan APD atau hubungi Admin K3!\n" .
                "Keterlambatan dapat dikenakan sanksi.",

                "🚨 *APD TERLAMBAT - Perlu Tindakan!*\n" .
                "━━━━━━━━━━━━━━━━━━\n" .
                "Peminjam: {$user->name} ({$user->nid})\n" .
                "No: {$noTransaksi}\n" .
                "Seharusnya kembali: {$tglKembali}\n" .
                "⏱️ Terlambat: *{$lamaStr}*\n" .
                "Item:\n{$itemList}",
            ],

            default => ['', ''],
        };

        // ── Kirim WA ke peminjam ─────────────────────────────
        if ($user->no_hp && $pesanPeminjam) {
            WhatsAppService::send($user->no_hp, $pesanPeminjam);
        }

        // ── Kirim email ke peminjam ──────────────────────────
        if ($user->email) {
            $user->notify(new PeminjamanReminderNotification(
                $peminjaman, $tipe, $itemList
            ));
        }

        // ── Kirim notif ke semua Admin K3 ───────────────────
        $admins = User::role('admin_k3')->get();
        foreach ($admins as $admin) {
            if ($admin->no_hp && $pesanAdmin) {
                WhatsAppService::send($admin->no_hp, $pesanAdmin);
            }
        }

        // ── Update tracking timestamp ───────────────────────
        $this->updateTracking($peminjaman, $tipe);
    }

    private function updateTracking(PeminjamanHeader $peminjaman, string $tipe): void
    {
        $col = match($tipe) {
            'reminder_h1'       => 'reminder_h1_sent_at',
            'jatuh_tempo'       => 'reminder_jatuh_tempo_sent_at',
            'terlambat'         => 'reminder_terlambat_last_sent_at',
            default             => null,
        };

        if ($col) {
            $peminjaman->$col = now();
            $peminjaman->save();
        }
    }
}
