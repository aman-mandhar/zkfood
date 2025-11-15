<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * /dashboard पर आएंगे तो user को उसके role के हिसाब से
     * सही dashboard route पर भेज देंगे।
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Helper से role ⇒ route name लो
        $routeName = \App\Support\Dashboard::routeNameFor(Auth::user());

        // जिस route का नाम मिला है, वहीं redirect
        return redirect()->route($routeName);
    }

    // ---- Individual dashboards (views) ----

    public function adminDashboard()
    {
        return view('dashboards.admin'); // resources/views/dashboards/admin.blade.php
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
}
