<?php

namespace App\Http\Middleware;

use App\Helpers\PermisoHelper;
use Closure;
use Illuminate\Http\Request;

class AdminPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        abort_unless(
            PermisoHelper::esAdministrador() || PermisoHelper::tiene($permission),
            403
        );

        return $next($request);
    }
}
