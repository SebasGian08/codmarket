<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AjustarTablasEconomicas extends Migration
{
    private function colExists($table, $column)
    {
        return Schema::hasColumn($table, $column);
    }

    private function dropFk($table, $column)
    {
        $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . $table . "'
            AND COLUMN_NAME = '" . $column . "' AND REFERENCED_TABLE_NAME IS NOT NULL");
        foreach ($foreigns as $fk) {
            DB::statement('ALTER TABLE `' . $table . '` DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
        }
    }

    public function up()
    {
        // ---------- metodos_pagos.id_destino_pago ----------
        if (!Schema::hasTable('destinos_pago')) {
            throw new \RuntimeException('El maestro destinos_pago debe existir antes de relacionar metodos_pagos');
        }

        if (!$this->colExists('metodos_pagos', 'id_destino_pago')) {
            DB::statement('ALTER TABLE metodos_pagos ADD COLUMN id_destino_pago INT UNSIGNED NULL AFTER codigo');
        }

        $this->dropFk('metodos_pagos', 'id_destino_pago');

        // Asignar destino por defecto según el código del método (solo si está vacío).
        // Efectivo -> caja; los demás métodos bancarios -> cuenta.
        $destinoCaja = DB::table('destinos_pago')->where('codigo', 'caja')->first();
        $destinoCuenta = DB::table('destinos_pago')->where('codigo', 'cuenta')->first();

        $filas = DB::table('metodos_pagos')->get(['id_metodo_pago', 'codigo', 'id_destino_pago']);
        foreach ($filas as $fila) {
            if ($fila->id_destino_pago !== null) {
                continue;
            }
            $esEfectivo = strtolower((string) $fila->codigo) === 'efectivo';
            $idDestino = $esEfectivo ? optional($destinoCaja)->id_destino_pago : optional($destinoCuenta)->id_destino_pago;
            if ($idDestino) {
                DB::table('metodos_pagos')->where('id_metodo_pago', $fila->id_metodo_pago)
                    ->update(['id_destino_pago' => $idDestino]);
            }
        }

        DB::statement('ALTER TABLE metodos_pagos MODIFY COLUMN id_destino_pago INT UNSIGNED NULL');

        Schema::table('metodos_pagos', function (Blueprint $t) {
            $t->foreign('id_destino_pago')
                ->references('id_destino_pago')->on('destinos_pago')
                ->nullOnDelete();
        });

        // ---------- ventas.monto_recibido ----------
        if (!$this->colExists('ventas', 'monto_recibido')) {
            Schema::table('ventas', function (Blueprint $t) {
                $t->decimal('monto_recibido', 10, 2)->nullable()->after('total');
            });
        }

        // ---------- cajas.monto_diferencia (arqueo) ----------
        if (!$this->colExists('cajas', 'monto_diferencia')) {
            Schema::table('cajas', function (Blueprint $t) {
                $t->decimal('monto_diferencia', 12, 2)->nullable()->after('monto_cierre');
            });
        }
    }

    public function down()
    {
        $this->dropFk('metodos_pagos', 'id_destino_pago');
        if (Schema::hasColumn('metodos_pagos', 'id_destino_pago')) {
            Schema::table('metodos_pagos', function (Blueprint $t) {
                $t->dropColumn('id_destino_pago');
            });
        }
        if (Schema::hasColumn('ventas', 'monto_recibido')) {
            Schema::table('ventas', function (Blueprint $t) {
                $t->dropColumn('monto_recibido');
            });
        }
        if (Schema::hasColumn('cajas', 'monto_diferencia')) {
            Schema::table('cajas', function (Blueprint $t) {
                $t->dropColumn('monto_diferencia');
            });
        }
    }
}
