<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('productos') || Schema::hasColumn('productos', 'orden')) {
            return;
        }

        Schema::table('productos', function (Blueprint $table) {
            $table->unsignedInteger('orden')->default(0)->after('id_producto');
        });

        $orden = 1;
        DB::table('productos')
            ->orderByDesc('id_producto')
            ->pluck('id_producto')
            ->each(function ($idProducto) use (&$orden) {
                DB::table('productos')
                    ->where('id_producto', $idProducto)
                    ->update(['orden' => $orden++]);
            });
    }

    public function down(): void
    {
        if (Schema::hasTable('productos') && Schema::hasColumn('productos', 'orden')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('orden');
            });
        }
    }
};
