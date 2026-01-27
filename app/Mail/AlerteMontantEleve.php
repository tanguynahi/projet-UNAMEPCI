<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class AlerteMontantEleve extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $montant_voulue;
    public function __construct($montant_voulue)
    {
        //
        $this->montant_voulue = $montant_voulue;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */

    public function build(){
        return $this
        // ->from(env('MAIL_FROM_NAME'))
                    ->subject('Alerte: FPM Montant élevé saisi')
                    ->view('home.admin.alerte_montant')
                    ->with([
                        'montant_voulue' => $this->montant_voulue,
    ]);
    }
}
