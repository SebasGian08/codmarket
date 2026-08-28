<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMovimientosTipoTable extends Migration
{
    public function up()
    {
        Schema::create('movimientos_tipo', function (Blueprint $table) {
            $table->bigIncrements('id_tipo_movimiento');
            $table->string('codigo', 60)->unique();
            $table->string('nombre', 120);
            $table->string('signo', 1)->default('+'); // + entrada | - salida
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        $tipos = [
            ['ingreso', 'Ingreso', '+'],
            ['venta', 'Venta', '-'],
            ['transferencia_salida', 'Transferencia salida', '-'],
            ['transferencia_entrada', 'Transferencia entrada', '+'],
            ['ajuste', 'Ajuste', '+'],
        ];

        foreach ($tipos as $i => $t) {
            DB::table('movimientos_tipo')->insert([
                'id_tipo_movimiento' => $i + 1,
                'codigo' => $t[0],
                'nombre' => $t[1],
                'signo' => $t[2],
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('movimientos_tipo');
    }
}
