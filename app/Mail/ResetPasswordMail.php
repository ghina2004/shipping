<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $token;

    public function __construct($token,$email)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function build(): ResetPasswordMail
    {
        return $this->markdown('emails.reset-password');
    }
}
