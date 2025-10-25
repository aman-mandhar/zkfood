<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->user_role_id;

        // Map करो roles → id
        $roles = [
            'admin'    => 1,
            'promoter' => 2,
            'pro'      => 3,
            'guest'    => 4,
        ];

        if (!isset($roles[$role]) || $userRole !== $roles[$role]) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}