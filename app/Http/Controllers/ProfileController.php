<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    /**
     * Profile overview (read-only dashboard view).
     */
    public function index()
    {
        $user = Auth::user();

        // If you use eager loads for profile relations, add them here.
        // $user->load('addresses', 'orders');

        return view('profile.index', compact('user'));
    }

    /**
     * Show edit form.
     */
    public function edit()
    {
        $user = Auth::user();

        // Example: pass cities list if you have addresses/city selector
        // $cities = City::orderBy('name')->get();

        return view('profile.edit', [
            'user' => $user,
            // 'cities' => $cities,
        ]);
    }

    /**
     * Update base profile details (name, phone, address, etc.).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Adjust fields to match your users table columns.
        // Common extra columns shown here: phone, address, city_id, pincode
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:120'],
            'email'     => [
                'required', 'email', 'max:190',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'phone'     => ['nullable', 'string', 'max:20'],
            'address'   => ['nullable', 'string', 'max:255'],
            'city_id'   => ['nullable', 'integer', 'exists:cities,id'],
            'pincode'   => ['nullable', 'string', 'max:10'],

            // Optional password change (only if provided)
            'password'  => ['nullable', 'confirmed', 'min:8'],
        ]);

        // If password supplied, hash it; otherwise ignore
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        unset($validated['password']);
        unset($validated['password_confirmation']);

        // Update the rest
        $user->fill($validated);
        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update avatar image.
     *
     * Expects input name="avatar" from the form.
     * Stores files in storage/app/public/avatars.
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => [
                'required',
                'image',            // validates it’s an image
                'mimes:jpg,jpeg,png,webp',
                'max:2048',         // 2 MB
            ],
            // Optional: allow user to remove avatar
            'remove' => ['sometimes', 'boolean'],
        ]);

        // Handle "remove" checkbox quickly
        if ($request->boolean('remove')) {
            $this->deleteOldAvatarIfAny($user);
            $user->profile_image = null; // column name you use; adjust if different
            $user->save();

            return back()->with('success', 'Avatar removed.');
        }

        // Upload new avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Delete previous avatar file if present
            $this->deleteOldAvatarIfAny($user);

            // Store new one
            $path = $file->store('avatars', 'public'); // storage/app/public/avatars/xxxx.ext

            // Save relative path (recommended)
            $user->profile_image = $path;
            $user->save();

            return back()->with('success', 'Avatar updated.');
        }

        return back()->with('error', 'No avatar file received.');
    }

    /**
     * Internal helper: delete old avatar file from storage if it exists.
     */
    protected function deleteOldAvatarIfAny($user): void
    {
        // If you store just the relative path in profile_image, this works:
        if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }
    }
}
