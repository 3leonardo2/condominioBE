<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitacionResidente extends Mailable {
    use Queueable, SerializesModels;

    public string $nombreResidente;
    public string $linkActivacion;

    public function __construct(string $nombreResidente, string $linkActivacion) {
        $this->nombreResidente = $nombreResidente;
        $this->linkActivacion = $linkActivacion;
    }

    public function envelope(): Envelope {
        return new Envelope(subject: 'Invitación a Happy Community');
    }

    public function content(): Content {
        return new Content(view: 'invitacion_residente');
    }
}