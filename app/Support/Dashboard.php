<?php
namespace App\Support;

use App\Models\User;

class Dashboard
{
    public static function routeNameFor(User $user): string
    {
        $routes = [
            1=>'admin.dashboard',
            2=>'guest.dashboard',
            11=>'promoter.dashboard',
        ];
        return $routes[$user->user_role] ?? '/';
    }
}
