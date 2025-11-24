<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class AdminProfileController extends Controller
{
    /**
     * Show admin profile
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Show edit admin profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function password()
    {
        $user = Auth::user();
        return view('admin.profile.password', compact('user'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $rules = [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Only validate these if they are present in the request
        if ($request->has('full_name')) {
            $rules['full_name'] = 'required|string|max:255';
        }
        if ($request->has('email')) {
            $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($user->id)];
        }
        if ($request->has('phone_number')) {
            $rules['phone_number'] = 'nullable|string|max:15';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $userData = [];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($user->image_url && Storage::disk('public')->exists(str_replace('storage/', '', $user->image_url))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $user->image_url));
                }

                // Upload new image
                $file = $request->file('image');
                $path = Storage::disk('public')->putFile('profile-images', $file);
                $userData['image_url'] = 'storage/' . $path;
            }

            // Update other fields if present
            if (isset($validated['full_name'])) {
                $userData['full_name'] = $validated['full_name'];
            }
            if (isset($validated['email'])) {
                $userData['email'] = $validated['email'];
            }
            if (array_key_exists('phone_number', $validated)) { // Check key existence for nullable field
                $userData['phone_number'] = $validated['phone_number'];
            }

            if (!empty($userData)) {
                $user->update($userData);
            }

            DB::commit();

            return back()->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            Auth::user()->update([
                'password' => bcrypt($validated['password']),
            ]);

            DB::commit();

            return back()->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
