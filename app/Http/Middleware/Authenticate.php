<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle($request, Closure $next, $guard = null)
    {
        // Usamos el método oficial de Laravel para verificar la sesión
        if (!Auth::check()) {

            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}