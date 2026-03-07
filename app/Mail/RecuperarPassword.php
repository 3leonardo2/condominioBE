<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecuperarPassword extends Mailable {
    use Queueable, SerializesModels;

    public string $nombreUsuario;
    public string $linkRecuperacion;

    public function __construct(string $nombreUsuario, string $linkRecuperacion) {
        $this->nombreUsuario = $nombreUsuario;
        $this->linkRecuperacion = $linkRecuperacion;
    }

    public function envelope(): Envelope {
        return new Envelope(subject: 'Recuperar contraseña - Happy Community');
    }

    public function content(): Content {
        return new Content(view: 'recuperar_password');
    }
}