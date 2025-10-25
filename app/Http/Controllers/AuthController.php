<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\City;
use App\Models\User;
use App\Models\Spldiscount;
use Illuminate\Support\Str;
use App\Services\AuthBootstrapService;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectDash());
        }
        return view('login');
    }

    public function showRegisterForm(Request $request)
    {
        $cities = City::all();
        $ref_code = $request->query('ref') ?? null;

        return view('register', compact('cities', 'ref_code'));
    }

    public function Profile(string $slug) // same case to match your route
    {
        return redirect()->route('welcome');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'mobile_number'    => 'required|numeric|digits:10|unique:users,mobile_number',
            'email'            => 'nullable|email|max:255|unique:users,email',
            'password'         => 'required|string|min:8|confirmed',
            'city'             => 'required|exists:cities,id',
            'gst_no'           => 'nullable|string|max:15',
            'location_lat'     => 'nullable|numeric',
            'location_lng'     => 'nullable|numeric',
        ]);

        // ✳️ Referral logic via ref_code (safe way)
        $referrer = null;
        if ($request->filled('ref_code')) {
            $referrer = User::where('ref_code', $request->ref_code)->first();
        }
        $ref_id = $referrer?->id ?? 1;

        // Auto-generate email if null
        $email = $request->email ?? ($request->mobile_number . '@zedkaysuperstore.com');

        $user = User::create([
            'name'             => $request->name,
            'mobile_number'    => $request->mobile_number,
            'ref_mobile_number'=> $referrer?->mobile_number ?? '0000000000',
            'ref_id'           => $ref_id,
            'user_role'        => 2, // Default: Customer
            'email'            => $email,
            'password'         => Hash::make($request->password),
            'city'             => $request->city,
            'gst_no'           => $request->gst_no,
            'location_lat'     => $request->location_lat,
            'location_lng'     => $request->location_lng,
        ]);

        // Auto-login
        Auth::login($user);
        app(AuthBootstrapService::class)->run($user, $request);

        return redirect()->route($this->redirectDash())->with('success', 'Registration successful! Welcome to Zedkay Superstore.');
    }


    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'password' => 'required|string|min:8'
        ]);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            app(AuthBootstrapService::class)->run($user, $request);


            if ($request->ajax()) {
                $redirect = $request->input('redirect_to') ?: route($this->redirectDash());
                return response()->json(['redirect_to' => $redirect]);
            }

            if ($request->filled('redirect_to')) {
                return redirect()->to($request->input('redirect_to'));
            }

            return redirect()->route($this->redirectDash());
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return back()->with('error', 'Invalid mobile number or password');
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user && $user instanceof \App\Models\User) {
            $user->session_token = null;
            $user->save();
        }

        Auth::logout();
        Session::flush();

        return redirect('/');
    }

    protected function redirectDash()
    {
        return \App\Support\Dashboard::routeNameFor(Auth::user());
    }

    public function dashboardAll()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectDash()); // ✅
        }
        return view('login');
    }
}
