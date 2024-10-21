<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InscricaoConfirmadaMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Inscricao $inscricao)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Inscricao Confirmada',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.inscricao-confirmada',
            with: ['inscricao' => $this->inscricao]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
