<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarPermisoGastos extends Migration
{
    public function up()
    {
        $permiso = [
            'nombre' => 'Ver Gastos',
            'codigo' => 'gastos.ver',
        ];

        $id = DB::table('permisos')->where('codigo', $permiso['codigo'])->value('id_permiso');
        if (!$id) {
            $id = DB::table('permisos')->insertGetId([
                'nombre' => $permiso['nombre'],
                'codigo' => $permiso['codigo'],
                'estado' => 1,
            ]);
        }

        // Otorgar a TODOS los roles existentes (Admin y demás) por si aplica,
        // priorizando el rol Admin (id_rol = 1).
        $roles = DB::table('roles')->pluck('id_rol');
        foreach ($roles as $idRol) {
            $existe = DB::table('rol_permiso')
                ->where('id_rol', $idRol)
                ->where('id_permiso', $id)
                ->exists();
            if (!$existe) {
                DB::table('rol_permiso')->insert([
                    'id_rol' => $idRol,
                    'id_permiso' => $id,
                ]);
            }
        }
    }

    public function down()
    {
        $id = DB::table('permisos')->where('codigo', 'gastos.ver')->value('id_permiso');
        if ($id) {
            DB::table('rol_permiso')->where('id_permiso', $id)->delete();
        }
    }
}
