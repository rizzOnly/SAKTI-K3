<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\KlinikAppointment;
use App\Services\WhatsAppService;

class ReminderAppointmentNotification extends Notification
{
    public function __construct(private KlinikAppointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('[Reminder] Appointment Klinik Besok')
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Mengingatkan appointment Anda besok:')
            ->line('📅 Tanggal: ' . $this->appointment->tanggal->format('d M Y'))
            ->line('🕐 Jam: ' . $this->appointment->jam_slot)
            ->line('👨‍⚕️ Dokter: ' . $this->appointment->dokter->name)
            ->action('Lihat Detail', url('/klinik'))
            ->line('Harap hadir tepat waktu. Terima kasih.');
    }
}
