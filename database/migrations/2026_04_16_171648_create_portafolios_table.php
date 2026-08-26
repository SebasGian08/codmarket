<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortafoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('portafolios', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Relación con services (opcional)
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id')
                ->references('id_service')
                ->on('services')
                ->nullOnDelete();

            // Información general
            $table->string('titulo');
            $table->string('slug')->unique();
            $table->string('cliente')->nullable();

            // Categoría general (web, marketing, software, facturación, etc.)
            $table->string('categoria');

            // Tipo de proyecto más flexible
            $table->enum('tipo', ['web', 'marketing', 'software', 'facturacion', 'app', 'otros'])
                  ->default('otros');

            // Descripción
            $table->text('descripcion')->nullable();

            // Imagen principal del portafolio
            $table->string('imagen')->nullable();

            // URL o demo
            $table->string('url_demo')->nullable();

            // Estado
            $table->boolean('estado')->default(1);

            // Soft delete (recomendado)
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portafolios');
    }
}
