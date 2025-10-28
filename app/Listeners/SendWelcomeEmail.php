<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        $user = $event->user;
        Mail::to($user->email)->queue(new WelcomeMail($user));
    }
}