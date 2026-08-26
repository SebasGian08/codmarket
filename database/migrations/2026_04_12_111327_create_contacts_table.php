<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id_contact');

            $table->string('nombres');
            $table->string('apellidos');
            $table->string('email');
            $table->string('telefono', 20);

            // relaciones maestras
            $table->unsignedBigInteger('id_service')->nullable();
            $table->unsignedBigInteger('id_source')->nullable();
            $table->unsignedBigInteger('id_status')->nullable();
            $table->unsignedBigInteger('id_priority')->nullable();

            $table->text('mensaje');

            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->foreign('id_service')->references('id_service')->on('services');
            $table->foreign('id_source')->references('id_source')->on('contact_sources');
            $table->foreign('id_status')->references('id_status')->on('contact_statuses');
            $table->foreign('id_priority')->references('id_priority')->on('priorities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
