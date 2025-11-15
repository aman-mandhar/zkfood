<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\City;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $cities = City::all();  
        $roles = UserRole::all();
        return view('users.index', compact('users', 'roles', 'cities', 'roles'));
    }
    public function search(Request $request)
    {
        $search = $request->get('search');
        $users = User::orwhere('name', 'like', '%' .$search. '%')
        ->orwhere('mobile_number', 'like', '%'.$search.'%')
        ->orwhere('ref_mobile_number', 'like', '%'.$search.'%')
        ->orwhere('email', 'like', '%'.$search.'%')
        ->orwhere('city', 'like', '%'.$search.'%')->get();
        $cities = City::all();  
        $roles = UserRole::all();
        return view('users.index', ['users' => $users],);
    }

    public function getReferralName(Request $request)
    {
    $referralMobileNumber = $request->input('referralMobileNumber');
    $referralUser = User::where('mobile_number', $referralMobileNumber)->first();

    return response()->json(['name' => $referralUser ? $referralUser->name : null]);

    }

    public function getReferralId(Request $request)
{
    $referralMobileNumber = $request->input('referralMobileNumber');
    
    // Query the User model by mobile_number column
    $referralUser = User::where('mobile_number', $referralMobileNumber)->first();

    return response()->json(['id' => $referralUser ? $referralUser->id : null]);
}

    public function create()
    {
        $users = User::all();
        $cities = City::all();  
        $roles = UserRole::all();
        return view('users.create', compact('roles'), compact('cities'), compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|numeric|digits:10|unique:users',
            'ref_mobile_number' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'user_role' => 'required|exists:user_roles,id',
            'city' => 'required|exists:cities,id',
            'gst_no' => 'nullable|string|max:255',
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_number' => $request->mobile_number,
            'ref_mobile_number' => $request->ref_mobile_number,
            'city' => $request->city,
            'user_role' => $request->user_role,
            'gst_no' => $request->gst_no,
        ]);
        $user->save();

    
            // You can redirect the user wherever you want after creation
            return redirect()->route('users.create')->with('success', 'User created successfully!');
        }
   

    

    public function edit($user)
    {
        $user = User::find($user);
        $cities = City::all();
        $roles = UserRole::all();
        $user_role = UserRole::find($user->user_role);
        return view('users.edit', compact('user', 'roles', 'cities', 'user_role'));
    }

    public function update(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'mobile_number' => 'nullable|numeric|digits:10',
            'city' => 'required|exists:cities,id',
            'gst_no' => 'nullable|string|max:255',
        ]);

        // Update the user
        $user = User::findOrFail($id); // Find user of id = $id
        if($user){
            $user->name = $request->name;
            $user->mobile_number = $request->mobile_number;
            $user->city = $request->city;
            $user->gst_no = $request->gst_no;
        }
        $user->update($validatedData);

        // You can redirect the user wherever you want after updation
        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function updatePasswords()
    {
        // Step 1: Generate a hashed password
            $hashedPassword = Hash::make('12345678');

        // Step 2: Use Query Builder to update passwords for user_role = 3
            DB::table('users')  // Target the 'users' table
                ->where('user_role', 3)  // Add a WHERE condition
                ->update(['password' => $hashedPassword]);  // Update the 'password' field

    return "Passwords updated successfully!";
}

    public function destroy($user)
    {
        $user = User::find($user);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function profile()
    {
        $current_id = Auth::user()->id;
        $user = DB::table('users')
            ->select('users.*', 'user_roles.name as role', 'cities.name as city')
            ->join('user_roles', 'users.user_role', '=', 'user_roles.id')
            ->join('cities', 'users.city', '=', 'cities.id')
            ->where('users.id', $current_id) // Specify 'users.id'
            ->first();
        
        $ref = User::where('mobile_number', $user->ref_mobile_number)->first();
        $cities = City::all();
        return view('profile', compact('user', 'cities', 'ref'));
    }

    public function changePassword()
    {
        return view('users.change-password');
    }

    public function referrals()
    {
    $user = Auth::user();
    $direct_refs = User::where('ref_mobile_number', $user->mobile_number)->get();
    return view('users.referrals', compact('direct_refs'));
    }

    public function ref_1($user)
    {
        $user = User::find($user);
        $ref_1s = User::where('ref_mobile_number', $user->mobile_number)->get();
        return view('users.ref_1', compact('ref_1s', 'user'));
    }
    
    public function ref_2($user)
    {
        $user = User::find($user);
        $ref_2s = User::where('ref_mobile_number', $user->mobile_number)->get();
        return view('users.ref_2', compact('ref_2s', 'user'));
    }

    public function ref_3($user)
    {
        $user = User::find($user);
        $ref_3s = User::where('ref_mobile_number', $user->mobile_number)->get();
        return view('users.ref_3', compact('ref_3s', 'user'));
    }

    public function createMerchant()
    {
        $merchants = User::where('user_role', 18)->where('ref_mobile_number', Auth::user()->mobile_number)->get();
        return view('users.create_merchant', compact('merchants'));
    }

    public function indexMerchant()
    {
        $merchants = User::where('user_role', 18)->get();
        return view('users.index_merchant', compact('merchants'));
    }
    
}
