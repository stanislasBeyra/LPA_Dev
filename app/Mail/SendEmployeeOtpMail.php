<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmployeeOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otpcode;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $otpcode)
    {
        $this->user=$user;
        $this->otpcode=$otpcode;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OTP VERIFICATION',
        );
    }

    public function build()
    {
        return $this->subject('OTP Verification code')
                    ->from('lpa@softsolutionsdev.com', 'LPA Center')
                    ->to($this->user->email)
                    ->view('emails.otp.otpemail');
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
