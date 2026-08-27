<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Service;
use App\Models\Empresa;
use App\Models\Configuracion;
use App\Models\Categoria;
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
            $view->with('categorias', Categoria::where('estado', 1)->get());
        });

        Blade::if('permiso', function($codigo){
            return \App\Helpers\PermisoHelper::tiene($codigo);
        });
    }
}
