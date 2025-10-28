<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthBootstrapService
{
    public function run(User $user, ?Request $request = null): array
    {
        // नया session generate करें (login के बाद)
        if ($request) {
            $request->session()->regenerate();
        }

        // custom session token बनाएं
        $sessionToken = Str::random(60);
        $user->session_token = $sessionToken;
        $user->save();

        // अगर Spldiscount टेबल नहीं है, तो error न फेंके
        $balance = 0;
        try {
            if (class_exists(\App\Models\Spldiscount::class)) {
                $spl = \App\Models\Spldiscount::where('user_id', $user->id)->get();
                $balance = ($spl->sum('issued') ?? 0) - ($spl->sum('used') ?? 0);
            }
        } catch (\Throwable $e) {
            $balance = 0;
        }

        // session variables सेट करें
        session([
            'user_session_token'   => $sessionToken,
            'balance_spl_discount' => $balance,
        ]);

        return ['session_token' => $sessionToken, 'balance_spl_discount' => $balance];
    }
}
