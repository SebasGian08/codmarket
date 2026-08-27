<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): \Illuminate\Http\Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                switch ($guard) {
                    case 'web':
                        return redirect('/auth/inicio');
                    case 'alumnos':
                        return redirect('/alumno/avisos');
                    case 'empresasw':
                        return redirect('/empresa/avisos');
                    default:
                        return redirect('/admin/dashboard');
                }
            }
        }

        return $next($request);
    }
}
