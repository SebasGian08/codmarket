<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MakeVentaPagosCuentaNullable extends Migration
{
    public function up()
    {
        // El pago en efectivo no usa cuenta bancaria -> columna nullable
        DB::statement('ALTER TABLE venta_pagos MODIFY COLUMN id_cuenta_bancaria BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        // No revierte a NOT NULL para evitar pérdida de datos
    }
}
