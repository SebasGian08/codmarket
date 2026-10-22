<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddInventoryMovementTypesForAdjustmentsAndReturns extends Migration
{
    private const TYPES = [
        ['codigo' => 'ajuste_suma', 'nombre' => 'Ajuste suma', 'signo' => '+'],
        ['codigo' => 'ajuste_resta', 'nombre' => 'Ajuste resta', 'signo' => '-'],
        ['codigo' => 'devolucion', 'nombre' => 'Devolución', 'signo' => '+'],
    ];

    public function up()
    {
        foreach (self::TYPES as $type) {
            $existing = DB::table('movimientos_tipo')
                ->where('codigo', $type['codigo'])
                ->exists();

            if ($existing) {
                DB::table('movimientos_tipo')
                    ->where('codigo', $type['codigo'])
                    ->update([
                        'nombre' => $type['nombre'],
                        'signo' => $type['signo'],
                        'estado' => 1,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('movimientos_tipo')->insert(array_merge($type, [
                    'estado' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down()
    {
        $ids = DB::table('movimientos_tipo')
            ->whereIn('codigo', array_column(self::TYPES, 'codigo'))
            ->pluck('id_tipo_movimiento');

        if (DB::table('movimientos')->whereIn('id_tipo_movimiento', $ids)->exists()) {
            throw new RuntimeException('No se pueden eliminar tipos de movimiento que ya tienen movimientos registrados.');
        }

        DB::table('movimientos_tipo')
            ->whereIn('codigo', array_column(self::TYPES, 'codigo'))
            ->delete();
    }
}
