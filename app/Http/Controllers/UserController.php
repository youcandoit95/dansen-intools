<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $activeMenu = 'users';
        $users = User::with('cabang')->whereNull('deleted_at')->latest()->get();
        $trashed = User::onlyTrashed()->with('cabang')->latest('deleted_at')->get();

        return view('users.index', compact('users', 'trashed', 'activeMenu'));
    }

    public function create()
    {
        $activeMenu = 'users';
        $cabangs = Cabang::all();
        return view('users.create', compact('cabangs', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cabang_id' => 'nullable|exists:cabang,id',
            'username' => 'required|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'no_wa' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'superadmin' => 'boolean',
            'manager' => 'boolean',
            'supervisor' => 'boolean',
            'staff' => 'boolean',
            'status' => 'boolean',
        ]);

        $manualSalt = env('APP_PASSWORD_SALT');
        if (!$manualSalt) {
            abort(500, 'APP_PASSWORD_SALT belum diatur di file .env');
        }

        $validated['password'] = Hash::make($validated['password'] . $manualSalt);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'User dihapus (soft delete).');
    }

    public function toggle(Request $request, User $user, $field)
    {
        $booleanFields = ['superadmin', 'manager', 'supervisor', 'staff', 'status'];

        if (!in_array($field, $booleanFields)) {
            return redirect()->back()->with('error', 'Field tidak valid.');
        }

        $user->update([$field => !$user->{$field}]);

        return redirect()->back()->with('success', ucfirst($field) . ' berhasil diubah.');
    }

    public function edit(User $user)
    {
        $activeMenu = 'users';
        $cabangs = Cabang::all();
        return view('users.edit', compact('user', 'cabangs', 'activeMenu'));
    }

    public function update(Request $request, User $user)
    {
        foreach (['superadmin', 'manager', 'supervisor', 'staff', 'status'] as $field) {
            $request->merge([
                $field => $request->has($field) ? 1 : 0
            ]);
        }

        $validated = $request->validate([
            'cabang_id' => 'nullable|exists:cabang,id',
            'username' => ['required', 'regex:/^\\S*$/', 'unique:users,username,' . $user->id],
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'no_wa' => 'nullable|string|max:20',
            'superadmin' => 'boolean',
            'manager' => 'boolean',
            'supervisor' => 'boolean',
            'staff' => 'boolean',
            'status' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $manualSalt = env('APP_PASSWORD_SALT');
        if (!$manualSalt) {
            abort(500, 'APP_PASSWORD_SALT belum diatur di file .env');
        }

        $user->update([
            'password' => Hash::make($validated['password'] . $manualSalt),
        ]);

        return redirect()->route('users.index')->with('success', 'Password berhasil direset.');
    }
}
