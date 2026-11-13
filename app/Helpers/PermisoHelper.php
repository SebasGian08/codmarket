<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermisoHelper
{
    public static function esAdministrador($usuario = null)
    {
        $usuario = $usuario ?: Auth::user();
        $nombreRol = strtolower(trim($usuario->rol->nombre ?? ''));
        $rolNormalizado = preg_replace('/[\s_-]+/', '', $nombreRol);

        return in_array($rolNormalizado, ['admin', 'administrador', 'superadmin'], true);
    }

    public static function tiene($permiso)
    {
        if(!Auth::check()){
            return false;
        }

        $usuario = Auth::user();
        if(!$usuario->rol){
            return false;
        }

        return $usuario
            ->rol
            ->permisos()
            ->where('codigo',$permiso)
            ->exists();

    }

}