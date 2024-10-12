<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class SendUserCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    /**
     * Create a new message instance.
     *
     * @param $user L'utilisateur à qui envoyer les informations
     * @param $password Le mot de passe généré pour l'utilisateur
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     *
     * Définit l'objet de l'email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Login Informations'
        );
    }

    /**
     * Build the message.
     *
     * Construit l'email en utilisant une vue Blade.
     */
    public function build()
    {
        return $this->subject('Login Details')
                    ->from('lpacenter63@gmail.com', 'LPA Center')
                    ->to($this->user->email)
                    ->view('emails.plaintext');
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
