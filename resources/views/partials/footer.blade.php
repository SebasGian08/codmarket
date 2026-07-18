@php
    $footerTheme = $config['footer_theme'] ?? 'dark';

    switch ($footerTheme) {
        case 'light':
            $footerClass = 'light_footer';
            break;

        case 'dark':
        default:
            $footerClass = '';
            break;
    }
@endphp

<footer class="footer_section fashion_minimal_footer clearfix {{ $footerClass }}">

    {{-- BACK TO TOP --}}
    <div class="backtotop" data-background="{{ asset('assets/images/shape_01.png') }}"
        style="background-image: url('{{ asset('assets/images/shape_01.png') }}'); display: block;">

        <a href="#" class="scroll">
            <i class="far fa-arrow-up"></i>
        </a>
    </div>

    {{-- FOOTER TOP --}}
    <div class="footer_widget_area">
        <div class="container">
            <div class="row gy-5 justify-content-between">

                {{-- LOGO + DESCRIPCIÓN --}}
                <div class="col-lg-4 col-md-6">

                    <div class="footer_widget footer_about">

                        <div class="brand_logo mb-4">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset($empresa->logo_footer ?? $empresa->logo_header ?? 'assets/images/logo.png') }}"
                                    alt="{{ $empresa->nombre ?? 'Logo' }}" class="footer-logo">
                            </a>
                        </div>

                        <div class="footer_description">
                            {!! $empresa->descripcion ?? '
                            <p>
                                Encuentra productos exclusivos, promociones especiales
                                y una experiencia de compra rápida y segura.
                            </p>' !!}
                        </div>

                        {{-- REDES SOCIALES (IGUAL QUE HEADER) --}}
                        <div class="footer_social mt-3">
                            <ul class="primary_social_links ul_li">

                                @if($empresa && $empresa->facebook)
                                <li><a href="{{ $empresa->facebook }}" target="_blank" style="color: #ffffff;"><i
                                            class="fab fa-facebook-f"></i></a></li>
                                @endif

                                @if($empresa && $empresa->instagram)
                                <li><a href="{{ $empresa->instagram }}" target="_blank" style="color: #ffffff;"><i
                                            class="fab fa-instagram"></i></a></li>
                                @endif

                                @if($empresa && $empresa->tiktok)
                                <li><a href="{{ $empresa->tiktok }}" target="_blank" style="color: #ffffff;"><i
                                            class="fab fa-tiktok"></i></a></li>
                                @endif

                            </ul>
                        </div>

                        <!--  <div class="payment_methods mt-4">
                            <img src="{{ asset('assets/images/payment_methods_01.png') }}" alt="Métodos de pago">
                        </div>
                        -->
                    </div>

                </div>

                {{-- CONTACTO --}}
                <div class="col-lg-3 col-md-6">

                    <div class="footer_widget">

                        <h3 class="footer_widget_title">Contáctanos</h3>

                        <ul class="footer_links">

                            @if($empresa->direccion)
                            <li>
                                <span>Dirección:</span>
                                {!! $empresa->direccion !!}
                            </li>
                            @endif

                            @if($empresa->telefono)
                            <li>
                                <span>Celular:</span>
                                {{ $empresa->telefono }}
                            </li>
                            @endif

                            @if($empresa->correo)
                            <li>
                                <span>Email:</span>
                                {{ $empresa->correo }}
                            </li>
                            @endif

                        </ul>

                    </div>

                </div>

                {{-- NAVEGACIÓN --}}
                <div class="col-lg-2 col-md-6">

                    <div class="footer_widget">

                        <h3 class="footer_widget_title">Navegación</h3>

                        <ul class="footer_links">
                            <li><a href="{{ route('home') }}">Inicio</a></li>
                            <li><a href="{{ route('productos.index') }}">Productos</a></li>
                            <!-- <li><a href="#!">Categorías</a></li> -->
                            <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                            <!-- <li><a href="{{ route('blog.index') }}">Blog</a></li> -->
                            <li><a href="{{ route('contact.index') }}">Contacto</a></li>
                        </ul>

                    </div>

                </div>

                {{-- AYUDA --}}
                <div class="col-lg-3 col-md-6">

                    <div class="footer_widget">

                        <!-- <h3 class="footer_widget_title">Atención al Cliente</h3>

                        <ul class="footer_links">
                            <li><a href="#!">Preguntas Frecuentes</a></li>
                            <li><a href="#!">Métodos de Pago</a></li>
                            <li><a href="#!">Políticas de Envío</a></li>
                            <li><a href="#!">Términos y Condiciones</a></li>
                            <li><a href="#!">Libro de Reclamaciones</a></li>
                        </ul> -->

                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- FOOTER BOTTOM --}}
    <div class="footer_bottom">
        <div class="container">

            <div class="footer_bottom_content">

                <p class="copyright_text">
                    © {{ date('Y') }}
                    <strong>{{ $empresa->nombre ?? 'Neoncart' }}</strong>.
                    Todos los derechos reservados.
                </p>

            </div>

        </div>
    </div>

</footer>

@php
$telefono = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');
$mensaje = urlencode('Hola, vengo de la web! Quisiera pedir más información.');
@endphp

<a href="https://wa.me/{{ $telefono }}?text={{ $mensaje }}" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
.light_footer {
    background: #ffffff;
    color: #222 !important;
    border-top: 1px solid #e5e5e5 !important;
}

/* títulos */
.light_footer .footer_widget_title {
    color: #111 !important;
    font-weight: 600;
}

/* textos generales */
.light_footer p,
.light_footer li,
.light_footer span {
    color: #444 !important;
}

/* links */
.light_footer a {
    color: #333 !important;
    transition: 0.3s;
}

.light_footer a:hover {
    color: #000 !important;
}

/* widgets */
.light_footer .footer_widget {
    border-color: rgba(0, 0, 0, 0.08) !important;
}


/* redes sociales */
.light_footer .footer_social i {
    color: #111 !important;
}

/* back to top */
.light_footer .backtotop {
    filter: none;
}

/* whatsapp flotante (si quieres mantenerlo limpio) */
.whatsapp-float {
    background: #25d366;
    color: #fff !important;
}

.light_footer .footer_description {
    margin-bottom: 0;
    color: #000000 !important;
}
</style>