<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMessageMail extends Mailable
{
    use Queueable;
    use SerializesModels;


    public function __construct(
        public User $destinatario,
        public User $administrador,
        public string $assunto,
        public string $conteudo,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | ASSUNTO DO E-MAIL
    |--------------------------------------------------------------------------
    */

    public function envelope(): Envelope
    {
        return new Envelope(
            subject:
                $this->assunto
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW DO E-MAIL
    |--------------------------------------------------------------------------
    */

    public function content(): Content
    {
        return new Content(
            view:
                'emails.admin-message'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ANEXOS
    |--------------------------------------------------------------------------
    */

    public function attachments(): array
    {
        return [];
    }
}