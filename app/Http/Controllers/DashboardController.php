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

    public function customerDashboard()
    {
        return view('dashboards.customer');
    }

    public function foodVendorDashboard()
    {
        return view('dashboards.foodvendor');
    }

    public function deliveryPartnerDashboard()
    {
        return view('dashboards.deliverypartner');
    }

    protected function redirectToRoleDashboard()
    {
        $role = (int) Auth::user()->user_role_id; // ensure using correct DB column

        switch ($role) {
            case 1:
                return redirect()->route('admin.dashboard');
            case 2:
                return redirect()->route('customer.dashboard');
            case 5:
                return redirect()->route('foodvendor.dashboard');
            case 7:
                return redirect()->route('deliverypartner.dashboard');

            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Invalid role! Logged out.');
        }
    }
}
