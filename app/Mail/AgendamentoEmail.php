<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AgendamentoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $agendamento;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $agendamento)
    {
        $this->user = $user;
        $this->agendamento = $agendamento;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Agendamento Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.agendamento',
            with: [
                'user' => $this->user,
                'agendamento' => $this->agendamento,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
