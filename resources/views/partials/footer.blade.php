@php
$footerTheme = $config['footer_theme'] ?? 'dark';
$facebookUrl = $empresa->facebook ?? false;
$instagramUrl = $empresa->instagram ?? false;
$tiktokUrl = $empresa->tiktok ?? false;
$linkedinUrl = $empresa->linkedin ?? false;

switch ($footerTheme) {
case 'light':
$footerClass = 'light_footer';
break;

case 'dark':
default:
$footerClass = '';
break;
}


$mostrarBlogs = $config['home_mostrar_blogs'] ?? 1;
$mostrarServicios = $config['home_mostrar_servicios'] ?? 1;
$mostrarProductos = $config['web_mostrar_productos'] ?? 1;


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
            @if($footerTheme === 'light')
            <div class="footer_light_top">
                <div class="footer_light_brand_block">
                    <a class="footer_light_brand" href="{{ route('home') }}">
                        <img src="{{ asset($empresa->logo_footer ?? $empresa->logo_header ?? 'assets/images/logo.png') }}"
                            alt="{{ $empresa->nombre ?? 'Logo' }}">
                    </a>
                    <div class="footer_light_description">
                        {!! $empresa->descripcion ?? '
                        <p>
                            Encuentra productos exclusivos, promociones especiales
                            y una experiencia de compra rápida y segura.
                        </p>' !!}
                    </div>
                </div>

                <div class="footer_light_column">
                    <h3 class="footer_widget_title">Menú</h3>
                    <ul class="footer_links">
                        <li><a href="{{ route('home') }}">Inicio</a></li>
                        @if($mostrarProductos == 1)
                        <li><a href="{{ route('productos.index') }}">Productos</a></li>
                        @endif
                        <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                        @if($mostrarBlogs == 1)
                        <li><a href="{{ route('blog.index') }}">Novedades</a></li>
                        @endif
                        <li><a href="{{ route('contact.index') }}">Contacto</a></li>
                    </ul>
                </div>

                <div class="footer_light_column">
                    <h3 class="footer_widget_title">Recursos y contacto</h3>
                    <ul class="footer_links">
                        <li><a href="{{ route('contact.index') }}">Contáctanos</a></li>
                        <li><a href="{{ route('blog.index') }}">Novedades</a></li>
                        @if($empresa->correo)
                        <li><a href="mailto:{{ $empresa->correo }}">Correo electrónico</a></li>
                        @endif
                        @if($empresa->whatsapp)
                        <li><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $empresa->whatsapp) }}">WhatsApp</a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="footer_light_certifications">
                    @if(isset($footerLogos) && $footerLogos->count())
                    @foreach($footerLogos as $logo)
                    @if($logo->imagen)
                    @if($logo->url)
                    <a href="{{ $logo->url }}" target="_blank" rel="noopener noreferrer" class="footer_logo_item"
                        title="{{ $logo->titulo }}">
                        <img src="{{ asset($logo->imagen) }}" alt="{{ $logo->titulo ?? 'Logo' }}">
                    </a>
                    @else
                    <span class="footer_logo_item" title="{{ $logo->titulo }}">
                        <img src="{{ asset($logo->imagen) }}" alt="{{ $logo->titulo ?? 'Logo' }}">
                    </span>
                    @endif
                    @endif
                    @endforeach
                    @endif
                </div>

                <div class="footer_light_column footer_light_contact">
                    <h3 class="footer_widget_title">Contáctanos</h3>
                    <ul class="footer_links">
                        @if($empresa->direccion)
                        <li>
                            <i class="far fa-map-marker-alt" aria-hidden="true"></i>
                            <span><strong>Dirección:</strong> {!! $empresa->direccion !!}</span>
                        </li>
                        @endif
                        @if($empresa->telefono)
                        <li>
                            <i class="far fa-phone" aria-hidden="true"></i>
                            <span><strong>Asesoría en línea:</strong> {{ $empresa->telefono }}</span>
                        </li>
                        @endif
                        @if($empresa->correo)
                        <li>
                            <i class="far fa-envelope" aria-hidden="true"></i>
                            <span><strong>Email:</strong> {{ $empresa->correo }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            @else
            <div class="row footer_dark_top justify-content-between">

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
                            @if($mostrarProductos == 1)
                            <li><a href="{{ route('productos.index') }}">Productos</a></li>
                            @endif
                            <!-- <li><a href="#!">Categorías</a></li> -->
                            <li><a href="{{ route('nosotros') }}">Nosotros</a></li>
                            <!-- <li><a href="{{ route('blog.index') }}">Blog</a></li> -->
                            @if($mostrarBlogs == 1)
                            <li><a href="{{ route('blog.index') }}">Novedades</a></li>
                            @endif
                            <li><a href="{{ route('contact.index') }}">Contacto</a></li>
                        </ul>

                    </div>

                </div>

                {{-- REDES SOCIALES --}}
                <div class="col-lg-3 col-md-6">

                    <div class="footer_widget">

                        <h3 class="footer_widget_title">Síguenos</h3>

                        <div class="footer_social_cards">

                            @if($facebookUrl)
                            <a href="{{ $facebookUrl }}" target="_blank" class="social_card social_facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            @endif

                            @if($instagramUrl)
                            <a href="{{ $instagramUrl }}" target="_blank" class="social_card social_instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            @endif

                            @if($tiktokUrl)
                            <a href="{{ $tiktokUrl }}" target="_blank" class="social_card social_tiktok">
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

                            @if($linkedinUrl)
                            <a href="{{ $linkedinUrl }}" target="_blank" class="social_card social_linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                            @endif

                        </div>

                        @if(isset($footerLogos) && $footerLogos->count())
                        <div class="footer_logos">
                            @foreach($footerLogos as $logo)
                            @if($logo->imagen)
                            @if($logo->url)
                            <a href="{{ $logo->url }}" target="_blank" rel="noopener" class="footer_logo_item"
                                title="{{ $logo->titulo }}">
                                <img src="{{ asset($logo->imagen) }}" alt="{{ $logo->titulo ?? 'Logo' }}">
                            </a>
                            @else
                            <span class="footer_logo_item" title="{{ $logo->titulo }}">
                                <img src="{{ asset($logo->imagen) }}" alt="{{ $logo->titulo ?? 'Logo' }}">
                            </span>
                            @endif
                            @endif
                            @endforeach
                        </div>
                        @endif

                    </div>

                </div>

            </div>
            @endif
        </div>
    </div>

    {{-- FOOTER BOTTOM --}}
    <div class="footer_bottom">
        <div class="container">

            @if($footerTheme === 'light')
            <div class="footer_bottom_content footer_light_bottom">
                <p class="copyright_text">
                    © {{ date('Y') }}
                    <strong>{{ $empresa->nombre ?? 'falta-nombre' }}</strong>.
                    Todos los derechos reservados.
                </p>

                <div class="footer_light_socials" aria-label="Redes sociales">
                    @if($config['youtube_url'] ?? false)
                    <a href="{{ $config['youtube_url'] }}" target="_blank" rel="noopener noreferrer"
                        aria-label="YouTube">
                        <i class="fab fa-youtube" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if($facebookUrl)
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if($instagramUrl)
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if($linkedinUrl)
                    <a href="{{ $linkedinUrl }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if($tiktokUrl)
                    <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok">
                        <i class="fab fa-tiktok" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if($config['twitter_url'] ?? false)
                    <a href="{{ $config['twitter_url'] }}" target="_blank" rel="noopener noreferrer"
                        aria-label="Twitter / X">
                        <i class="fab fa-x-twitter" aria-hidden="true"></i>
                    </a>
                    @endif
                </div>
            </div>
            @else
            <div class="footer_bottom_content">
                <p class="copyright_text">
                    © {{ date('Y') }}
                    <strong>{{ $empresa->nombre ?? 'falta-nombre' }}</strong>.
                    Todos los derechos reservados.
                </p>
            </div>
            @endif

        </div>
    </div>

</footer>

@php
$whatsapp = preg_replace('/[^0-9]/', '', $empresa->whatsapp ?? '');
$mensaje = urlencode('Hola, vengo de la web! Quisiera pedir más información.');
@endphp

<a href="https://wa.me/{{ $whatsapp }}?text={{ $mensaje }}" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>