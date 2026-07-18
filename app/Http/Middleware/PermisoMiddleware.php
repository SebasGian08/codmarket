<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class PermisoMiddleware
{

    public function handle($request, Closure $next, $permiso)
    {

        if(!Auth::check()){
            abort(401);
        }


        $tiene = Auth::user()
            ->rol
            ->permisos
            ->where('codigo',$permiso)
            ->count();


        if(!$tiene){
            abort(403);
        }


        return $next($request);

    }

}