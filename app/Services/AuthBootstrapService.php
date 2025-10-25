<?php
namespace App\Services;

use App\Models\Spldiscount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthBootstrapService
{
    public function run(User $user, ?Request $request = null): array
    {
        if ($request) {
            $request->session()->regenerate();
        }

        $sessionToken = Str::random(60);
        $user->session_token = $sessionToken;
        $user->save();

        $spl = Spldiscount::where('user_id', $user->id)->get();
        $balance = $spl->sum('issued') - $spl->sum('used');

        session([
            'user_session_token'   => $sessionToken,
            'balance_spl_discount' => $balance,
        ]);

        return ['session_token' => $sessionToken, 'balance_spl_discount' => $balance];
    }
}
