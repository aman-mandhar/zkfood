<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\City;
use App\Models\User;
use App\Services\AuthBootstrapService;

class AuthController extends Controller
{
    // -------------------- LOGIN PAGE --------------------
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectDash());
        }
        return view('login');
    }

    // -------------------- REGISTER PAGE --------------------
    public function showRegisterForm(Request $request)
    {
        $cities = City::all();
        $ref_code = $request->query('ref'); // URL ?ref=CODE
        return view('register', compact('cities', 'ref_code'));
    }

    // -------------------- REGISTER --------------------
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'mobile_number' => 'required|numeric|digits:10|unique:users,mobile_number',
            'email'         => 'nullable|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'city'          => 'required|exists:cities,id',
            'gst_no'        => 'nullable|string|max:15',
            'location_lat'  => 'nullable|numeric',
            'location_lng'  => 'nullable|numeric',
            'ref_code'      => 'nullable|string|max:32',
        ]);

        // अगर कोई ref_code दिया गया है
        $referrer = $request->filled('ref_code')
            ? User::where('ref_code', $request->ref_code)->first()
            : null;

        $ref_id = $referrer?->id ?? 1;
        $email  = $request->email ?? ($request->mobile_number . '@zedkaysuperstore.com');

        $user = User::create([
            'name'              => $request->name,
            'mobile_number'     => $request->mobile_number,
            'ref_mobile_number' => $referrer?->mobile_number ?? '0000000000',
            'ref_id'            => $ref_id,
            'user_role'         => 2, // Customer
            'email'             => $email,
            'password'          => Hash::make($request->password),
            'city'              => $request->city,
            'gst_no'            => $request->gst_no,
            'location_lat'      => $request->location_lat,
            'location_lng'      => $request->location_lng,
        ]);

        Auth::login($user);
        app(AuthBootstrapService::class)->run($user, $request);

        return redirect()
            ->route($this->redirectDash())
            ->with('success', 'पंजीकरण सफल रहा! स्वागत है Zedkay Superstore में।');
    }

    // -------------------- LOGIN --------------------
    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|digits:10',
            'password'      => 'required|string|min:8',
            'remember'      => 'nullable|boolean',
        ]);

        $user = User::where('mobile_number', $request->mobile_number)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, (bool)$request->boolean('remember'));
            app(AuthBootstrapService::class)->run($user, $request);

            // अगर AJAX request है
            if ($request->ajax()) {
                $redirect = $request->input('redirect_to') ?: route($this->redirectDash());
                return response()->json(['redirect_to' => $redirect]);
            }

            // अगर redirect_to parameter है
            if ($request->filled('redirect_to')) {
                return redirect()->to($request->string('redirect_to'));
            }

            return redirect()->route($this->redirectDash());
        }

        // गलत credentials
        if ($request->ajax()) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return back()->with('error', 'मोबाइल नंबर या पासवर्ड गलत है।');
    }

    // -------------------- LOGOUT --------------------
    public function logout(Request $request)
    {
        if ($user = Auth::user()) {
            $user->session_token = null;
            $user->save();
        }

        Auth::logout();

        // सुरक्षा के लिए नया session बनाएं
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::flush();

        return redirect('/');
    }

    // -------------------- REDIRECT HELPER --------------------
    protected function redirectDash(): string
    {
        return \App\Support\Dashboard::routeNameFor(Auth::user());
    }

    // -------------------- DEFAULT DASHBOARD REDIRECT --------------------
    public function dashboardAll()
    {
        if (Auth::check()) {
            return redirect()->route($this->redirectDash());
        }
        return view('login');
    }
}
