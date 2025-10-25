<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // smart role based redirect
        return $this->redirectToRoleDashboard();
    }

    public function adminDashboard()
    {
        return view('dashboards.admin');
    }

    public function promoterDashboard()
    {
        return view('dashboards.promoter');
    }

    public function guestDashboard()
    {
        return view('dashboards.guest');
    }

    protected function redirectToRoleDashboard()
    {
        $role = (int) Auth::user()->user_role_id; // ensure using correct DB column

        switch ($role) {
            case 1:
                return redirect()->route('admin.dashboard');
            case 11:
                return redirect()->route('promoter.dashboard');
            case 2:
                return redirect()->route('guest.dashboard');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Invalid role! Logged out.');
        }
    }
}
