<?php

namespace App\Http\Middleware;

use App\Models\Configuracion;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckConfiguracionMantenimiento
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            return $next($request);
        }

        $modoMantenimiento = Configuracion::where('clave', 'modo_mantenimiento')->value('valor');

        if (filter_var($modoMantenimiento, FILTER_VALIDATE_BOOLEAN)) {
            return response()->view('pages.errors.mantenimiento', [], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return $next($request);
    }
}
