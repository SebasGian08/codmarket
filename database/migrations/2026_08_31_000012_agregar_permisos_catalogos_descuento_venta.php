<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarPermisosCatalogosDescuentoVenta extends Migration
{
    protected $modulos = [
        'tipos-venta' => 'Tipo de Venta',
        'motivos-descuento' => 'Motivo de Descuento',
        'reglas-descuento' => 'Regla de Descuento',
    ];

    public function up()
    {
        $roles = DB::table('roles')->pluck('id_rol');

        foreach ($this->modulos as $prefijo => $nombreSingular) {
            $permisos = [
                ["nombre" => "Ver $nombreSingular", 'codigo' => "$prefijo.ver"],
                ["nombre" => "Crear $nombreSingular", 'codigo' => "$prefijo.crear"],
                ["nombre" => "Editar $nombreSingular", 'codigo' => "$prefijo.editar"],
                ["nombre" => "Eliminar $nombreSingular", 'codigo' => "$prefijo.eliminar"],
            ];

            foreach ($permisos as $permiso) {
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
    }

    public function down()
    {
        foreach ($this->modulos as $prefijo => $nombreSingular) {
            foreach (['ver', 'crear', 'editar', 'eliminar'] as $accion) {
                $id = DB::table('permisos')->where('codigo', "$prefijo.$accion")->value('id_permiso');
                if ($id) {
                    DB::table('rol_permiso')->where('id_permiso', $id)->delete();
                }
            }
        }
    }
}
