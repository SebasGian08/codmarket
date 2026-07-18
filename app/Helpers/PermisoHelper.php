<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermisoHelper
{

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