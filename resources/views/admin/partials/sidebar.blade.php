<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <img src="{{ $empresa && $empresa->logo_header ? asset($empresa->logo_header) : asset('assets/images/logo-principal-white.png') }}"
                    alt="logo" class="navbar-brand" height="20" />
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>

            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">

            <ul class="nav nav-secondary">
                <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Inicio</p>
                    </a>
                </li>

                @permiso('ventas.ver')
                <li class="nav-item {{ Request::is('admin/ventas') ? 'active' : '' }}">
                    <a href="{{ route('admin.ventas.index') }}">
                        <i class="fas fa-cart-plus"></i>
                        <p>Punto de Venta</p>
                    </a>
                </li>
                @endpermiso

                @php($puedeInventario = \App\Helpers\PermisoHelper::tiene('ventas.historial') || \App\Helpers\PermisoHelper::tiene('ventas.cerrar') || \App\Helpers\PermisoHelper::tiene('cajas.ver') || \App\Helpers\PermisoHelper::tiene('ingresos.ver') || \App\Helpers\PermisoHelper::tiene('gastos.ver') || \App\Helpers\PermisoHelper::tiene('transferencias.ver') || \App\Helpers\PermisoHelper::tiene('inventario.ver') || \App\Helpers\PermisoHelper::tiene('inventario.carga'))
                @if($puedeInventario)
                <li
                    class="nav-item {{ Request::is('admin/ventas/*') || Request::is('admin/ventas/cerrar-venta') || Request::is('admin/cajas*') || Request::is('admin/ingresos*') || Request::is('admin/gastos*') || Request::is('admin/transferencias*') || Request::is('admin/inventario*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#inventario">
                        <i class="fas fa-boxes"></i>
                        <p>Inventario</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('admin/ventas/*') || Request::is('admin/ventas/cerrar-venta') || Request::is('admin/cajas*') || Request::is('admin/ingresos*') || Request::is('admin/gastos*') || Request::is('admin/transferencias*') || Request::is('admin/inventario*') ? 'show' : '' }}"
                        id="inventario">
                        <ul class="nav nav-collapse">
                            @permiso('ventas.historial')
                            <li class="{{ Request::is('admin/ventas/historial') ? 'active' : '' }}">
                                <a href="{{ route('admin.ventas.historial') }}">
                                    <span class="sub-item">Historial de Ventas</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('ventas.cerrar')
                            <li class="{{ Request::is('admin/ventas/cerrar-venta') ? 'active' : '' }}">
                                <a href="{{ route('admin.ventas.cerrar') }}">
                                    <span class="sub-item">Cerrar Venta</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('cajas.ver')
                            <li class="{{ Request::is('admin/cajas*') ? 'active' : '' }}">
                                <a href="{{ route('admin.cajas.index') }}">
                                    <span class="sub-item">Cajas</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('ingresos.ver')
                            <li class="{{ Request::is('admin/ingresos*') ? 'active' : '' }}">
                                <a href="{{ route('admin.ingresos.index') }}">
                                    <span class="sub-item">Ingresos</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('gastos.ver')
                            <li class="{{ Request::is('admin/gastos*') ? 'active' : '' }}">
                                <a href="{{ route('admin.gastos.index') }}">
                                    <span class="sub-item">Gastos</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('transferencias.ver')
                            <li class="{{ Request::is('admin/transferencias*') ? 'active' : '' }}">
                                <a href="{{ route('admin.transferencias.index') }}">
                                    <span class="sub-item">Transferencias</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('inventario.ver')
                            <li class="{{ Request::is('admin/inventario*') ? 'active' : '' }}">
                                <a href="{{ route('admin.inventario.index') }}">
                                    <span class="sub-item">Stock por Tienda</span>
                                </a>
                            </li>
                            @endpermiso
                            @permiso('inventario.carga')
                            <li class="{{ Request::is('admin/inventario/carga*') ? 'active' : '' }}">
                                <a href="{{ route('admin.inventario.carga.index') }}">
                                    <span class="sub-item">Carga Masiva</span>
                                </a>
                            </li>
                            @endpermiso
                        </ul>
                    </div>
                </li>
                @endif

                <li
                    class="nav-item  {{ Request::is('admin/marcas*') || Request::is('admin/proveedores*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#productos">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Mis Productos</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('admin/marcas*') || Request::is('admin/proveedores*') ? 'show' : '' }}"
                        id="productos">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('admin/categorias*') ? 'active' : '' }}">
                                <a href="{{ route('admin.categorias.index') }}">
                                    <span class="sub-item">Categorías</span>
                                </a>
                            </li>

                            <li class="{{ Request::is('admin/marcas*') ? 'active' : '' }}">
                                <a href="{{ route('admin.marcas.index') }}">
                                    <span class="sub-item">Marcas</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('admin/proveedores*') ? 'active' : '' }}">
                                <a href="{{ route('admin.proveedores.index') }}">
                                    <span class="sub-item">Proveedores</span>
                                </a>
                            </li>
                            @permiso('productos.ver')
                            <li>
                                <a href="{{route('admin.productos.index')}}">
                                    <span class="sub-item">
                                        Productos
                                    </span>
                                </a>
                            </li>
                            <li class="{{ Request::is('admin/productos/carga*') ? 'active' : '' }}">
                                <a href="{{ route('admin.productos.carga.index') }}">
                                    <span class="sub-item">Carga Masiva</span>
                                </a>
                            </li>
                            @endpermiso
                            <li class="{{ Request::is('admin/atributos*') ? 'active' : '' }}">
                                <a href="{{ route('admin.atributos.index') }}">
                                    <span class="sub-item">Atributos</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li
                    class="nav-item {{ Request::is('admin/empresa*') || Request::is('admin/usuarios*') || Request::is('admin/roles*') || Request::is('admin/tiendas*') || Request::is('admin/clientes*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#mantenimiento">
                        <i class="fas fa-cogs"></i>
                        <p>Configuración</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ Request::is('admin/empresa*') || Request::is('admin/usuarios*') || Request::is('admin/roles*') || Request::is('admin/tiendas*') || Request::is('admin/clientes*') || Request::is('admin/vendedores*') ? 'show' : '' }}"
                        id="mantenimiento">
                        <ul class="nav nav-collapse">

                            @permiso('empresa.ver')
                            <li class="{{ Request::is('admin/empresa*') ? 'active' : '' }}">
                                <a href="{{ route('admin.empresa.index') }}">
                                    <span class="sub-item">Empresa</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('tiendas.ver')
                            <li class="{{ Request::is('admin/tiendas*') ? 'active' : '' }}">
                                <a href="{{ route('admin.tiendas.index') }}">
                                    <span class="sub-item">Tiendas</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('clientes.ver')
                            <li class="{{ Request::is('admin/clientes*') ? 'active' : '' }}">
                                <a href="{{ route('admin.clientes.index') }}">
                                    <span class="sub-item">Clientes</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('vendedores.ver')
                            <li class="{{ Request::is('admin/vendedores*') ? 'active' : '' }}">
                                <a href="{{ route('admin.vendedores.index') }}">
                                    <span class="sub-item">Vendedores</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('usuarios.ver')
                            <li class="{{ Request::is('admin/usuarios*') ? 'active':'' }}">
                                <a href="{{ route('admin.users.index') }}">
                                    <span class="sub-item">
                                        Usuarios
                                    </span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('permisos.ver')
                            <li class="{{ Request::is('admin/permisos*') ? 'active':'' }}">
                                <a href="{{ route('admin.permisos.index') }}">
                                    <span class="sub-item">
                                        Permisos
                                    </span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('roles.ver')
                            <li>
                                <a href="{{route('admin.roles.index')}}">
                                    <span class="sub-item">
                                        Roles
                                    </span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('configuracion.ver')
                            <li class="{{ Request::is('admin/configuracion*') ? 'active' : '' }}">
                                <a href="{{ route('admin.configuracion.index') }}">
                                    <span class="sub-item">Configuración</span>
                                </a>
                            </li>
                            @endpermiso
                        </ul>
                    </div>
                </li>

                <li class="nav-item 
                    {{ Request::is('admin/blogs*') || 
                    Request::is('admin/servicios*') || 
                    Request::is('admin/portafolios*') || 
                    Request::is('admin/contacts*') || 
                    Request::is('admin/banners-principales*') || 
                    Request::is('admin/preguntas-frecuentes*') ? 'active' : '' }}">

                    <a data-bs-toggle="collapse" href="#tiendaVirtual">
                        <i class="fas fa-store"></i>
                        <p>Web</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse 
                        {{ Request::is('admin/blogs*') || 
                        Request::is('admin/servicios*') || 
                        Request::is('admin/portafolios*') || 
                        Request::is('admin/contacts*') || 
                        Request::is('admin/banners-principales*') || 
                        Request::is('admin/preguntas-frecuentes*') ? 'show' : '' }}" id="tiendaVirtual">

                        <ul class="nav nav-collapse">

                            @permiso('blogs.ver')
                            <li class="{{ Request::is('admin/blogs*') ? 'active' : '' }}">
                                <a href="{{ route('admin.blogs.index') }}">
                                    <span class="sub-item">Blog</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('servicios.ver')
                            <li class="{{ Request::is('admin/servicios*') ? 'active' : '' }}">
                                <a href="{{ route('admin.servicios.index') }}">
                                    <span class="sub-item">Servicios</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('portafolios.ver')
                            <li class="{{ Request::is('admin/portafolios*') ? 'active' : '' }}">
                                <a href="{{ route('admin.portafolios.index') }}">
                                    <span class="sub-item">Portafolio</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('contacts.ver')
                            <li class="{{ Request::is('admin/contacts*') ? 'active' : '' }}">
                                <a href="{{ route('admin.contacts.index') }}">
                                    <span class="sub-item">Contactos</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('banners.ver')
                            <li class="{{ Request::is('admin/banners-principales*') ? 'active' : '' }}">
                                <a href="{{ route('admin.banners.index') }}">
                                    <span class="sub-item">Banners</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('promociones.ver')
                            <li class="{{ Request::is('admin/promociones*') ? 'active' : '' }}">
                                <a href="{{ route('admin.promociones.index') }}">
                                    <span class="sub-item">Promociones</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('trabajos.ver')
                            <li class="{{ Request::is('admin/trabajos-realizados*') ? 'active' : '' }}">
                                <a href="{{ route('admin.trabajos.index') }}">
                                    <span class="sub-item">Trabajos Realizados</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('rubros.ver')
                            <li class="{{ Request::is('admin/rubros*') ? 'active' : '' }}">
                                <a href="{{ route('admin.rubros.index') }}">
                                    <span class="sub-item">Rubros</span>
                                </a>
                            </li>
                            @endpermiso

                            @permiso('preguntas.ver')
                            <li class="{{ Request::is('admin/preguntas-frecuentes*') ? 'active' : '' }}">
                                <a href="{{ route('admin.preguntas.index') }}">
                                    <span class="sub-item">Preguntas Frecuentes</span>
                                </a>
                            </li>
                            @endpermiso


                        </ul>
                    </div>
                </li>

            </ul>

        </div>
    </div>
</div>

<style>
.nav-collapse li {
    list-style: none !important;
}

.nav-collapse li a .sub-item::before {
    content: none !important;
}

.nav-collapse li a .sub-item {
    padding-left: 20px;
    position: relative;
}

.nav-collapse li a .sub-item::after {
    content: "›";
    position: absolute;
    left: 5px;
    color: #666;
    font-size: 14px;
}
</style>