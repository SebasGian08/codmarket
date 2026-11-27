<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductosCatalogoConfiguracionTest extends TestCase
{
    private $originalDatabaseDefault;

    protected function setUp(): void
    {
        parent::setUp();

        $this->originalDatabaseDefault = config('database.default');
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.foreign_key_constraints' => false,
            'session.driver' => 'array',
        ]);
        DB::purge('sqlite');

        Schema::connection('sqlite')->create('configuraciones', function (Blueprint $table) {
            $table->increments('id_configuracion');
            $table->string('clave');
            $table->text('valor')->nullable();
        });
    }

    protected function tearDown(): void
    {
        DB::disconnect('sqlite');
        DB::purge('sqlite');
        config(['database.default' => $this->originalDatabaseDefault]);

        parent::tearDown();
    }

    public function testCatalogoRedirigeAlInicioCuandoProductosNoSeMuestranEnInicio()
    {
        DB::table('configuraciones')->insert([
            'clave' => 'home_mostrar_productos',
            'valor' => '0',
        ]);

        $this->get(route('productos.index'))
            ->assertRedirect(route('home'));
    }
}
