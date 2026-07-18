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


        return $usuario
            ->rol
            ->permisos
            ->where('codigo',$permiso)
            ->count() > 0;

    }

}