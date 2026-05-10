<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\PeminjamanHeader;

class PeminjamanReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        private PeminjamanHeader $peminjaman,
        private string $tipe,        // reminder_h1 | jatuh_tempo | terlambat
        private string $itemList,
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tglKembali = $this->peminjaman->tanggal_kembali_rencana->format('d M Y');
        $hariTerlambat = abs((int) now()->diffInDays(
            $this->peminjaman->tanggal_kembali_rencana, false
        ));

        [$subjek, $heading, $body, $warna] = match($this->tipe) {
            'reminder_h1' => [
                "Pengingat: APD harus dikembalikan besok",
                "⏰ Pengingat Pengembalian APD",
                "APD yang Anda pinjam harus dikembalikan *besok* ({$tglKembali}). Harap kembalikan tepat waktu ke pos K3.",
                'info',
            ],
            'jatuh_tempo' => [
                "APD Jatuh Tempo Hari Ini — " . $this->peminjaman->nomor_transaksi,
                "⏰ APD Jatuh Tempo Hari Ini!",
                "Batas pengembalian APD Anda adalah *hari ini* ({$tglKembali}). Segera kembalikan sebelum tutup.",
                'warning',
            ],
            'terlambat' => [
                "🚨 APD Terlambat {$hariTerlambat} Hari — " . $this->peminjaman->nomor_transaksi,
                "APD Terlambat Dikembalikan!",
                "APD Anda sudah *melewati batas pengembalian {$hariTerlambat} hari* (seharusnya {$tglKembali}). Segera kembalikan atau hubungi Admin K3.",
                'error',
            ],
            default => ['Notifikasi APD', 'Notifikasi APD', '', 'info'],
        };

        return (new MailMessage)
            ->subject($subjek)
            ->greeting("Halo, {$notifiable->name}")
            ->line($heading)
            ->line($body)
            ->line("**No. Transaksi:** {$this->peminjaman->nomor_transaksi}")
            ->line("**Item APD:**\n{$this->itemList}")
            ->action('Lihat Detail', url('/admin'))
            ->line('Hubungi Admin K3 jika ada pertanyaan.');
    }

    public function toDatabase($notifiable): array
    {
        $tglKembali = $this->peminjaman->tanggal_kembali_rencana->format('d/m/Y');
        $hariTerlambat = abs((int) now()->diffInDays(
            $this->peminjaman->tanggal_kembali_rencana, false
        ));

        $pesan = match($this->tipe) {
            'reminder_h1' => "APD harus dikembalikan besok ({$tglKembali}). No: {$this->peminjaman->nomor_transaksi}",
            'jatuh_tempo' => "APD jatuh tempo hari ini ({$tglKembali}). No: {$this->peminjaman->nomor_transaksi}",
            'terlambat'   => "APD terlambat {$hariTerlambat} hari. No: {$this->peminjaman->nomor_transaksi}",
            default       => 'Notifikasi APD',
        };

        return [
            'tipe'            => $this->tipe,
            'nomor_transaksi' => $this->peminjaman->nomor_transaksi,
            'tgl_kembali'     => $tglKembali,
            'item_list'       => $this->itemList,
            'pesan'           => $pesan,
        ];
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
