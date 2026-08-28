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
                <div class="col-lg-3 col-md-6">

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

                    </div>

                </div>

                {{-- CONTACTO --}}
                <div class="col-lg-3 col-md-6">

                    <div class="footer_widget">

                        <h3 class="footer_widget_title">Contáctanos</h3>

                        <ul class="footer_links">

                            @if($empresa->direccion)
                            <li>
                                <i class="far fa-map-marker-alt"></i>
                                <span>Dirección:</span>
                                {!! $empresa->direccion !!}
                            </li>
                            @endif

                            @if($empresa->telefono)
                            <li>
                                <i class="far fa-phone"></i>
                                <span>Asesoría en línea:</span>
                                {{ $empresa->telefono }}
                            </li>
                            @endif

                            @if($empresa->correo)
                            <li>
                                <i class="far fa-envelope"></i>
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

                {{-- REDES SOCIALES --}}
                <div class="col-lg-3 col-md-6">

                    <div class="footer_widget">

                        <h3 class="footer_widget_title">Síguenos</h3>

                        <div class="footer_social_cards">

                            @if($config['facebook_url'] ?? false)
                            <a href="{{ $config['facebook_url'] }}" target="_blank" class="social_card social_facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            @endif

                            @if($config['instagram_url'] ?? false)
                            <a href="{{ $config['instagram_url'] }}" target="_blank" class="social_card social_instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            @endif

                            @if($config['tiktok_url'] ?? false)
                            <a href="{{ $config['tiktok_url'] }}" target="_blank" class="social_card social_tiktok">
                                <i class="fab fa-tiktok"></i>
                                <span>TikTok</span>
                            </a>
                            @endif

                            @if($config['youtube_url'] ?? false)
                            <a href="{{ $config['youtube_url'] }}" target="_blank" class="social_card social_youtube">
                                <i class="fab fa-youtube"></i>
                                <span>YouTube</span>
                            </a>
                            @endif

                            @if($config['twitter_url'] ?? false)
                            <a href="{{ $config['twitter_url'] }}" target="_blank" class="social_card social_twitter">
                                <i class="fab fa-x-twitter"></i>
                                <span>Twitter / X</span>
                            </a>
                            @endif

                            @if($config['linkedin_url'] ?? false)
                            <a href="{{ $config['linkedin_url'] }}" target="_blank" class="social_card social_linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                            @endif

                        </div>

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
                    <strong>{{ $empresa->nombre ?? 'falta-nombre' }}</strong>.
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

/* icons in contact */
.footer_links li i {
    margin-right: 8px;
    font-size: 13px;
    opacity: 0.7;
}

.light_footer .footer_links li i {
    color: #444 !important;
}

/* redes sociales */
.light_footer .footer_social i {
    color: #111 !important;
}

/* back to top */
.light_footer .backtotop {
    filter: none;
}

/* whatsapp flotante */
.whatsapp-float {
    background: #25d366;
    color: #fff !important;
}

.light_footer .footer_description {
    margin-bottom: 0;
    color: #000000 !important;
}

/* ============ REDES SOCIALES - TARJETAS ============ */
.footer_social_cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.footer_social_cards .social_card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.footer_social_cards .social_card i {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.footer_social_cards .social_card:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.2);
}

/* Colores por red social */
.social_facebook:hover { background: rgba(59, 89, 152, 0.3); border-color: rgba(59, 89, 152, 0.5); }
.social_instagram:hover { background: rgba(225, 48, 108, 0.3); border-color: rgba(225, 48, 108, 0.5); }
.social_tiktok:hover { background: rgba(0, 0, 0, 0.4); border-color: rgba(255, 255, 255, 0.3); }
.social_youtube:hover { background: rgba(255, 0, 0, 0.3); border-color: rgba(255, 0, 0, 0.5); }
.social_twitter:hover { background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.3); }
.social_linkedin:hover { background: rgba(0, 119, 181, 0.3); border-color: rgba(0, 119, 181, 0.5); }

/* Light footer - tarjetas sociales */
.light_footer .footer_social_cards .social_card {
    background: rgba(0, 0, 0, 0.04);
    border-color: rgba(0, 0, 0, 0.08);
    color: #333;
}

.light_footer .footer_social_cards .social_card:hover {
    background: rgba(0, 0, 0, 0.08);
    border-color: rgba(0, 0, 0, 0.15);
}

.light_footer .social_facebook:hover { background: rgba(59, 89, 152, 0.12); border-color: rgba(59, 89, 152, 0.3); color: #3b5998; }
.light_footer .social_instagram:hover { background: rgba(225, 48, 108, 0.12); border-color: rgba(225, 48, 108, 0.3); color: #e1306c; }
.light_footer .social_tiktok:hover { background: rgba(0, 0, 0, 0.08); border-color: rgba(0, 0, 0, 0.15); }
.light_footer .social_youtube:hover { background: rgba(255, 0, 0, 0.1); border-color: rgba(255, 0, 0, 0.3); color: #ff0000; }
.light_footer .social_twitter:hover { background: rgba(0, 0, 0, 0.06); border-color: rgba(0, 0, 0, 0.12); }
.light_footer .social_linkedin:hover { background: rgba(0, 119, 181, 0.1); border-color: rgba(0, 119, 181, 0.3); color: #0077b5; }
</style>