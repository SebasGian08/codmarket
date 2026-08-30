<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CrearCuentasTipoCuenta extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('cuentas_tipo_cuenta')) {
            Schema::create('cuentas_tipo_cuenta', function (Blueprint $table) {
                $table->increments('id_tipo_cuenta');
                $table->string('nombre', 100);
                $table->tinyInteger('estado')->default(1);
                $table->timestamps();
            });
        }

        if (!DB::table('cuentas_tipo_cuenta')->exists()) {
            DB::table('cuentas_tipo_cuenta')->insert([
                ['nombre' => 'Ahorros', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Corriente', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Empresarial', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('cuentas_tipo_cuenta');
    }
}
