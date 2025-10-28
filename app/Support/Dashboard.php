<?php

namespace App\Support;

use App\Models\User;

class Dashboard
{
    public static function routeNameFor(User $user): string
    {
        // role के हिसाब से redirect route
        $routes = [
            1 => 'admin.dashboard',
            2 => 'customer.dashboard',
            5 => 'foodvendor.dashboard',
            7 => 'deliverypartner.dashboard',
        ];

        // अगर role match न करे तो welcome पर भेजो
        return $routes[(int)$user->user_role] ?? 'welcome';
    }
}
