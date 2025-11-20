<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('client.profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('client.profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($user->image_url && Storage::disk('public')->exists(str_replace('storage/', '', $user->image_url))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $user->image_url));
                }

                // Upload new image
                $file = $request->file('image');
                $path = Storage::disk('public')->putFile('profile-images', $file);
                $validated['image_url'] = 'storage/' . $path;
            }

            // Update user
            $user->update([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? $user->phone_number,
                'image_url' => $validated['image_url'] ?? $user->image_url,
            ]);

            DB::commit();

            return redirect()->route('profile.show')
                ->with('success', 'Profil berhasil diperbarui');
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

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

    /**
     * Show preferences
     */
    public function preferences()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('client.profile.preferences', compact('user'));
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'notifications_email' => 'boolean',
            'newsletter' => 'boolean',
            'public_profile' => 'boolean',
        ]);

        // Store preferences in a metadata field or create preferences table
        // For now, storing as user attributes or meta
        $user->update([
            'notifications_email' => $validated['notifications_email'] ?? true,
            'newsletter' => $validated['newsletter'] ?? false,
        ]);

        return back()->with('success', 'Preferensi berhasil diperbarui');
    }
}

