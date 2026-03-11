<?php

namespace App\Mail;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RendezVousNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rendezVous;
    public $portalLink;

    public function __construct(RendezVous $rendezVous, string $portalLink = '')
    {
        $this->rendezVous = $rendezVous;
        $this->portalLink = $portalLink;
    }

    public function build()
    {
        return $this->subject('Confirmation de rendez-vous - ' . $this->rendezVous->titre)
                    ->view('emails.rendez-vous-notification')
                    ->with([
                        'rendezVous' => $this->rendezVous,
                        'contact' => $this->rendezVous->contact,
                        'activite' => $this->rendezVous->activite,
                        'portalLink' => $this->portalLink,
                    ]);
    }
}
