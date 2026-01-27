<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailDeValidationDeCompteMutualiste extends Notification
{
    use Queueable;

    protected $mutualiste;
    protected $LienDeValidation;

    /**
     * Create a new notification instance.
     * @param $mutualiste
     * @param $LienDeValidation
     */
    public function __construct($mutualiste, $LienDeValidation)
    {
        $this->mutualiste = $mutualiste;
        $this->LienDeValidation = $LienDeValidation;
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
        return (new MailMessage)

            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Validation de votre compte MUTUALPAY')
            ->greeting('Bonjour (M./Mme) ' . $this->mutualiste->nom . ' ' . $this->mutualiste->prenom . ',')
            ->line('Merci pour la première étape de votre inscription sur MUTUALPAY. Veuillez cliquer sur le boutton ci-dessous pour finaliser votre inscription et valider votre compte.')
            ->action('Valider le compte', $this->LienDeValidation)
            ->line('NB: Mail générer automatiquement ne pas y répondre!')
            ->line('Merci d’utiliser notre plateforme!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
