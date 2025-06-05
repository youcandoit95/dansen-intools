<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        foreach ($roles as $role) {
            if (session($role) === true) {
                return $next($request);
            }
        }

        abort(403, 'Akses tidak diizinkan.');
    }
}
