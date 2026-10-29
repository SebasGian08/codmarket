<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('productos')) {
            return;
        }

        if (!Schema::hasColumn('productos', 'id_usuario_registro')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->unsignedBigInteger('id_usuario_registro')->nullable()->after('estado');
            });

            Schema::table('productos', function (Blueprint $table) {
                $table->foreign('id_usuario_registro')
                    ->references('id_usuario')
                    ->on('usuarios')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('productos', 'id_usuario_modifica')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->unsignedBigInteger('id_usuario_modifica')->nullable()->after('id_usuario_registro');
            });

            Schema::table('productos', function (Blueprint $table) {
                $table->foreign('id_usuario_modifica')
                    ->references('id_usuario')
                    ->on('usuarios')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('productos')) {
            return;
        }

        $columns = array_filter([
            Schema::hasColumn('productos', 'id_usuario_registro') ? 'id_usuario_registro' : null,
            Schema::hasColumn('productos', 'id_usuario_modifica') ? 'id_usuario_modifica' : null,
        ]);

        if (!$columns) {
            return;
        }

        Schema::table('productos', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                $table->dropForeign([$column]);
            }

            $table->dropColumn($columns);
        });
    }
};
