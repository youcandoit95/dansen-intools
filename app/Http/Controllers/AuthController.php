<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil salt dari .env, wajib diisi
        $manualSalt = env('APP_PASSWORD_SALT');
        if (!$manualSalt) {
            abort(500, 'APP_PASSWORD_SALT belum diatur di file .env');
        }

        // Cari user dengan username dan status aktif
        $user = User::where('username', $credentials['username'])
            ->where('status', true)
            ->first();

        // Verifikasi password + salt manual
        if ($user && Hash::check($credentials['password'] . $manualSalt, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Ambil nama cabang jika relasi tersedia
            $cabangName = optional($user->cabang)->nama_cabang;

            // Simpan seluruh data user ke session (tanpa password)
            session([
                'user_id'     => $user->id,
                'username'    => $user->username,
                'name'        => $user->name,
                'email'       => $user->email,
                'no_wa'       => $user->no_wa,
                'cabang_id'   => $user->cabang_id,
                'cabang_name' => $cabangName,
                'superadmin'  => $user->superadmin,
                'manager'     => $user->manager,
                'supervisor'  => $user->supervisor,
                'staff'       => $user->staff,
            ]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah, atau akun dinonaktifkan.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
