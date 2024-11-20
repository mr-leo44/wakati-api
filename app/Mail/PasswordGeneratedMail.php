<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $user, public $password, public $activationToken){}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activation de votre compte wakati-app',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $activationUrl = config('app.frontend_url)' . '/activate?token=' . $this->activationToken);
        return new Content(
            view: 'mails.activation-token',
            with: [
                'user' => $this->user,
                'activationUrl' => $activationUrl,
                'password' => $this->password,
            ]
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
