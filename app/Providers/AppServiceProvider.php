<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Service;
use App\Models\Empresa;
use App\Models\Configuracion;
use App\Models\Categoria;
//use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path();
        });
    }

    public function boot()
    {   
        if (\App::environment('production')) {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }

        Schema::defaultStringLength(200);
        \Carbon\Carbon::setLocale('es');

        View::composer('*', function ($view) {
            $config = Configuracion::pluck('valor', 'clave')->toArray();

            $view->with('servicesMenu', Service::where('estado', 1)->get());
            $view->with('empresa', Empresa::first());
            $view->with('config', $config);
            $view->with('categorias', Categoria::where('estado', 1)->get());
        });

    }
}