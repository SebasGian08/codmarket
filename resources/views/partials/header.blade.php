@php
    // Definición de variables con valores por defecto o fallback
    $logoHeader = $empresa->logo_header ?? 'assets/images/logo.png';
    $height = $height ?? '45px';
    $numeroWhatsapp = $numeroWhatsapp ?? null;
    $numeroWhatsappUrl = $numeroWhatsappUrl ?? null;
@endphp

<header class="header_section header_type_1">
    <div class="header_content_wrap">
        <div class="container-fluid px-3 px-md-4">
            <div class="row align-items-center justify-content-between">
                
                {{-- Bloque del Logo y Acciones Rápidas Mobile/Tablet --}}
                <div class="col-6 col-lg-3">
                    <div class="brand_logo d-flex align-items-center" style="padding: 0 !important; margin: 0 !important;">
                        <a class="brand_link p-0 m-0 d-block" href="{{ route('home') }}">
                            <img src="{{ asset($logoHeader) }}" 
                                 alt="Logo {{ $empresa->nombre ?? config('app.name') }}"
                                 style="height: {{ $height }} !important; width: auto; object-fit: contain; display: block;">
                        </a>
                    </div>
                </div>

                {{-- Menú de Navegación Principal --}}
                <div class="col-lg-6 d-none d-lg-block">
                    <nav class="main_menu">
                        <ul class="ul_li_center clearfix">
                            <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">Inicio</a>
                            </li>
                            <li class="{{ request()->routeIs('productos*') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">Productos</a>
                            </li>
                            <li class="{{ request()->routeIs('nosotros') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">Nosotros</a>
                            </li>
                            <li class="{{ request()->routeIs('contacto') ? 'active' : '' }}">
                                <a href="{{ route('home') }}">Contacto</a>
                            </li>
                        </ul>
                    </nav>
                </div>

                {{-- Botones de Acción (Búsqueda, WhatsApp, Menú Móvil) --}}
                <div class="col-6 col-lg-3 text-right">
                    <ul class="mh_action_btns ul_li_right clearfix d-flex align-items-center justify-content-end mb-0">
                        @if($numeroWhatsappUrl)
                            <li class="header-whatsapp-item mr-2">
                                <a href="https://wa.me/{{ $numeroWhatsappUrl }}" 
                                   class="header-whatsapp d-inline-flex align-items-center btn btn-success btn-sm rounded-pill px-3"
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   aria-label="Contactar por WhatsApp">
                                    <i class="fab fa-whatsapp mr-1"></i>
                                    <span class="header-whatsapp-number font-weight-bold" data-number="{{ $numeroWhatsapp }}">
                                        Escríbenos
                                    </span>
                                </a>
                            </li>
                        @endif

                        <li class="mr-2">
                            <button type="button" 
                                    class="search_btn btn btn-light rounded-circle" 
                                    data-toggle="collapse" 
                                    data-target="#search_body_collapse"
                                    aria-expanded="false" 
                                    aria-controls="search_body_collapse"
                                    aria-label="Buscar">
                                <i class="fal fa-search"></i>
                            </button>
                        </li>

                        <li class="d-lg-none">
                            <button type="button" 
                                    class="mobile_menu_btn btn btn-light rounded-circle"
                                    aria-label="Abrir menú">
                                <i class="far fa-bars"></i>
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    {{-- Buscador Desplegable --}}
    <div class="collapse search_body_collapse" id="search_body_collapse">
        <div class="card card-body border-0 rounded-0 bg-light">
            <div class="container">
                <form action="{{ route('home') }}" method="GET" class="search_form">
                    <div class="input-group">
                        <input type="text" 
                               name="q" 
                               class="form-control border-right-0" 
                               placeholder="¿Qué estás buscando?..." 
                               value="{{ request('q') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fal fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
    /* Estilos de ajuste para eliminar espacios en blanco innecesarios en el logo */
    .brand_logo .brand_link {
        padding: 0 !important;
        margin: 0 !important;
        line-height: 1;
    }

    .brand_logo img {
        max-width: 100%;
        height: auto;
    }

    /* Estilo personalizado para el botón de WhatsApp si no usas una clase CSS externa */
    .header-whatsapp {
        background-color: #25d366;
        color: #ffffff !important;
        border: none;
        transition: background-color 0.3s ease;
    }

    .header-whatsapp:hover {
        background-color: #128c7e;
    }
</style>