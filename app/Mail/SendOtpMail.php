<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Kode OTP Reset Password Tripzy');
    }

    public function content(): Content
    {
        // Nembak ke file tampilan email (kita bikin di bawah)
        return new Content(view: 'emails.otp');
    }
}