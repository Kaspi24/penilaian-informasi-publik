<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $token, $expiry_time;

    public function __construct($token, $expiry_time)
    {
        $this->token        = $token;
        $this->expiry_time  = $expiry_time;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Alamat Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verify-email-address',
            with: [
                'token'         => $this->token,
                'expiry_time'   => $this->expiry_time,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
