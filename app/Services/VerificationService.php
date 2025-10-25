<?php
// app/Services/VerificationService.php

namespace App\Services;

use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Support\Facades\Hash;

class VerificationService
{
    /**
     * OTP generate करें (email या sms, signup/login/reset आदि किसी भी purpose के लिए)
     *
     * @param array{
     *   user?: \App\Models\User|null,
     *   identifier?: string|null,   // pre-signup email/phone
     *   channel: 'email'|'sms',
     *   purpose: string,            // e.g. 'signup','login','reset','2fa','change_email','change_mobile'
     *   digits?: int,               // default 6
     *   ttl?: int,                  // seconds, default 300 (5 min)
     *   rate_cap?: int,             // requests allowed in window, default 3
     *   rate_window_minutes?: int,  // default 10
     * } $opts
     *
     * @return \App\Models\UserVerification  (->plain_code transient रूप में मौजूद होगा)
     */
    public function generate(array $opts): UserVerification
    {
        $user       = $opts['user'] ?? null;         // User|null
        $identifier = $opts['identifier'] ?? null;   // email/phone (pre-signup)
        $channel    = $opts['channel'];              // 'email' | 'sms'
        $purpose    = $opts['purpose'];              // 'signup' | 'login' | ...
        $digits     = $opts['digits'] ?? 6;
        $ttl        = $opts['ttl'] ?? 300;
        $rateCap    = $opts['rate_cap'] ?? 3;
        $rateWin    = $opts['rate_window_minutes'] ?? 10;

        // --- Validate न्यूनतम इनपुट ---
        if (!in_array($channel, ['email','sms'], true)) {
            throw new \InvalidArgumentException('Invalid channel.');
        }
        if (!$user && !$identifier) {
            throw new \InvalidArgumentException('Provide user or identifier.');
        }

        // --- Rate limit ---
        $rateQuery = UserVerification::query()
            ->when($user,
                fn($q) => $q->where('user_id', $user->id),
                fn($q) => $q->whereNull('user_id')->where('identifier', $identifier)
            )
            ->where('purpose', $purpose)
            ->where('created_at', '>=', now()->subMinutes($rateWin));

        if ($rateQuery->count() >= $rateCap) {
            throw new \RuntimeException('Too many requests. Try again later.');
        }

        // --- Single active per (user/identifier + purpose) ---
        UserVerification::query()
            ->when($user,
                fn($q) => $q->where('user_id', $user->id),
                fn($q) => $q->whereNull('user_id')->where('identifier', $identifier)
            )
            ->where('purpose', $purpose)
            ->delete();

        // --- OTP generate (fixed-length, zero-padded) ---
        $plain = str_pad((string) random_int(0, (10**$digits) - 1), $digits, '0', STR_PAD_LEFT);

        // --- Destination snapshot ---
        $sentTo = $channel === 'sms'
            ? ($user->mobile_number ?? $identifier)
            : ($user->email ?? $identifier);

        // --- Save hashed OTP ---
        $row = UserVerification::create([
            'user_id'     => $user?->id,
            'identifier'  => $user ? ($user->email ?? $user->mobile_number) : $identifier,
            'channel'     => $channel,
            'purpose'     => $purpose,
            'code'        => Hash::make($plain),
            'expires_at'  => now()->addSeconds($ttl),
            'attempts'    => 0,
            'max_attempts'=> 5,
            'sent_to'     => $sentTo,
            'meta'        => ['ip' => request()->ip(), 'ua' => request()->userAgent()],
        ]);

        // Transient property (DB में save नहीं)
        $row->plain_code = $plain;

        return $row;
    }

    /**
     * OTP verify करें.
     *
     * @param array{
     *   user?: \App\Models\User|null,
     *   identifier?: string|null, // pre-signup के लिए
     *   purpose: string,
     *   code: string,             // 6-digit
     *   delete_on_success?: bool  // default true (consume+delete)
     * } $opts
     */
    public function verify(array $opts): bool
    {
        $user      = $opts['user'] ?? null;
        $identifier= $opts['identifier'] ?? null;
        $purpose   = $opts['purpose'];
        $code      = $opts['code'];
        $del       = $opts['delete_on_success'] ?? true;

        if (!$user && !$identifier) {
            throw new \InvalidArgumentException('Provide user or identifier.');
        }

        $rec = UserVerification::query()
            ->when($user,
                fn($q) => $q->where('user_id', $user->id),
                fn($q) => $q->whereNull('user_id')->where('identifier', $identifier)
            )
            ->where('purpose', $purpose)
            ->latest('id')
            ->first();

        if (!$rec) return false;
        if ($rec->isConsumed() || $rec->isExpired()) { $rec->delete(); return false; }
        if ($rec->attempts >= $rec->max_attempts)    { $rec->delete(); return false; }

        $rec->increment('attempts');

        if (! Hash::check($code, $rec->code)) {
            return false;
        }

        // Success → consume
        $rec->consumed_at = now();
        $rec->save();

        if ($del) {
            $rec->delete(); // table साफ रहे; चाहो तो न हटाओ
        }

        return true;
    }
}
