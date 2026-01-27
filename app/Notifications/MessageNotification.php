<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageNotification extends Notification
{
    use Queueable;

    protected $messageDetails;

    /**
     * Create a new notification instance.
     */
    public function __construct($messageDetails)
    {
        //
        $this->messageDetails = $messageDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Nouveau Message de Administrateur')
            ->greeting('Bonjour,')
            ->line('Vous avez un nouveau message.')
            ->line('Sujet: ' . $this->messageDetails['sujet'])
            ->line('Message: ' . $this->messageDetails['message'])
            // ->line('Document joints: ' . $this->messageDetails['lien_document'])
            ->action('Voir le Message', url('/mutualiste/message/' . $this->messageDetails['id']))
            ->line('Merci pour votre attention!');
        // Attacher le document si disponible
        // if (isset($this->messageDetails['lien_document'])) {
        //     $mailMessage->attach(storage_path('app/' . $this->messageDetails['lien_document']));
        // }
        return $mailMessage;
        // }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message_id' => $this->messageDetails['id'],
            'sujet' => $this->messageDetails['sujet'],
            'message' => $this->messageDetails['message'],
        ];
    }
}
