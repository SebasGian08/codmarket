<?php

namespace App\Http\Middleware;

use App\Helpers\PermisoHelper;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(PermisoHelper::esAdministrador(), 403);

        return $next($request);
    }
}
