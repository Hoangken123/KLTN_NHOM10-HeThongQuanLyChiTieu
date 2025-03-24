<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verifyUrl;
    public $fullName;

    public function __construct($user, $token)
    {
        $this->verifyUrl = config('app.url') . '/api/verify-email?token=' . $token;
        $this->fullName = $user->fullName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận tài khoản'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify-email',
            with: [
                'verifyUrl' => $this->verifyUrl,
                'fullName' => $this->fullName,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
