<body class="home_furniture">

    @php
    $mostrarBlogs = $config['home_mostrar_blogs'] ?? 1;
    $mostrarServicios = $config['home_mostrar_servicios'] ?? 1;
    $mostrarProductos = $config['home_mostrar_productos'] ?? 1;
    $numeroWhatsapp = $empresa->whatsapp ?: $empresa->telefono;
    $numeroWhatsappUrl = preg_replace('/[^0-9]/', '', $numeroWhatsapp ?? '');
    @endphp

    <!-- Estilos específicos para la Opción 2 (Logo Centrado + Glassmorphism) -->
    <style>
        .header_option2 {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1200px;
            z-index: 999;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
            padding: 5px 30px;
        }

        .header_option2 .nav_container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .header_option2 .menu_left,
        .header_option2 .menu_right {
            display: flex;
            align-items: center;
            gap: 20px;
            list-style: none;
            margin: 0;
            padding: 0;
            flex: 1;
        }

        .header_option2 .menu_left {
            justify-content: flex-end;
            padding-right: 30px;
        }

        .header_option2 .menu_right {
            justify-content: flex-start;
            padding-left: 30px;
        }

        .header_option2 .menu_item_link {
            color: #2b2b2b;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .header_option2 .menu_item_link:hover,
        .header_option2 .menu_item_link.active-menu {
            color: #000000;
            font-weight: 700;
        }

        /* Logo Circular Centrado */
        .header_option2 .brand_logo_center {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .header_option2 .brand_logo_center img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            padding: 4px;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        /* Botón estilo Pill */
        .header_option2 .btn_pill {
            border: 1px solid rgba(0, 0, 0, 0.3);
            border-radius: 20px;
            padding: 6px 18px;
            font-size: 13px;
            font-weight: 600;
            color: #2b2b2b;
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .header_option2 .btn_pill:hover {
            background-color: #000000;
            color: #ffffff;
            border-color: #000000;
        }

        /* Ajuste móvil */
        @media (max-width: 991px) {
            .header_option2 .menu_left, 
            .header_option2 .menu_right {
                display: none;
            }
            .header_option2 .nav_container {
                justify-content: space-between;
                height: 60px;
            }
            .header_option2 .brand_logo_center {
                position: static;
                transform: none;
            }
            .header_option2 .brand_logo_center img {
                width: 50px;
                height: 50px;
            }
        }
    </style>

    <header class="header_section header_option2 clearfix">
        <div class="nav_container">

            <!-- BLOQUE IZQUIERDO: Navegación -->
            <ul class="menu_left d-none d-lg-flex">
                @if($mostrarProductos == 1)
                <li class="menu_item_has_child position-relative">
                    <a href="#!" class="menu_item_link {{ request()->routeIs('productos.categoria*') ? 'active-menu' : '' }}">
                        Productos <i class="fas fa-chevron-down ml-1 arrow_icon" style="font-size: 10px;"></i>
                    </a>
                    <ul class="submenu submenu_flat">
                        @foreach($categorias as $categoria)
                        <li>
                            <a href="{{ route('productos.categoria', $categoria->slug) }}">
                                @if($categoria->icono)
                                <i class="{{ $categoria->icono }} menu_subcat_icon"></i>
                                @endif
                                {{ $categoria->nombre }}
                            </a>
                            @if($categoria->hijos && $categoria->hijos->count())
                            <ul class="submenu_flat_children">
                                @foreach($categoria->hijos as $hijo)
                                <li>
                                    <a href="{{ route('productos.categoria', $hijo->slug) }}">
                                        @if($hijo->icono)
                                        <i class="{{ $hijo->icono }} menu_subcat_icon"></i>
                                        @endif
                                        {{ $hijo->nombre }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif

                @if($mostrarServicios == 1)
                <li class="menu_item_has_child position-relative">
                    <a href="#!" class="menu_item_link {{ request()->routeIs('services*') ? 'active-menu' : '' }}">
                        Servicios <i class="fas fa-chevron-down ml-1 arrow_icon" style="font-size: 10px;"></i>
                    </a>
                    <ul class="submenu">
                        @foreach($services as $service)
                        <li>
                            <a href="{{ route('services.show', $service->slug) }}">
                                {{ $service->nombre }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endif

                <li>
                    <a href="{{ route('nosotros') }}" class="menu_item_link {{ request()->routeIs('nosotros') ? 'active-menu' : '' }}">
                        Nosotros
                    </a>
                </li>
            </ul>

            <!-- LOGO CIRCULAR CENTRADO -->
            <div class="brand_logo_center">
                <a href="{{ route('home') }}">
                    <img src="{{ asset($empresa->logo_header ?? 'assets/images/logo.png') }}" alt="logo">
                </a>
            </div>

            <!-- BLOQUE DERECHO: Navegación + Acción -->
            <ul class="menu_right d-none d-lg-flex">
                @if($mostrarBlogs == 1)
                <li>
                    <a href="{{ route('blog.index') }}" class="menu_item_link {{ request()->routeIs('blog.index') ? 'active-menu' : '' }}">
                        Blog
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('contact.index') }}" class="menu_item_link {{ request()->routeIs('contact.index') ? 'active-menu' : '' }}">
                        Contacto
                    </a>
                </li>

                <!-- Botón de Búsqueda -->
                <li>
                    <button type="button" class="search_btn border-0 bg-transparent" data-toggle="collapse" data-target="#search_body_collapse">
                        <i class="fal fa-search" style="color: #2b2b2b;"></i>
                    </button>
                </li>

                <!-- Botón de Acción destacado (Pill) -->
                @if($numeroWhatsappUrl)
                <li>
                    <a href="https://wa.me/{{ $numeroWhatsappUrl }}" class="btn_pill" target="_blank" rel="noopener">
                        Escríbenos
                    </a>
                </li>
                @endif
            </ul>

            <!-- Botón del menú móvil -->
            <div class="d-lg-none ml-auto">
                <button type="button" class="mobile_menu_btn border-0 bg-transparent">
                    <i class="far fa-bars" style="font-size: 20px; color: #2b2b2b;"></i>
                </button>
            </div>

        </div>

        <!-- Buscador Desplegable -->
        <div id="search_body_collapse" class="search_body_collapse collapse search_overlay">
            <div class="search_body">
                <div class="container-fluid prl_90">
                    <form action="{{ route('productos.buscar') }}" method="GET" class="search_form">
                        <div class="search_inline">
                            <input type="search" name="search" placeholder="¿Qué estás buscando?" value="{{ request('search') }}" required>
                            <button type="submit">
                                <i class="fal fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar Menú Móvil -->
    <div class="sidebar-menu-wrapper">
        <div class="sidebar_mobile_menu">
            <button type="button" class="close_btn"><i class="fal fa-times"></i></button>

            <div class="msb_widget brand_logo text-center" style="padding-bottom: 0px !important;">
                <a href="{{ route('home') }}">
                    <img src="{{ asset($empresa->logo_header ?? 'assets/images/logo.png') }}" alt="logo" style="max-width: 50%; margin: 0 auto;">
                </a>
            </div>

            <div class="msb_widget mobile_menu_list clearfix">
                <h3 class="title_text mb_15 text-uppercase">
                    <i class="far fa-bars mr-2"></i> Menú
                </h3>
                <ul class="ul_li_block clearfix">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                    @if($mostrarProductos == 1)
                    <li class="menu_item_has_child">
                        <a href="#!">Productos</a>
                        <ul class="submenu submenu_flat">
                            @foreach($categorias as $categoria)
                            <li>
                                <a href="{{ route('productos.categoria', $categoria->slug) }}">{{ $categoria->nombre }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                    @if($mostrarServicios == 1)
                    <li class="menu_item_has_child">
                        <a href="#!">Servicios</a>
                        <ul class="submenu">
                            @foreach($services as $service)
                            <li>
                                <a href="{{ route('services.show', $service->slug) }}">{{ $service->nombre }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                    @if($mostrarBlogs == 1)
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    @endif
                    <li><a href="{{ route('contact.index') }}">Contacto</a></li>
                </ul>
            </div>
        </div>
        <div class="overlay"></div>
    </div>