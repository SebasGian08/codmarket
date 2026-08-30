<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ReestructurarCuentasBancarias extends Migration
{
    private function hasColumn($column)
    {
        return Schema::hasColumn('cuentas_bancarias', $column);
    }

    public function up()
    {
        // ---------- 1. COLUMNAS DESCRIPTIVAS ----------
        // nombre -> nombre_banco (MariaDB usa CHANGE COLUMN)
        if ($this->hasColumn('nombre') && !$this->hasColumn('nombre_banco')) {
            DB::statement('ALTER TABLE cuentas_bancarias CHANGE COLUMN nombre nombre_banco VARCHAR(100) NOT NULL');
        }

        // saldo -> saldo_actual
        if ($this->hasColumn('saldo') && !$this->hasColumn('saldo_actual')) {
            DB::statement('ALTER TABLE cuentas_bancarias CHANGE COLUMN saldo saldo_actual DECIMAL(12,2) NOT NULL DEFAULT 0');
        }

        // Agregar numero_cuenta, titular, saldo_actual si faltan (SQL crudo)
        if (!$this->hasColumn('numero_cuenta')) {
            DB::statement('ALTER TABLE cuentas_bancarias ADD COLUMN numero_cuenta VARCHAR(50) NULL AFTER nombre_banco');
        }
        if (!$this->hasColumn('titular')) {
            DB::statement('ALTER TABLE cuentas_bancarias ADD COLUMN titular VARCHAR(150) NULL AFTER numero_cuenta');
        }
        if (!$this->hasColumn('saldo_actual')) {
            DB::statement('ALTER TABLE cuentas_bancarias ADD COLUMN saldo_actual DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER titular');
        } elseif ($this->hasColumn('saldo')) {
            // copiar saldo -> saldo_actual si saldo_actual quedara en 0
            DB::statement('UPDATE cuentas_bancarias SET saldo_actual = saldo WHERE saldo_actual = 0');
        }

        // ---------- 2. TIPO DE CUENTA -> MAESTRO ----------
        // renombrar tipo -> tipo_cuenta
        if ($this->hasColumn('tipo') && !$this->hasColumn('tipo_cuenta')) {
            DB::statement('ALTER TABLE cuentas_bancarias CHANGE COLUMN tipo tipo_cuenta VARCHAR(50) NULL');
        }

        // si no existe tipo_cuenta, crearla como FK nullable
        if (!$this->hasColumn('tipo_cuenta')) {
            DB::statement('ALTER TABLE cuentas_bancarias ADD COLUMN tipo_cuenta INT UNSIGNED NULL AFTER nombre_banco');
        }

        // mapear valores a ids del maestro cuentas_tipo_cuenta
        $filas = DB::table('cuentas_bancarias')->get(['id_cuenta_bancaria', 'tipo_cuenta']);
        foreach ($filas as $fila) {
            $valor = $fila->tipo_cuenta;
            if ($valor === null || $valor === '') {
                DB::table('cuentas_bancarias')->where('id_cuenta_bancaria', $fila->id_cuenta_bancaria)->update(['tipo_cuenta' => 1]);
                continue;
            }
            if (is_numeric($valor)) {
                continue;
            }
            // valor es un string (nombre del tipo): buscar o crear en el maestro
            $tipo = DB::table('cuentas_tipo_cuenta')->where('nombre', $valor)->first();
            $idTipo = $tipo ? $tipo->id_tipo_cuenta
                : DB::table('cuentas_tipo_cuenta')->insertGetId([
                    'nombre' => $valor, 'estado' => 1,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            DB::table('cuentas_bancarias')->where('id_cuenta_bancaria', $fila->id_cuenta_bancaria)->update(['tipo_cuenta' => $idTipo]);
        }

        // forzar tipo_cuenta como INT UNSIGNED NULL (SQL crudo)
        DB::statement('ALTER TABLE cuentas_bancarias MODIFY COLUMN tipo_cuenta INT UNSIGNED NULL');

        // ---------- 3. FOREIGN KEYS ----------
        // eliminar FK previa si existe (no blockea por nombre)
        $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'cuentas_bancarias'
            AND COLUMN_NAME = 'tipo_cuenta' AND REFERENCED_TABLE_NAME IS NOT NULL");
        foreach ($foreigns as $fk) {
            DB::statement('ALTER TABLE cuentas_bancarias DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
        }

        // crear FK si el maestro existe
        if (Schema::hasTable('cuentas_tipo_cuenta')) {
            Schema::table('cuentas_bancarias', function (Blueprint $t) {
                $t->foreign('tipo_cuenta')
                    ->references('id_tipo_cuenta')->on('cuentas_tipo_cuenta')
                    ->nullOnDelete();
            });
        }
    }

    public function down()
    {
        $foreigns = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'cuentas_bancarias'
            AND COLUMN_NAME = 'tipo_cuenta' AND REFERENCED_TABLE_NAME IS NOT NULL");
        foreach ($foreigns as $fk) {
            DB::statement('ALTER TABLE cuentas_bancarias DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
        }
    }
}
