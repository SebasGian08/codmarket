<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $permissions = [
        ['nombre' => 'Ver Inicio', 'codigo' => 'dashboard.ver'],
        ['nombre' => 'Cerrar Venta', 'codigo' => 'ventas.cerrar'],
        ['nombre' => 'Consultar Stock', 'codigo' => 'inventario.consulta.ver'],
        ['nombre' => 'Ver Categorías', 'codigo' => 'categorias.ver'],
        ['nombre' => 'Ver Marcas', 'codigo' => 'marcas.ver'],
        ['nombre' => 'Ver Proveedores', 'codigo' => 'proveedores.ver'],
        ['nombre' => 'Ver Productos', 'codigo' => 'productos.ver'],
        ['nombre' => 'Carga Masiva de Productos', 'codigo' => 'productos.carga'],
        ['nombre' => 'Ver Atributos', 'codigo' => 'atributos.ver'],
        ['nombre' => 'Actualizar Precios', 'codigo' => 'productos.precios'],
        ['nombre' => 'Ver Empresa', 'codigo' => 'empresa.ver'],
        ['nombre' => 'Ver Usuarios', 'codigo' => 'usuarios.ver'],
        ['nombre' => 'Ver Roles', 'codigo' => 'roles.ver'],
        ['nombre' => 'Ver Permisos', 'codigo' => 'permisos.ver'],
        ['nombre' => 'Ver Configuración', 'codigo' => 'configuracion.ver'],
        ['nombre' => 'Ver Blogs', 'codigo' => 'blogs.ver'],
        ['nombre' => 'Ver Tags', 'codigo' => 'tags.ver'],
        ['nombre' => 'Ver Servicios', 'codigo' => 'servicios.ver'],
        ['nombre' => 'Ver Portafolio', 'codigo' => 'portafolios.ver'],
        ['nombre' => 'Ver Contactos', 'codigo' => 'contacts.ver'],
        ['nombre' => 'Ver Banners', 'codigo' => 'banners.ver'],
        ['nombre' => 'Ver Promociones', 'codigo' => 'promociones.ver'],
        ['nombre' => 'Ver Trabajos Realizados', 'codigo' => 'trabajos.ver'],
        ['nombre' => 'Ver Rubros', 'codigo' => 'rubros.ver'],
        ['nombre' => 'Ver Preguntas Frecuentes', 'codigo' => 'preguntas.ver'],
    ];

    public function up(): void
    {
        $adminRoleId = DB::table('roles')->where('id_rol', 1)->value('id_rol');

        foreach ($this->permissions as $permission) {
            $permissionId = DB::table('permisos')
                ->where('codigo', $permission['codigo'])
                ->value('id_permiso');

            if (!$permissionId) {
                $permissionId = DB::table('permisos')->insertGetId(
                    array_merge($permission, ['estado' => 1])
                );
            }

            if ($adminRoleId) {
                $assigned = DB::table('rol_permiso')
                    ->where('id_rol', $adminRoleId)
                    ->where('id_permiso', $permissionId)
                    ->exists();

                if (!$assigned) {
                    DB::table('rol_permiso')->insert([
                        'id_rol' => $adminRoleId,
                        'id_permiso' => $permissionId,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Keep permissions and role assignments intact if this data migration is rolled back.
    }
};
