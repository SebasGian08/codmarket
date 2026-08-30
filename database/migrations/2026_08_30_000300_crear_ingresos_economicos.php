<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearIngresosEconomicos extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ingresos_economicos')) {
            Schema::create('ingresos_economicos', function (Blueprint $table) {
                $table->bigIncrements('id_ingreso_economico');
                $table->string('numero', 30)->unique();
                $table->unsignedInteger('correlativo');

                $table->unsignedBigInteger('id_tipo_ingreso_economico');
                $table->unsignedBigInteger('id_tienda');

                // Destino financiero: caja XOR cuenta
                $table->unsignedBigInteger('id_caja')->nullable();
                $table->unsignedBigInteger('id_cuenta_bancaria')->nullable();

                $table->unsignedBigInteger('id_usuario');
                $table->date('fecha');
                $table->string('descripcion', 500);
                $table->decimal('monto', 12, 2);
                $table->string('moneda', 10)->default('PEN');
                $table->text('observacion')->nullable();
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });

            Schema::table('ingresos_economicos', function (Blueprint $table) {
                $table->foreign('id_tipo_ingreso_economico')
                    ->references('id_tipo_ingreso_economico')->on('tipos_ingresos_economicos');
                $table->foreign('id_tienda')
                    ->references('id_tienda')->on('tiendas');
                $table->foreign('id_caja')
                    ->references('id_caja')->on('cajas')->nullOnDelete();
                $table->foreign('id_cuenta_bancaria')
                    ->references('id_cuenta_bancaria')->on('cuentas_bancarias')->nullOnDelete();
                $table->foreign('id_usuario')
                    ->references('id_usuario')->on('usuarios');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('ingresos_economicos')) {
            $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ingresos_economicos'
                AND REFERENCED_TABLE_NAME IS NOT NULL");
            foreach ($foreigns as $fk) {
                DB::statement('ALTER TABLE ingresos_economicos DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
            }
            Schema::dropIfExists('ingresos_economicos');
        }
    }
}
