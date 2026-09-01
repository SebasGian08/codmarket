<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarPermisosTiposGastos extends Migration
{
    protected $permisos = [
        ['nombre' => 'Ver Tipos de Gastos', 'codigo' => 'tipos-gastos.ver'],
        ['nombre' => 'Crear Tipo de Gasto', 'codigo' => 'tipos-gastos.crear'],
        ['nombre' => 'Editar Tipo de Gasto', 'codigo' => 'tipos-gastos.editar'],
        ['nombre' => 'Eliminar Tipo de Gasto', 'codigo' => 'tipos-gastos.eliminar'],
    ];

    public function up()
    {
        $roles = DB::table('roles')->pluck('id_rol');

        foreach ($this->permisos as $permiso) {
            $id = DB::table('permisos')->where('codigo', $permiso['codigo'])->value('id_permiso');

            if (!$id) {
                $id = DB::table('permisos')->insertGetId([
                    'nombre' => $permiso['nombre'],
                    'codigo' => $permiso['codigo'],
                    'estado' => 1,
                ]);
            }

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
    }

    public function down()
    {
        foreach ($this->permisos as $permiso) {
            $id = DB::table('permisos')->where('codigo', $permiso['codigo'])->value('id_permiso');
            if ($id) {
                DB::table('rol_permiso')->where('id_permiso', $id)->delete();
            }
        }
    }
}
