<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamaciones_tipo', function (Blueprint $table) {
            $table->bigIncrements('id_reclamacion_tipo');
            $table->string('nombre', 100);
            $table->string('codigo', 20)->unique('uk_reclamaciones_tipo_codigo');
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        DB::table('reclamaciones_tipo')->insert([
            ['nombre' => 'Reclamo', 'codigo' => 'RECLAMO', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Queja', 'codigo' => 'QUEJA', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->bigIncrements('id_reclamacion');
            $table->unsignedBigInteger('id_documento_tipo');
            $table->string('numero_documento', 20);
            $table->string('correo_electronico', 150);
            $table->string('numero_celular', 20);
            $table->enum('tipo_bien', ['PRODUCTO', 'SERVICIO']);
            $table->decimal('monto_reclamado', 12, 2)->default(0.00);
            $table->text('detalle_bien');
            $table->unsignedBigInteger('id_reclamacion_tipo');
            $table->text('detalle_reclamacion');
            $table->text('pedido_consumidor');
            $table->boolean('acepta_datos')->default(0);
            $table->boolean('estado')->default(1);
            $table->timestamps();

            $table->index(['id_documento_tipo', 'numero_documento'], 'idx_documento');
            $table->index('id_reclamacion_tipo', 'idx_tipo_reclamacion');
            $table->index('created_at', 'idx_fecha');
            $table->foreign('id_documento_tipo', 'fk_reclamaciones_documentos')
                ->references('id_documento_tipo')
                ->on('documentos_tipo');
            $table->foreign('id_reclamacion_tipo', 'fk_reclamaciones_tipo')
                ->references('id_reclamacion_tipo')
                ->on('reclamaciones_tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamaciones');
        Schema::dropIfExists('reclamaciones_tipo');
    }
};
