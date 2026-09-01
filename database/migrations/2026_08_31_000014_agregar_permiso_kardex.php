<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarPermisoKardex extends Migration
{
    public function up()
    {
        $id = DB::table('permisos')->where('codigo', 'kardex.ver')->value('id_permiso');

        if (!$id) {
            $id = DB::table('permisos')->insertGetId([
                'nombre' => 'Ver Kardex de Inventario',
                'codigo' => 'kardex.ver',
                'estado' => 1,
            ]);
        }

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
        $id = DB::table('permisos')->where('codigo', 'kardex.ver')->value('id_permiso');
        if ($id) {
            DB::table('rol_permiso')->where('id_permiso', $id)->delete();
        }
    }
}
