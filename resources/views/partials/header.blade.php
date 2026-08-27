<body class="home_furniture">

    @php
    $mostrarBlogs = $config['home_mostrar_blogs'] ?? 1;
    $mostrarServicios = $config['home_mostrar_servicios'] ?? 1;
    @endphp

    <header class="header_section fashion_minimal_header sticky_header clearfix"
        style="background-color: {{ $config['tema_color_fondo'] }}; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
        <div class="brand_ticker">
            <div class="brand_ticker_track">
                @for($i = 0; $i < 20; $i++) <span>
                    {{ strtoupper($empresa->nombre ?? 'FALTA-NOMBRE') }}
                    <i class="fas fa-circle"></i>
                    </span>
                    @endfor
            </div>
        </div>
        <div class="header_top clearfix topbar_main">
            <div class="container-fluid prl_100">

                <div class="topbar_flex">

                    <!-- IZQUIERDA: redes + texto -->
                    <div class="topbar_left">

                        <ul class="primary_social_links ul_li">
                            @if($config['facebook_url'] ?? false)
                            <li>
                                <a href="{{ $config['facebook_url'] }}" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>
                            @endif

                            @if($config['instagram_url'] ?? false)
                            <li>
                                <a href="{{ $config['instagram_url'] }}" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                            @endif

                            @if($config['tiktok_url'] ?? false)
                            <li>
                                <a href="{{ $config['tiktok_url'] }}" target="_blank">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                            </li>
                            @endif

                            @if($config['youtube_url'] ?? false)
                            <li>
                                <a href="{{ $config['youtube_url'] }}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                            @endif

                            @if($config['twitter_url'] ?? false)
                            <li>
                                <a href="{{ $config['twitter_url'] }}" target="_blank">
                                    <i class="fab fa-x-twitter"></i>
                                </a>
                            </li>
                            @endif

                            @if($config['linkedin_url'] ?? false)
                            <li>
                                <a href="{{ $config['linkedin_url'] }}" target="_blank">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
                            @endif
                        </ul>
                        <!-- <span style="color: #e1e1e1">|</span>
                        <span class="topbar_text" id="topbar-text">
                            ¡Ahora paga con Yape y Plin!
                        </span> -->

                    </div>

                    <!-- DERECHA: contacto -->
                    <div class="topbar_right">
                        <ul class="contact_info ul_li">
                            <li><i class="fas fa-phone-alt mr-1"></i> {{ $empresa->telefono ?? '' }}</li>
                            <li><i class="fas fa-envelope mr-1"></i> {{ $empresa->correo ?? '' }}</li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>

        @php
        $colorFondo = $config['header_color_fondo'] ?? $empresa->header_color_fondo ?? '#ffffff';
        $height = $config['header_height'] ?? '70px';
        @endphp

        <div class="header_content_wrap d-flex align-items-center clearfix"
            style="background-color: {{ $colorFondo }}; box-shadow: 0 4px 12px rgba(0,0,0,0.08); height: 70px;">
            <div class="container-fluid prl_90">
                <div class="row align-items-center">

                    <div class="col-lg-3">
                        <div class="brand_logo">
                            <a class="brand_link" href="{{ route('home') }}">
                                <img src="{{ asset($empresa->logo_header ?? 'assets/images/logo.png') }}" alt="logo"
                                    style="height: {{ $height }}; width: auto; object-fit: contain;">
                            </a>
                            <ul class="mh_action_btns ul_li clearfix">
                                <li>
                                    <button type="button" class="search_btn" data-toggle="collapse"
                                        data-target="#search_body_collapse">
                                        <i class="fal fa-search"></i>
                                    </button>
                                </li>

                                <!--  <li>
                                    <button type="button" class="cart_btn">
                                        <i class="fal fa-shopping-cart"></i>
                                        <span class="btn_badge">0</span>
                                    </button>
                                </li> -->

                                <li>
                                    <button type="button" class="mobile_menu_btn">
                                        <i class="far fa-bars"></i>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <nav class="main_menu clearfix">
                            <ul class="ul_li_center clearfix">

                                <li><a href="{{ route('home') }}"
                                        class="{{ request()->routeIs('home') ? 'active-menu' : '' }}">Inicio</a></li>
                                <li><a href="{{ route('nosotros') }}"
                                        class="{{ request()->routeIs('nosotros') ? 'active-menu' : '' }}">Nosotros</a>
                                </li>
                                <li>
                                    <a href="{{ route('productos.index') }}"
                                        class="{{ request()->routeIs('productos.index') ? 'active-menu' : '' }}">
                                        Productos
                                    </a>
                                </li>

                                <li class="menu_item_has_child">
                                    <a href="#!"
                                        class="{{ request()->routeIs('productos.categoria*') ? 'active-menu' : '' }}">
                                        Categorías
                                        <i class="fas fa-chevron-down ml-1 arrow_icon"></i>
                                    </a>

                                    <ul class="submenu">
                                        @foreach($categorias as $categoria)
                                        <li>
                                            <a href="{{ route('productos.categoria', $categoria->slug) }}">
                                                {{ $categoria->nombre }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>

                                @if($mostrarServicios == 1)
                                <li class="menu_item_has_child">
                                    <a href="#!" class="{{ request()->routeIs('services*') ? 'active-menu' : '' }}">
                                        Servicios
                                        <i class="fas fa-chevron-down ml-1 arrow_icon"></i>
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

                                @if($mostrarBlogs == 1)
                                <li><a href="{{ route('blog.index') }}"
                                        class="{{ request()->routeIs('blog.index') ? 'active-menu' : '' }}">Blog</a>
                                </li>
                                @endif

                                <li><a href="{{ route('contact.index') }}"
                                        class="{{ request()->routeIs('contact.index') ? 'active-menu' : '' }}">Contacto</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-3">
                        <ul class="action_btns_group ul_li_right clearfix">
                            <li>
                                <button type="button" class="search_btn" data-toggle="collapse"
                                    data-target="#search_body_collapse">
                                    <i class="fal fa-search"></i>
                                </button>
                            </li>
                            @auth

                            <li>
                                <button type="button" class="user_btn" data-toggle="collapse"
                                    data-target="#user_dropdown" aria-expanded="false">

                                    <i class="fal fa-user"></i>
                                </button>

                                <div id="user_dropdown" class="collapse_dropdown collapse">

                                    <div class="dropdown_content">

                                        <div class="profile_info clearfix">
                                            <div class="user_thumbnail">
                                                <i class="fas fa-user"></i>
                                            </div>


                                            <div class="user_content">
                                                <h4 class="user_name">{{ Auth::user()->nombres }}</h4>
                                                <span class="user_title">{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>

                                        <ul class="settings_options ul_li_block clearfix">

                                            <!-- <li>
                                                <a href="{{ route('profile') }}">
                                                    <i class="fal fa-user-cog"></i> Mi perfil
                                                </a>
                                            </li> -->

                                            <li>
                                                <a href="#"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="fal fa-sign-out-alt"></i> Cerrar sesión
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display:none;">
                                                    @csrf
                                                </form>
                                            </li>

                                        </ul>

                                    </div>

                                </div>
                            </li>

                            @else

                            <li>
                                <button type="button" class="user_btn" data-toggle="collapse"
                                    data-target="#guest_dropdown" aria-expanded="false">

                                    <i class="fal fa-user"></i>
                                </button>

                                <div id="guest_dropdown" class="collapse_dropdown collapse">

                                    <div class="dropdown_content">

                                        <div class="profile_info clearfix">
                                            <div class="user_thumbnail">
                                                <i class="fas fa-user"></i>
                                            </div>

                                            <div class="user_content">
                                                <h4 class="user_name">Bienvenido</h4>
                                                <span class="user_title">Accede a tu cuenta</span>
                                            </div>
                                        </div>

                                        <ul class="settings_options ul_li_block clearfix">

                                            <li>
                                                <a href="{{ route('admin.login') }}">
                                                    <i class="fal fa-sign-in-alt"></i> Iniciar sesión
                                                </a>
                                            </li>

                                        </ul>

                                    </div>

                                </div>
                            </li>

                            @endauth
                            <!-- <li>
                                <button type="button" class="cart_btn">
                                    <i class="fal fa-shopping-cart"></i>
                                    <span class="btn_badge">0</span>
                                </button>
                            </li> -->

                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div id="search_body_collapse" class="search_body_collapse collapse search_overlay">
            <div class="search_body">
                <div class="container-fluid prl_90">

                    <form action="{{ route('productos.buscar') }}" method="GET" class="search_form">

                        <div class="search_inline">

                            <input type="search" name="search" placeholder="¿Qué estás buscando?"
                                value="{{ request('search') }}" required>

                            <button type="submit">
                                <i class="fal fa-search"></i>
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>

    </header>

    <div class="sidebar-menu-wrapper">
        <div class="cart_sidebar">
            <button type="button" class="close_btn"><i class="fal fa-times"></i></button>

            <ul class="cart_items_list ul_li_block mb_30 clearfix">
                <li>
                    <div class="item_image">
                        <img src="assets/images/cart/img_01.jpg" alt="image_not_found">
                    </div>
                    <div class="item_content">
                        <h4 class="item_title">Yellow Blouse</h4>
                        <span class="item_price">$30.00</span>
                    </div>
                    <button type="button" class="remove_btn"><i class="fal fa-trash-alt"></i></button>
                </li>
                <li>
                    <div class="item_image">
                        <img src="assets/images/cart/img_01.jpg" alt="image_not_found">
                    </div>
                    <div class="item_content">
                        <h4 class="item_title">Yellow Blouse</h4>
                        <span class="item_price">$30.00</span>
                    </div>
                    <button type="button" class="remove_btn"><i class="fal fa-trash-alt"></i></button>
                </li>
                <li>
                    <div class="item_image">
                        <img src="assets/images/cart/img_01.jpg" alt="image_not_found">
                    </div>
                    <div class="item_content">
                        <h4 class="item_title">Yellow Blouse</h4>
                        <span class="item_price">$30.00</span>
                    </div>
                    <button type="button" class="remove_btn"><i class="fal fa-trash-alt"></i></button>
                </li>
            </ul>

            <ul class="total_price ul_li_block mb_30 clearfix">
                <li>
                    <span>Subtotal:</span>
                    <span>$90</span>
                </li>
                <li>
                    <span>Vat 5%:</span>
                    <span>$4.5</span>
                </li>
                <li>
                    <span>Discount 20%:</span>
                    <span>- $18.9</span>
                </li>
                <li>
                    <span>Total:</span>
                    <span>$75.6</span>
                </li>
            </ul>

            <ul class="btns_group ul_li_block clearfix">
                <li><a href="shop_cart.html">View Cart</a></li>
                <li><a href="shop_checkout.html">Checkout</a></li>
            </ul>
        </div>

        <div class="sidebar_mobile_menu">
            <button type="button" class="close_btn"><i class="fal fa-times"></i></button>

            <!-- LOGO -->
            <div class="msb_widget brand_logo text-center" style="padding-bottom: 0px !important;">
                <a href="{{ route('home') }}">
                    <img src="{{ asset($empresa->logo_header ?? 'assets/images/logo.png') }}" alt="logo"
                        style="max-width: 50%; margin: 0 auto;">
                </a>
            </div>

            <!-- MENU -->
            <div class="msb_widget mobile_menu_list clearfix">
                <h3 class="title_text mb_15 text-uppercase">
                    <i class="far fa-bars mr-2"></i> Menú
                </h3>

                <ul class="ul_li_block clearfix">

                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                    <li><a href="{{ route('productos.index') }}">Productos</a></li>


                    @if($mostrarBlogs == 1)
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    @endif

                    <li><a href="{{ route('contact.index') }}">Contacto</a></li>

                    <!-- CATEGORÍAS -->
                    <li class="menu_item_has_child">
                        <a href="#!">Categorías</a>
                        <ul class="submenu">
                            @foreach($categorias as $categoria)
                            <li>
                                <a href="{{ route('productos.categoria', $categoria->slug) }}">
                                    {{ $categoria->nombre }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>

                    @if($mostrarServicios == 1)
                    <li class="menu_item_has_child">
                        <a href="#!">
                            Servicios
                            <i class="fas fa-chevron-down ml-1 arrow_icon"></i>
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

                </ul>
            </div>

            <!-- USER INFO -->
            <div class="user_info">

                <h3 class="title_text mb_30 text-uppercase">
                    <i class="fas fa-user mr-2"></i> Usuario
                </h3>

                @auth
                <div class="profile_info clearfix">
                    <div class="user_thumbnail">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user_content">
                        <h4 class="user_name">{{ Auth::user()->nombres }}</h4>
                        <span class="user_title">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <ul class="settings_options ul_li_block clearfix">
                    <!-- <li>
                        <a href="{{ route('profile') }}">
                            <i class="fal fa-user-circle"></i> Perfil
                        </a>
                    </li>
 -->
                    <li>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="fal fa-sign-out-alt"></i> Cerrar sesión
                        </a>

                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                            style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
                @else
                <div class="profile_info clearfix">
                    <div class="user_thumbnail">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="user_content">
                        <h4 class="user_name">Bienvenido</h4>
                        <span class="user_title">Accede a tu cuenta</span>
                    </div>
                </div>

                <ul class="settings_options ul_li_block clearfix">
                    <li>
                        <a href="{{ route('admin.login') }}">
                            <i class="fal fa-sign-in-alt"></i> Iniciar sesión
                        </a>
                    </li>
                </ul>
                @endauth

            </div>
        </div>

        <div class="overlay"></div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const messages = [
            /* "¡AHORA PAGA CON YAPE Y PLIN!", */
            "ENTREGAS EN MENOS DE 24 HORAS",
            "ENVÍOS A TODO EL PERÚ",
            "OFERTAS EXCLUSIVAS CADA SEMANA",
            "COMPRA SEGURA Y GARANTIZADA"
        ];

        let index = 0;
        const text = document.getElementById("topbar-text");

        if (!text) return;

        setInterval(() => {
            text.classList.remove("show");

            setTimeout(() => {
                index = (index + 1) % messages.length;
                text.innerText = messages[index];
                text.classList.add("show");
            }, 400);

        }, 3000);

    });
    </script>