<?php

namespace App\Services\Email;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendEmail(User $user , Mailable $mailable)
    {
        Mail::to($user['email'])->send($mailable);
        return true;
    }
}
