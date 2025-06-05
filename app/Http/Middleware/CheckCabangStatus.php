<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCabangStatus
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && (!$user->cabang || $user->cabang->status === false)) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'username' => 'Cabang Anda sedang nonaktif.',
            ]);
        }

        return $next($request);
    }
}
