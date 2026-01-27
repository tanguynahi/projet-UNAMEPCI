<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MutualisteNotification extends Notification
{
    use Queueable;
    // protected $comment;
    protected $mutualiste;
    // protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($mutualiste)
    {
        //
        $this->mutualiste = $mutualiste;

        // $this->message = $message;
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
            ->subject('Paiement de droite d adhesion sur votre compte MUTUALPAY')
            ->greeting('Salut ' . $this->mutualiste->prenom . ' ' . $this->mutualiste->nom . '.')
            ->line("C'est officiel, votre paiement d'adhésion a été confirmé ! 🎉 Bienvenue dans la communauté du Fond de Prevoyance Militaire !")
            ->line("Nous sommes super excités de vous avoir avec nous. Votre adhésion vous ouvre les portes à un monde de nouvelles opportunités, d'événements passionnants et de nombreuses ressources. Prenez le temps d'explorer ce qui vous attend et faites-en le maximum !")
            ->line("Votre soutien signifie beaucoup pour nous, et nous sommes impatients de voir tout ce que vous accomplirez avec nous. N'hésitez pas à nous contacter si vous avez des questions ou si vous avez besoin d'assistance. Nous sommes là pour vous à chaque étape du chemin.")
            ->line("Bienvenue à bord, et profitons de cette aventure ensemble !")
            ->line('Cordialement,')
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
    public function toDatabase($notifiable)
    {
        // return [
        //     'message' => $this->message,
        //     // 'message' => 'vous avez une cotisation en attente',
        //     'user_id' => $notifiable->id,
        //     'timestamp' => now()
        // ];
    }
    public function toBroadcast($notifiable)
    {
        // return new BroadcastMessage([
        //     'message' => $this->message,
        //     'user_id' => $notifiable->id,
        //     'timestamp' => now()
        // ]);
    }
}
