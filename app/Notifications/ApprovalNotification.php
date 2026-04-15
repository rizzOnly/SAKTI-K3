<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApprovalNotification extends Notification
{
    public function __construct(
        private string $subject,
        private string $body,
        private string $actionUrl = ''
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->subject)
            ->greeting('Halo, ' . $notifiable->name)
            ->line($this->body);

        if ($this->actionUrl) {
            $mail->action('Lihat Detail', $this->actionUrl);
        }

        return $mail->line('Terima kasih.');
    }

    public function toDatabase($notifiable): array
    {
        return [
            'subject'    => $this->subject,
            'body'       => $this->body,
            'action_url' => $this->actionUrl,
        ];
    }
}
