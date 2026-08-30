<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearTransferenciasDinero extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('transferencias_dinero')) {
            Schema::create('transferencias_dinero', function (Blueprint $table) {
                $table->bigIncrements('id_transferencia_dinero');
                $table->string('numero', 30)->unique();
                $table->unsignedInteger('correlativo');

                $table->unsignedBigInteger('id_tienda');

                // Origen: caja XOR cuenta
                $table->unsignedBigInteger('id_caja_origen')->nullable();
                $table->unsignedBigInteger('id_cuenta_origen')->nullable();

                // Destino: caja XOR cuenta
                $table->unsignedBigInteger('id_caja_destino')->nullable();
                $table->unsignedBigInteger('id_cuenta_destino')->nullable();

                $table->unsignedBigInteger('id_usuario');
                $table->date('fecha');
                $table->decimal('monto', 12, 2);
                $table->string('moneda', 10)->default('PEN');
                $table->text('observacion')->nullable();
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });

            Schema::table('transferencias_dinero', function (Blueprint $table) {
                $table->foreign('id_tienda')->references('id_tienda')->on('tiendas');
                $table->foreign('id_caja_origen')->references('id_caja')->on('cajas')->nullOnDelete();
                $table->foreign('id_cuenta_origen')->references('id_cuenta_bancaria')->on('cuentas_bancarias')->nullOnDelete();
                $table->foreign('id_caja_destino')->references('id_caja')->on('cajas')->nullOnDelete();
                $table->foreign('id_cuenta_destino')->references('id_cuenta_bancaria')->on('cuentas_bancarias')->nullOnDelete();
                $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('transferencias_dinero')) {
            $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'transferencias_dinero'
                AND REFERENCED_TABLE_NAME IS NOT NULL");
            foreach ($foreigns as $fk) {
                DB::statement('ALTER TABLE transferencias_dinero DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
            }
            Schema::dropIfExists('transferencias_dinero');
        }
    }
}
