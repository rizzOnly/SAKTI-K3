<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Services\WhatsAppService;

class EwsNotification extends Notification
{
    public function __construct(
        private string $title,
        private string $items
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[EWS K3] ' . $this->title)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Peringatan Early Warning System:')
            ->line('**' . $this->title . '**')
            ->line('Item terdampak: ' . $this->items)
            ->action('Buka Dashboard K3', url('/admin'))
            ->line('Segera lakukan tindak lanjut.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->title,
            'items' => $this->items,
        ];
    }

    // Opsional: kirim juga via WhatsApp
    public function toWhatsApp($notifiable): void
    {
        if ($notifiable->no_hp) {
            $pesan = "⚠️ *EWS K3 - {$this->title}*\n"
                   . "Item: {$this->items}\n"
                   . "Segera cek dashboard: " . url('/admin');

            WhatsAppService::send($notifiable->no_hp, $pesan);
        }
    }
}
