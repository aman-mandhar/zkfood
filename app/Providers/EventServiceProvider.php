<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\UserLoginLog;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            \App\Listeners\SendWelcomeEmail::class,
            SendEmailVerificationNotification::class,
        ],

        Login::class => [
            \App\Listeners\SendLoginEmail::class, // optional custom
        ],

        PasswordReset::class => [
            \App\Listeners\SendPasswordResetConfirmation::class, // optional custom
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        /**
         * 🔹 Track user login
         */
        Event::listen(Login::class, function ($event) {
            try {
                UserLoginLog::create([
                    'user_id'    => $event->user->id,
                    'login_at'   => now(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                    'session_id' => request()->session()->getId(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to record login log: '.$e->getMessage());
            }
        });

        /**
         * 🔹 Track user logout
         */
        Event::listen(Logout::class, function ($event) {
            try {
                $log = UserLoginLog::where('user_id', $event->user->id)
                    ->whereNull('logout_at')
                    ->latest('id')
                    ->first();

                if ($log) {
                    $log->update(['logout_at' => now()]);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to record logout log: '.$e->getMessage());
            }
        });
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
