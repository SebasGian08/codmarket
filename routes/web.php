<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

/* WEB */
use App\Http\Controllers\App\HomeController;
use App\Http\Controllers\App\ContactController;
use App\Http\Controllers\App\SubscriptionController;
use App\Http\Controllers\App\BlogController;
use App\Http\Controllers\App\ServiceController;
use App\Http\Controllers\App\PortafolioController;
use App\Http\Controllers\App\ProductoController;

/* SISTEMA ADMIN */
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\PortafolioController as AdminPortafolioController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\BannerPrincipalController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\MarcaController as AdminMarcaController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\Admin\ProductoVarianteController;
use App\Http\Controllers\Admin\ProductoImagenController;
use App\Http\Controllers\Admin\AtributoController;
use App\Http\Controllers\Admin\AtributoValorController;
use App\Http\Controllers\Admin\ProveedorController as AdminProveedorController;
use App\Http\Controllers\Admin\PromocionController as AdminPromocionController;
use App\Http\Controllers\Admin\TrabajoRealizadoController;
use App\Http\Controllers\Admin\RubroController;

App::setLocale('es');

/*
|--------------------------------------------------------------------------
| HOME (PÚBLICO)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [ContactController::class, 'store'])->name('contact.store');
Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe.store');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/servicios', [ServiceController::class, 'index'])->name('services.index');
Route::get('/servicios/{slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/mantenimiento', function () {return view('pages.errors.mantenimiento');})->name('mantenimiento');
Route::get('/portafolio', [PortafolioController::class, 'index'])->name('portafolio.index');
Route::get('/productos-shop', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/producto/{slug}', [ProductoController::class, 'show']) ->name('producto.show');
Route::get('/buscar-productos', [ProductoController::class, 'buscar'])->name('productos.buscar');
Route::get('/productos/categoria/{slug}', [ProductoController::class, 'categoria'])->name('productos.categoria');
Route::get('/nosotros', function () {return view('pages.nosotros.index');})->name('nosotros');

/*
|--------------------------------------------------------------------------
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    // LOGIN
    Route::get('/login', [LoginController::class, 'index'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/perfil', function () { 
        return view('profile');
    })->name('profile');
});

/*
|--------------------------------------------------------------------------
| PANEL ADMIN (PROTEGIDO)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // MÓDULO: USUARIOS (Sintaxis compatible)
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('admin.users.index');
        Route::get('/crear', [UsuarioController::class, 'create'])->name('admin.users.create');
        Route::post('/guardar', [UsuarioController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}/editar', [UsuarioController::class, 'edit'])->name('admin.users.edit');
        Route::put('/{user}/actualizar', [UsuarioController::class, 'update'])->name('admin.users.update');
        Route::delete('/{user}/eliminar', [UsuarioController::class, 'destroy'])->name('admin.users.destroy');
    });

    // MÓDULO: ROLES
   Route::prefix('roles')->group(function () {
        Route::get('/', [RolController::class, 'index'])->name('admin.roles.index');
        Route::post('/guardar', [RolController::class, 'store'])->name('admin.roles.store');
        Route::put('/{id}/actualizar', [RolController::class, 'update'])->name('admin.roles.update');
        Route::delete('/{id}/eliminar', [RolController::class, 'destroy'])->name('admin.roles.destroy');
    });
    
    // MÓDULO: BLOG
    Route::prefix('blogs')->group(function () {
        Route::get('/', [AdminBlogController::class, 'index'])->name('admin.blogs.index');
        Route::post('/guardar', [AdminBlogController::class, 'store'])->name('admin.blogs.store');
        Route::put('/{blog}/actualizar', [AdminBlogController::class, 'update'])->name('admin.blogs.update');
        Route::delete('/{blog}/eliminar', [AdminBlogController::class, 'destroy'])->name('admin.blogs.destroy');
    });

    // MÓDULO: SERVICIOS
    Route::prefix('servicios')->group(function () {
        Route::get('/', [AdminServiceController::class, 'index'])->name('admin.servicios.index');
        Route::post('/guardar', [AdminServiceController::class, 'store'])->name('admin.servicios.store');
        Route::put('/{service}/actualizar', [AdminServiceController::class, 'update'])->name('admin.servicios.update');
        Route::delete('/{service}/eliminar', [AdminServiceController::class, 'destroy'])->name('admin.servicios.destroy');
    });

    // MÓDULO: PORTAFOLIO
    Route::prefix('portafolios')->group(function () {
        Route::get('/', [AdminPortafolioController::class, 'index'])->name('admin.portafolios.index');
        Route::post('/guardar', [AdminPortafolioController::class, 'store'])->name('admin.portafolios.store');
        Route::put('/{id}/actualizar', [AdminPortafolioController::class, 'update'])->name('admin.portafolios.update');
        Route::delete('/{id}/eliminar', [AdminPortafolioController::class, 'destroy'])->name('admin.portafolios.destroy');
    });

    Route::prefix('contacts')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('admin.contacts.index');
        Route::post('/', [AdminContactController::class, 'store'])->name('admin.contacts.store');
        Route::put('/{id}/status', [AdminContactController::class, 'changeStatus'])->name('admin.contacts.changeStatus');
        Route::post('/{id}/seguimiento', [AdminContactController::class, 'storeSeguimiento'])->name('admin.contacts.seguimiento.store');
        Route::put('/{id}', [AdminContactController::class, 'update'])->name('admin.contacts.update');
    });

    Route::prefix('empresa')->group(function () {
        Route::get('/', [EmpresaController::class, 'index'])->name('admin.empresa.index');
        Route::post('/guardar', [EmpresaController::class, 'store'])->name('admin.empresa.store');
        Route::put('/{id}/actualizar', [EmpresaController::class, 'update'])->name('admin.empresa.update');
    });

    Route::prefix('configuracion')->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('admin.configuracion.index');
        Route::post('/actualizar', [ConfiguracionController::class, 'update'])->name('admin.config.update');
        Route::post('/store', [ConfiguracionController::class, 'store'])->name('admin.config.store');
    });

    Route::prefix('banners-principales')->group(function () {
        Route::get('/', [BannerPrincipalController::class, 'index'])->name('admin.banners.index');
        Route::post('/guardar', [BannerPrincipalController::class, 'store'])->name('admin.banners.store');
        Route::put('/{id}/actualizar', [BannerPrincipalController::class, 'update'])->name('admin.banners.update');
        Route::delete('/{id}/eliminar', [BannerPrincipalController::class, 'destroy'])->name('admin.banners.destroy');
    });

    Route::prefix('categorias')->group(function () {
        Route::get('/', [AdminCategoriaController::class, 'index'])->name('admin.categorias.index');
        Route::post('/guardar', [AdminCategoriaController::class, 'store'])->name('admin.categorias.store');
        Route::put('/{id}/actualizar', [AdminCategoriaController::class, 'update'])->name('admin.categorias.update');
        Route::delete('/{id}/eliminar', [AdminCategoriaController::class, 'destroy'])->name('admin.categorias.destroy');
    });

    Route::prefix('marcas')->group(function () {
        Route::get('/', [AdminMarcaController::class, 'index'])->name('admin.marcas.index');
        Route::post('/guardar', [AdminMarcaController::class, 'store'])->name('admin.marcas.store');
        Route::put('/{id}/actualizar', [AdminMarcaController::class, 'update'])->name('admin.marcas.update');
        Route::delete('/{id}/eliminar', [AdminMarcaController::class, 'destroy'])->name('admin.marcas.destroy');
    });

        Route::prefix('proveedores')->group(function () {
            Route::get('/', [AdminProveedorController::class, 'index'])->name('admin.proveedores.index');
        Route::post('/guardar', [AdminProveedorController::class, 'store'])->name('admin.proveedores.store');
        Route::put('/{id}/actualizar', [AdminProveedorController::class, 'update'])->name('admin.proveedores.update');
        Route::delete('/{id}/eliminar', [AdminProveedorController::class, 'destroy'])->name('admin.proveedores.destroy');
    });

    Route::prefix('productos')->group(function () {
        Route::get('/', [AdminProductoController::class, 'index'])->name('admin.productos.index');
        Route::post('/guardar', [AdminProductoController::class, 'store'])->name('admin.productos.store');
        Route::put('/{id}/actualizar', [AdminProductoController::class, 'update'])->name('admin.productos.update');
        Route::delete('/{id}/eliminar', [AdminProductoController::class, 'destroy'])->name('admin.productos.destroy');
        Route::get('/plantilla', [AdminProductoController::class, 'plantilla'])->name('admin.productos.plantilla');
        Route::post('/importar', [AdminProductoController::class, 'importar'])->name('admin.productos.importar');
    });

    // VARIANTES DE PRODUCTO
    Route::prefix('variantes')->group(function () {
        Route::get('/{producto}', [ProductoVarianteController::class, 'index'])->name('admin.variantes.index');
        Route::post('/guardar', [ProductoVarianteController::class, 'store'])->name('admin.variantes.store');
        Route::put('/{id}/actualizar', [ProductoVarianteController::class, 'update'])->name('admin.variantes.update');
        Route::delete('/{id}/eliminar', [ProductoVarianteController::class, 'destroy'])->name('admin.variantes.destroy');
    });

    Route::prefix('productos/{producto}/imagenes')->group(function () {
        Route::get('/', [ProductoImagenController::class, 'index'])->name('admin.producto_imagen.index');
        Route::post('/guardar', [ProductoImagenController::class, 'store'])->name('admin.producto_imagen.store');
        Route::put('/{id}', [ProductoImagenController::class, 'update'])->name('admin.producto_imagen.update');
        Route::delete('/{id}', [ProductoImagenController::class, 'destroy'])->name('admin.producto_imagen.destroy');
    });

    // ATRIBUTOS (ej: color, talla)
    Route::prefix('atributos')->group(function () {
        Route::get('/', [AtributoController::class, 'index'])->name('admin.atributos.index');
        Route::post('/guardar', [AtributoController::class, 'store'])->name('admin.atributos.store');
        Route::put('/{id}/actualizar', [AtributoController::class, 'update'])->name('admin.atributos.update');
        Route::delete('/{id}/eliminar', [AtributoController::class, 'destroy'])->name('admin.atributos.destroy');
    });

    // VALORES DE ATRIBUTOS (ej: rojo, azul, S, M, L)
    Route::prefix('atributos-valores')->group(function () {
        Route::get('/', [AtributoValorController::class, 'index'])->name('admin.atributos_valores.index');
        Route::post('/guardar', [AtributoValorController::class, 'store'])->name('admin.atributos_valores.store');
        Route::put('/{id}/actualizar', [AtributoValorController::class, 'update'])->name('admin.atributos_valores.update');
        Route::delete('/{id}/eliminar', [AtributoValorController::class, 'destroy'])->name('admin.atributos_valores.destroy');
    });

    Route::prefix('promociones')->group(function () {
        Route::get('/', [AdminPromocionController::class, 'index'])->name('admin.promociones.index');
        Route::post('/guardar', [AdminPromocionController::class, 'store'])->name('admin.promociones.store');
        Route::put('/{id}/actualizar', [AdminPromocionController::class, 'update'])->name('admin.promociones.update');
        Route::delete('/{id}/eliminar', [AdminPromocionController::class, 'destroy'])->name('admin.promociones.destroy');
    });

    Route::prefix('trabajos-realizados')->group(function () {
        Route::get('/', [TrabajoRealizadoController::class, 'index'])->name('admin.trabajos.index');
        Route::post('/guardar', [TrabajoRealizadoController::class, 'store'])->name('admin.trabajos.store');
       /*  Route::put('/{id}/actualizar', [TrabajoRealizadoController::class, 'update'])->name('admin.trabajos.update'); */
        Route::delete('/{id}/eliminar', [TrabajoRealizadoController::class, 'destroy'])->name('admin.trabajos.destroy');
    });

    Route::prefix('rubros')->group(function () {
        Route::get('/', [RubroController::class, 'index'])->name('admin.rubros.index');
        Route::post('/guardar', [RubroController::class, 'store'])->name('admin.rubros.store');
        Route::put('/{id}/actualizar', [RubroController::class, 'update'])->name('admin.rubros.update');
        Route::delete('/{id}/eliminar', [RubroController::class, 'destroy'])->name('admin.rubros.destroy');
    });
});