<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        $query->whereDoesntHave('role', function ($q) {
            $q->where('name', 'Admin');
        });

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status == 'jamaah') {
            $status = $request->status;
            $query->whereHas('role', function ($q) {
                $q->where('name', 'Jamaah');
            });
        } else {
            $status = 'non-jamaah';
            $query->whereDoesntHave('role', function ($q) {
                $q->where('name', 'Jamaah');
            });
        }

        

        $perPage = $request->query('showing', 10);

        if ($perPage === 'all') {
            $users = $query->latest()->get();
        } else {
            $perPage = intval($perPage) > 0 ? intval($perPage) : 10;
            $users = $query->latest()->paginate($perPage)->withQueryString();
        }

        return view('admin.users.index', compact('users', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Exclude 'Admin' role from selection
        $roles = Role::where('name', '!=', 'Admin')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // Verify selected role is not Admin (extra security)
        $role = Role::findOrFail($request->role_id);
        if ($role->name === 'Admin') {
            return back()->with('error', 'Tidak dapat menetapkan role Admin.');
        }

        User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Exclude 'Admin' role from selection
        $roles = Role::where('name', '!=', 'Admin')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        // Verify selected role is not Admin (extra security)
        $role = Role::findOrFail($request->role_id);
        if ($role->name === 'Admin') {
            return back()->with('error', 'Tidak dapat menetapkan role Admin.');
        }

        $data = [
            'full_name' => $request->full_name,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'phone_number' => $request->phone_number,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting Jamaah
        if ($user->hasRole('Jamaah')) {
            return back()->with('error', 'Pengguna dengan role Jamaah tidak dapat dihapus.');
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
