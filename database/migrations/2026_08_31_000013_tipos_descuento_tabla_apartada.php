<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migración de ajuste: reemplaza el enum "tipo_descuento" en duro por una
 * tabla aparte (tipos_descuento). Idempotente: solo actúa si las columnas
 * enum aún existen (BD que ya tenía las migraciones 000010/000011).
 *
 * Para BDs nuevas, la tabla y las FK se crean directamente en 000010/000011;
 * esta migración no hace nada porque no encuentra las columnas enum.
 */
class TiposDescuentoTablaApartada extends Migration
{
    public function up()
    {
        // ============ 1) Crear el catálogo tipos_descuento ============
        if (!Schema::hasTable('tipos_descuento')) {
            Schema::create('tipos_descuento', function (Blueprint $table) {
                $table->bigIncrements('id_tipo_descuento');
                $table->string('codigo', 30)->unique();
                $table->string('nombre', 100);
                $table->string('descripcion', 255)->nullable();
                $table->boolean('estado')->default(1);
                $table->timestamps();
            });
        }

        if (DB::table('tipos_descuento')->count() === 0) {
            $now = now();
            DB::table('tipos_descuento')->insert([
                [
                    'codigo' => 'PORCENTAJE',
                    'nombre' => 'Porcentaje',
                    'descripcion' => 'Descuento expresado como porcentaje (%)',
                    'estado' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'codigo' => 'MONTO',
                    'nombre' => 'Monto',
                    'descripcion' => 'Descuento expresado como monto fijo (S/)',
                    'estado' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        // ============ 2) reglas_descuento: tipo_descuento -> id_tipo_descuento ============
        if (Schema::hasColumn('reglas_descuento', 'tipo_descuento')) {
            Schema::table('reglas_descuento', function (Blueprint $table) {
                $table->unsignedBigInteger('id_tipo_descuento')->nullable()->after('descripcion');
            });

            DB::statement(
                "UPDATE reglas_descuento r
                 SET r.id_tipo_descuento = (
                     SELECT td.id_tipo_descuento
                     FROM tipos_descuento td
                     WHERE td.codigo = r.tipo_descuento
                 )
                 WHERE r.tipo_descuento IS NOT NULL"
            );

            // Valores sin correspondencia -> Porcentaje (id 1)
            DB::table('reglas_descuento')
                ->whereNull('id_tipo_descuento')
                ->update([
                    'id_tipo_descuento' => DB::table('tipos_descuento')
                        ->where('codigo', 'PORCENTAJE')
                        ->value('id_tipo_descuento'),
                ]);

            Schema::table('reglas_descuento', function (Blueprint $table) {
                $table->foreign('id_tipo_descuento')
                    ->references('id_tipo_descuento')
                    ->on('tipos_descuento')
                    ->onDelete('restrict');
                $table->dropColumn('tipo_descuento');
            });
        }

        // ============ 3) ventas_detalle: tipo_descuento -> id_tipo_descuento ============
        if (Schema::hasColumn('ventas_detalle', 'tipo_descuento')) {
            Schema::table('ventas_detalle', function (Blueprint $table) {
                $table->unsignedBigInteger('id_tipo_descuento')->nullable()->after('id_motivo_descuento');
            });

            DB::statement(
                "UPDATE ventas_detalle d
                 SET d.id_tipo_descuento = (
                     SELECT td.id_tipo_descuento
                     FROM tipos_descuento td
                     WHERE td.codigo = d.tipo_descuento
                 )
                 WHERE d.tipo_descuento IS NOT NULL"
            );

            Schema::table('ventas_detalle', function (Blueprint $table) {
                $table->foreign('id_tipo_descuento')
                    ->references('id_tipo_descuento')
                    ->on('tipos_descuento')
                    ->onDelete('set null');
                $table->dropColumn('tipo_descuento');
            });
        }
    }

    public function down()
    {
        // Revertir ventas_detalle
        if (Schema::hasColumn('ventas_detalle', 'id_tipo_descuento')
            && !Schema::hasColumn('ventas_detalle', 'tipo_descuento')) {
            Schema::table('ventas_detalle', function (Blueprint $table) {
                $table->dropForeign(['id_tipo_descuento']);
                $table->string('tipo_descuento', 20)->nullable()->after('id_motivo_descuento');
            });
            DB::statement(
                "UPDATE ventas_detalle d
                 SET d.tipo_descuento = (SELECT td.codigo FROM tipos_descuento td WHERE td.id_tipo_descuento = d.id_tipo_descuento)"
            );
            Schema::table('ventas_detalle', function (Blueprint $table) {
                $table->dropColumn('id_tipo_descuento');
            });
        }

        // Revertir reglas_descuento
        if (Schema::hasColumn('reglas_descuento', 'id_tipo_descuento')
            && !Schema::hasColumn('reglas_descuento', 'tipo_descuento')) {
            Schema::table('reglas_descuento', function (Blueprint $table) {
                $table->dropForeign(['id_tipo_descuento']);
                $table->string('tipo_descuento', 20)->default('PORCENTAJE')->after('descripcion');
            });
            DB::statement(
                "UPDATE reglas_descuento r
                 SET r.tipo_descuento = (SELECT td.codigo FROM tipos_descuento td WHERE td.id_tipo_descuento = r.id_tipo_descuento)"
            );
            Schema::table('reglas_descuento', function (Blueprint $table) {
                $table->dropColumn('id_tipo_descuento');
            });
        }

        Schema::dropIfExists('tipos_descuento');
    }
}
