<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearMaestrosDinero extends Migration
{
    public function up()
    {
        // ---------- DESTINOS DE PAGO ----------
        // Determina si un método de pago afecta CAJA o CUENTA BANCARIA.
        if (!Schema::hasTable('destinos_pago')) {
            Schema::create('destinos_pago', function (Blueprint $table) {
                $table->increments('id_destino_pago');
                $table->string('codigo', 30)->unique();
                $table->string('nombre', 100);
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('destinos_pago') && !DB::table('destinos_pago')->exists()) {
            DB::table('destinos_pago')->insert([
                ['codigo' => 'caja', 'nombre' => 'Caja', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['codigo' => 'cuenta', 'nombre' => 'Cuenta bancaria', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // ---------- TIPOS DE MOVIMIENTO DE DINERO ----------
        // Mismo patrón que movimientos_tipo (stock): codigo + nombre + signo.
        if (!Schema::hasTable('tipos_movimiento_dinero')) {
            Schema::create('tipos_movimiento_dinero', function (Blueprint $table) {
                $table->bigIncrements('id_tipo_movimiento_dinero');
                $table->string('codigo', 60)->unique();
                $table->string('nombre', 120);
                $table->string('signo', 1)->default('+'); // + entrada | - salida
                $table->boolean('estado')->default(1);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('tipos_movimiento_dinero') && !DB::table('tipos_movimiento_dinero')->exists()) {
            $tipos = [
                ['saldo_inicial', 'Saldo inicial', '+'],
                ['venta', 'Venta', '+'],
                ['gasto', 'Gasto', '-'],
                ['ingreso_economico', 'Ingreso económico', '+'],
                ['transferencia_salida', 'Transferencia salida', '-'],
                ['transferencia_entrada', 'Transferencia entrada', '+'],
                ['anulacion_venta', 'Anulación de venta', '-'],
                ['anulacion_gasto', 'Anulación de gasto', '+'],
                ['anulacion_ingreso_economico', 'Anulación de ingreso económico', '-'],
                ['anulacion_transferencia_salida', 'Anulación de transferencia salida', '+'],
                ['anulacion_transferencia_entrada', 'Anulación de transferencia entrada', '-'],
            ];

            foreach ($tipos as $t) {
                DB::table('tipos_movimiento_dinero')->insert([
                    'codigo' => $t[0],
                    'nombre' => $t[1],
                    'signo' => $t[2],
                    'estado' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ---------- TIPOS DE INGRESO ECONÓMICO ----------
        if (!Schema::hasTable('tipos_ingresos_economicos')) {
            Schema::create('tipos_ingresos_economicos', function (Blueprint $table) {
                $table->bigIncrements('id_tipo_ingreso_economico');
                $table->string('nombre', 100);
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('tipos_ingresos_economicos') && !DB::table('tipos_ingresos_economicos')->exists()) {
            $tipos = [
                'Préstamo', 'Aporte de capital', 'Devolución de proveedor',
                'Otros ingresos', 'Ajuste de caja',
            ];

            foreach ($tipos as $t) {
                DB::table('tipos_ingresos_economicos')->insert([
                    'nombre' => $t, 'estado' => 1,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        // ---------- TIPOS DE GASTO (maestro incompleto: seed) ----------
        if (Schema::hasTable('tipos_gastos') && !DB::table('tipos_gastos')->exists()) {
            $tipos = [
                'Servicio', 'Compra de mercadería', 'Alquiler', 'Luz y agua',
                'Transporte', 'Publicidad', 'Sueldos', 'Otros gastos',
            ];

            foreach ($tipos as $t) {
                DB::table('tipos_gastos')->insert([
                    'nombre' => $t, 'estado' => 1,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('tipos_ingresos_economicos');
        Schema::dropIfExists('tipos_movimiento_dinero');
        Schema::dropIfExists('destinos_pago');
    }
}
