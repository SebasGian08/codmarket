<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\VentaController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AdminPermissionMiddleware;
use App\Http\Middleware\CheckConfiguracionMantenimiento;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ExposedVentaController extends VentaController
{
    public function authorizedVentaIds()
    {
        return $this->ventasAutorizadas()->orderBy('id_venta')->pluck('id_venta')->all();
    }

    public function findAuthorizedVenta($id)
    {
        return $this->ventaAutorizada($id);
    }
}

class AuthorizationRegressionTest extends TestCase
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

        Schema::connection('sqlite')->create('vendedores', function (Blueprint $table) {
            $table->increments('id_vendedor');
            $table->unsignedInteger('id_usuario');
            $table->boolean('estado');
        });
        Schema::connection('sqlite')->create('tiendas', function (Blueprint $table) {
            $table->increments('id_tienda');
            $table->string('nombre')->nullable();
            $table->boolean('estado');
        });
        Schema::connection('sqlite')->create('vendedores_tiendas', function (Blueprint $table) {
            $table->increments('id_vendedor_tienda');
            $table->unsignedInteger('id_vendedor');
            $table->unsignedInteger('id_tienda');
            $table->boolean('estado');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
        });
        Schema::connection('sqlite')->create('ventas', function (Blueprint $table) {
            $table->increments('id_venta');
            $table->unsignedInteger('id_vendedor');
            $table->unsignedInteger('id_tienda');
        });
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

    public function testNonAdministratorsCannotMutateUsersRolesOrPermissions()
    {
        $user = $this->userWithRole('Vendedor');
        $this->actingAs($user);
        $this->withoutMiddleware([
            CheckConfiguracionMantenimiento::class,
            VerifyCsrfToken::class,
        ]);
        $this->withoutExceptionHandling();

        $mutations = [
            ['POST', route('admin.users.store')],
            ['PUT', route('admin.users.update', 1)],
            ['DELETE', route('admin.users.destroy', 1)],
            ['POST', route('admin.roles.store')],
            ['PUT', route('admin.roles.update', 1)],
            ['DELETE', route('admin.roles.destroy', 1)],
            ['POST', route('admin.roles.permisos.guardar', 1)],
            ['POST', route('admin.permisos.store')],
            ['PUT', route('admin.permisos.update', 1)],
            ['DELETE', route('admin.permisos.destroy', 1)],
            ['GET', route('admin.permisos.estado', 1)],
        ];

        foreach ($mutations as [$method, $url]) {
            try {
                $this->call($method, $url);
                $this->fail($method . ' ' . $url . ' should be forbidden.');
            } catch (HttpException $exception) {
                $this->assertSame(403, $exception->getStatusCode());
            }
        }
    }

    public function testAdministratorAndExistingPermissionCanPassTheirAuthorizationChecks()
    {
        $admin = $this->userWithRole('Super Admin');
        $this->actingAs($admin);

        $adminResponse = app(AdminMiddleware::class)->handle(
            Request::create('/admin/roles'),
            fn () => new Response('allowed')
        );
        $this->assertSame('allowed', $adminResponse->getContent());

        $role = new class {
            public $nombre = 'Gestor';

            public function permisos()
            {
                return new class {
                    public function where($column, $permission)
                    {
                        return $this;
                    }

                    public function exists()
                    {
                        return true;
                    }
                };
            }
        };
        $this->actingAs($this->userWithRoleObject($role));

        $permissionResponse = app(AdminPermissionMiddleware::class)->handle(
            Request::create('/admin/usuarios'),
            fn () => new Response('allowed'),
            'usuarios.ver'
        );
        $this->assertSame('allowed', $permissionResponse->getContent());
    }

    public function testSaleLookupIsLimitedToTheCallersActiveStoreAssignments()
    {
        DB::table('vendedores')->insert([
            'id_vendedor' => 20,
            'id_usuario' => 10,
            'estado' => 1,
        ]);
        DB::table('tiendas')->insert([
            ['id_tienda' => 30, 'nombre' => 'Asignada', 'estado' => 1],
            ['id_tienda' => 31, 'nombre' => 'No asignada', 'estado' => 1],
            ['id_tienda' => 32, 'nombre' => 'Deshabilitada', 'estado' => 0],
        ]);
        DB::table('vendedores_tiendas')->insert([
            [
                'id_vendedor_tienda' => 1,
                'id_vendedor' => 20,
                'id_tienda' => 30,
                'estado' => 1,
                'fecha_inicio' => null,
                'fecha_fin' => null,
            ],
            [
                'id_vendedor_tienda' => 2,
                'id_vendedor' => 20,
                'id_tienda' => 31,
                'estado' => 0,
                'fecha_inicio' => null,
                'fecha_fin' => null,
            ],
            [
                'id_vendedor_tienda' => 3,
                'id_vendedor' => 20,
                'id_tienda' => 32,
                'estado' => 1,
                'fecha_inicio' => null,
                'fecha_fin' => null,
            ],
        ]);
        DB::table('ventas')->insert([
            ['id_venta' => 100, 'id_vendedor' => 20, 'id_tienda' => 30],
            ['id_venta' => 101, 'id_vendedor' => 20, 'id_tienda' => 31],
            ['id_venta' => 102, 'id_vendedor' => 20, 'id_tienda' => 32],
            ['id_venta' => 103, 'id_vendedor' => 21, 'id_tienda' => 30],
        ]);

        $this->actingAs($this->userWithRole('Vendedor', 10));
        $controller = (new \ReflectionClass(ExposedVentaController::class))
            ->newInstanceWithoutConstructor();

        $this->assertSame([100], $controller->authorizedVentaIds());
        $this->assertSame(100, $controller->findAuthorizedVenta(100)->id_venta);
        try {
            $controller->findAuthorizedVenta(101);
            $this->fail('A sale outside the active store assignments must not be readable.');
        } catch (ModelNotFoundException $exception) {
            $this->assertTrue(true);
        }

        $this->actingAs($this->userWithRole('Administrador', 10));
        $this->assertSame([100, 101, 102, 103], $controller->authorizedVentaIds());
    }

    private function userWithRole(string $roleName, int $userId = 1): Usuario
    {
        $role = new Rol();
        $role->forceFill(['nombre' => $roleName]);

        return $this->userWithRoleObject($role, $userId);
    }

    private function userWithRoleObject($role, int $userId = 1): Usuario
    {
        $user = new Usuario();
        $user->forceFill(['id_usuario' => $userId]);
        $user->setRelation('rol', $role);

        return $user;
    }
}
