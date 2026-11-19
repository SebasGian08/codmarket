<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarPermisosCategoriasBlog extends Migration
{
    protected $permisos = [
        ['nombre' => 'Ver Categorías del Blog', 'codigo' => 'blog-categorias.ver'],
        ['nombre' => 'Crear Categoría del Blog', 'codigo' => 'blog-categorias.crear'],
        ['nombre' => 'Editar Categoría del Blog', 'codigo' => 'blog-categorias.editar'],
        ['nombre' => 'Eliminar Categoría del Blog', 'codigo' => 'blog-categorias.eliminar'],
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
