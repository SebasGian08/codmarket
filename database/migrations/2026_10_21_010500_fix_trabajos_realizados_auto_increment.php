<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('trabajos_realizados')) {
            return;
        }

        $primaryKey = DB::select(
            'SHOW INDEX FROM trabajos_realizados WHERE Key_name = ?',
            ['PRIMARY']
        );

        if ($primaryKey) {
            DB::statement(
                'ALTER TABLE trabajos_realizados MODIFY id_trabajos_realizados BIGINT NOT NULL AUTO_INCREMENT'
            );

            return;
        }

        DB::statement(
            'ALTER TABLE trabajos_realizados
            MODIFY id_trabajos_realizados BIGINT NOT NULL AUTO_INCREMENT,
            ADD PRIMARY KEY (id_trabajos_realizados)'
        );
    }

    public function down(): void
    {
        if (!Schema::hasTable('trabajos_realizados')) {
            return;
        }

        $primaryKey = DB::select(
            'SHOW INDEX FROM trabajos_realizados WHERE Key_name = ?',
            ['PRIMARY']
        );

        if ($primaryKey) {
            DB::statement(
                'ALTER TABLE trabajos_realizados
                MODIFY id_trabajos_realizados BIGINT NOT NULL,
                DROP PRIMARY KEY'
            );
        }
    }
};
