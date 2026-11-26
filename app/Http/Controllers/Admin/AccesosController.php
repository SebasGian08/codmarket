<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PermisoHelper;
use App\Http\Controllers\Controller;
use App\Models\Permiso;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AccesosController extends Controller
{
    public function index(Request $request)
    {
        $esAdministrador = PermisoHelper::esAdministrador();
        $puedeVerUsuarios = $esAdministrador || PermisoHelper::tiene('usuarios.ver');
        $puedeVerRoles = $esAdministrador || PermisoHelper::tiene('roles.ver');
        $puedeVerPermisos = $esAdministrador || PermisoHelper::tiene('permisos.ver');

        abort_unless($puedeVerUsuarios || $puedeVerRoles || $puedeVerPermisos, 403);

        $pestanasDisponibles = [];
        if ($puedeVerUsuarios) {
            $pestanasDisponibles[] = 'usuarios';
        }
        if ($puedeVerRoles) {
            $pestanasDisponibles[] = 'roles';
        }
        if ($puedeVerPermisos) {
            $pestanasDisponibles[] = 'permisos';
        }

        $pestanaActiva = $request->query('tab');
        if (!in_array($pestanaActiva, $pestanasDisponibles, true)) {
            $pestanaActiva = $pestanasDisponibles[0];
        }

        $filtros = [
            'nombre' => $request->query('nombre'),
            'id_rol' => $request->query('id_rol'),
        ];

        $roles = ($puedeVerUsuarios || $puedeVerRoles)
            ? Rol::orderBy('id_rol', 'desc')->get()
            : collect();

        $users = collect();
        if ($puedeVerUsuarios) {
            $consultaUsuarios = Usuario::with('rol');

            if (!empty($filtros['nombre'])) {
                $consultaUsuarios->where(function ($query) use ($filtros) {
                    $query->where('nombres', 'like', '%' . $filtros['nombre'] . '%')
                        ->orWhere('apellidos', 'like', '%' . $filtros['nombre'] . '%')
                        ->orWhere('email', 'like', '%' . $filtros['nombre'] . '%');
                });
            }

            if (!empty($filtros['id_rol'])) {
                $consultaUsuarios->where('id_rol', $filtros['id_rol']);
            }

            $users = $consultaUsuarios->orderBy('id_usuario', 'desc')->get();
        }

        $permisos = $puedeVerPermisos
            ? Permiso::orderBy('id_permiso')->get()
            : collect();

        return view('admin.accesos.index', compact(
            'puedeVerUsuarios',
            'puedeVerRoles',
            'puedeVerPermisos',
            'pestanaActiva',
            'filtros',
            'users',
            'roles',
            'permisos'
        ));
    }
}
