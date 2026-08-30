<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AgregarPermisosFinancieros extends Migration
{
    /**
     * Permisos de los módulos financieros (dinero): cuentas bancarias,
     * ingresos económicos, transferencias de dinero y movimientos de dinero.
     */
    protected $permisos = [
        // MÓDULO: CUENTAS BANCARIAS (dinero)
        ['nombre' => 'Ver Cuentas Bancarias', 'codigo' => 'cuentas-bancarias.ver'],
        ['nombre' => 'Crear Cuenta Bancaria', 'codigo' => 'cuentas-bancarias.crear'],
        ['nombre' => 'Editar Cuenta Bancaria', 'codigo' => 'cuentas-bancarias.editar'],
        ['nombre' => 'Eliminar Cuenta Bancaria', 'codigo' => 'cuentas-bancarias.eliminar'],

        // MÓDULO: INGRESOS ECONÓMICOS (dinero)
        ['nombre' => 'Ver Ingresos Económicos', 'codigo' => 'ingresos-economicos.ver'],
        ['nombre' => 'Registrar Ingreso Económico', 'codigo' => 'ingresos-economicos.crear'],
        ['nombre' => 'Anular Ingreso Económico', 'codigo' => 'ingresos-economicos.anular'],

        // MÓDULO: TRANSFERENCIAS DE DINERO (caja <-> cuenta)
        ['nombre' => 'Ver Transferencias de Dinero', 'codigo' => 'transferencias-dinero.ver'],
        ['nombre' => 'Crear Transferencia de Dinero', 'codigo' => 'transferencias-dinero.crear'],
        ['nombre' => 'Anular Transferencia de Dinero', 'codigo' => 'transferencias-dinero.anular'],

        // MÓDULO: MOVIMIENTOS DE DINERO (trazabilidad)
        ['nombre' => 'Ver Movimientos de Dinero', 'codigo' => 'movimientos-dinero.ver'],
    ];

    public function up()
    {
        $adminRol = DB::table('roles')->where('id_rol', 1)->value('id_rol');

        foreach ($this->permisos as $permiso) {
            $id = DB::table('permisos')->where('codigo', $permiso['codigo'])->value('id_permiso');

            if (!$id) {
                $id = DB::table('permisos')->insertGetId([
                    'nombre' => $permiso['nombre'],
                    'codigo' => $permiso['codigo'],
                    'estado' => 1,
                ]);
            }

            // Otorgar al rol Admin (id_rol = 1) los permisos que aún no tiene.
            if ($adminRol) {
                $existe = DB::table('rol_permiso')
                    ->where('id_rol', $adminRol)
                    ->where('id_permiso', $id)
                    ->exists();

                if (!$existe) {
                    DB::table('rol_permiso')->insert([
                        'id_rol' => $adminRol,
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
                DB::table('permisos')->where('id_permiso', $id)->delete();
            }
        }
    }
}
