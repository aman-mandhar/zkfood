<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Mail\LoginAlertMail;
use Illuminate\Support\Facades\Mail;

class SendLoginEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $key = "login-mail:{$user->id}:" . now()->format('YmdHi'); // per minute
        if (Cache::has($key)) return;
        Cache::put($key, true, now()->addMinutes(1));

        $ip = request()->ip() ?? 'unknown';
        $agent = request()->userAgent() ?? 'unknown';
        $time = now()->timezone(config('app.timezone', 'UTC'))->toDayDateTimeString();

        Mail::to($user->email)->queue(new LoginAlertMail($ip, $agent, $time));
    }
}
