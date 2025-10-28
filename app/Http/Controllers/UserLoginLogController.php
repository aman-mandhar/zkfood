<?php

namespace App\Http\Controllers;

use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // अभी: हर user सिर्फ अपना history देखे
        $query = UserLoginLog::query()
            ->with(['user:id,name'])      // eager load to avoid N+1
            ->where('user_id', $user->id)
            ->orderByDesc('id');

        // (Optional) search filters: date range
        if ($request->filled('from')) {
            $query->whereDate('login_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('login_at', '<=', $request->date('to'));
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('login-history', compact('logs'));
    }
}
