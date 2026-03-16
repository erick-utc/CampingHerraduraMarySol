<?php

namespace App\Mail;

use App\Models\Reserva;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservaEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $accion,
        public Reserva $reserva
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reserva ' . $this->accion . ' #' . $this->reserva->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservas.evento',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
