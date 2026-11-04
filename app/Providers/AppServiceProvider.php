<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Service;
use App\Models\Empresa;
use App\Models\Configuracion;
use App\Models\Categoria;
use App\Models\FooterLogo;
use App\Models\DocumentoTipo;
use App\Models\Marca;
use App\Models\Venta;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Blade;
use App\Repositories\MarcaRepository;
use App\Repositories\MarcaRepositoryInterface;
use App\Services\MarcaService;
use App\Services\MarcaServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('path.public', function() {
            return base_path();
        });

        $this->app->bind(MarcaRepositoryInterface::class, MarcaRepository::class);
        $this->app->bind(MarcaServiceInterface::class, MarcaService::class);
    }

    public function boot(): void
    {
        if (\App::environment('production')) {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }

        \Carbon\Carbon::setLocale('es');

        View::composer('*', function ($view) {
            $config = Configuracion::pluck('valor', 'clave')->toArray();
            $view->with('services', Service::where('estado', 1)->get());
            $view->with('servicesMenu', Service::where('estado', 1)->get());
            $view->with('empresa', Empresa::first());
            $view->with('config', $config);
            $view->with('footerLogos', \Schema::hasTable('footer_logos')
                ? FooterLogo::where('estado', 1)->orderBy('orden', 'asc')->get()
                : collect());
            $view->with('documentosTipos', \Schema::hasTable('documentos_tipo')
                ? DocumentoTipo::where('estado', 1)->orderBy('id_documento_tipo')->get()
                : collect());
            $view->with('categorias', Categoria::whereNull('id_categoria_padre')
                ->with(['hijos' => function ($q) {
                    $q->where('estado', 1)->orderBy('orden', 'asc');
                }])
                ->where('estado', 1)
                ->orderBy('orden', 'asc')
                ->get());
        });

        View::composer('admin.partials.navbar', function ($view) {
            $ventasPendientesCierreHoy = 0;
            $fechaCierreHoy = now()->toDateString();

            if (auth()->check()) {
                $query = Venta::where('estado', 1)
                    ->where('estado_cobro', 'pendiente')
                    ->whereDate('created_at', $fechaCierreHoy);

                $usuario = auth()->user();
                $vendedor = Vendedor::where('id_usuario', $usuario->id_usuario)
                    ->where('estado', 1)
                    ->first();
                $nombreRol = strtolower(trim($usuario->rol->nombre ?? ''));
                $rolNormalizado = preg_replace('/[\s_-]+/', '', $nombreRol);
                $esAdministrador = in_array($rolNormalizado, ['admin', 'administrador', 'superadmin'], true);

                if ($vendedor && !$esAdministrador) {
                    $tiendasAsignadas = $vendedor->tiendas()
                        ->where('tiendas.estado', 1)
                        ->wherePivot('estado', 1)
                        ->pluck('tiendas.id_tienda');

                    $query->where('id_vendedor', $vendedor->id_vendedor)
                        ->whereIn('id_tienda', $tiendasAsignadas);
                }

                $ventasPendientesCierreHoy = $query->count();
            }

            $view->with(compact('ventasPendientesCierreHoy', 'fechaCierreHoy'));
        });

        View::composer('sections.contact-brand', function ($view) {
            if (!array_key_exists('marcas', $view->getData())) {
                $view->with('marcas', Marca::where('estado', 1)->orderBy('orden')->get());
            }
        });

        Blade::if('permiso', function($codigo){
            return \App\Helpers\PermisoHelper::tiene($codigo);
        });
    }
}
