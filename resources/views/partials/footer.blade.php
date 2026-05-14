<footer class="footer_section fashion_minimal_footer clearfix">

    {{-- BACK TO TOP --}}
    <div class="backtotop"
        data-background="{{ asset('assets/images/shape_01.png') }}"
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
                                    alt="{{ $empresa->nombre ?? 'Logo' }}"
                                    class="footer-logo">
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
                                <li><a href="{{ $empresa->facebook }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                @endif

                                @if($empresa && $empresa->instagram)
                                <li><a href="{{ $empresa->instagram }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
                                @endif

                                @if($empresa && $empresa->tiktok)
                                <li><a href="{{ $empresa->tiktok }}" target="_blank"><i class="fab fa-tiktok"></i></a></li>
                                @endif

                            </ul>
                        </div>

                        <div class="payment_methods mt-4">
                            <img src="{{ asset('assets/images/payment_methods_01.png') }}" alt="Métodos de pago">
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
                            <li><a href="{{ route('productos.index') }}">Tienda</a></li>
                            <!-- <li><a href="#!">Categorías</a></li> -->
                            <li><a href="#!">Nosotros</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
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

{{-- BOTÓN WHATSAPP --}}
<a href="https://wa.me/51907225732" class="whatsapp-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
.footer_section {
    background: #0f0f0f;
    color: #d8d8d8;
}

.footer_widget_area {
    padding: 90px 0 60px;
    border-bottom: 1px solid rgba(255, 255, 255, .08);
}

.footer-logo {
    max-width: 180px;
}

.footer_description {
    font-size: 15px;
    line-height: 1.9;
    color: rgba(255, 255, 255, .70);
}

.footer_widget_title {
    color: #fff !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    margin-bottom: 25px !important;
    position: relative !important;
}

.footer_widget_title::after {
    content: '';
    width: 45px;
    height: 2px;
    background: var(--color-principal);
    display: block;
    margin-top: 10px;
}

.footer_links {
    padding: 0;
    margin: 0;
    list-style: none;
}

.footer_links li {
    margin-bottom: 14px;
    color: rgba(255, 255, 255, .70);
    font-size: 15px;
    line-height: 1.7;
}

.footer_links li span {
    color: #fff;
    font-weight: 600;
}

.footer_links li a {
    color: rgba(255, 255, 255, .70);
    transition: .3s ease;
    text-decoration: none;
    font-size: 12px;
}

.footer_links li a:hover {
    color: #fff;
    padding-left: 5px;
}

.footer_widget_title {
    position: relative;
    font-size: 15px !important;
    font-weight: 700;
    color: #fff;
    margin-bottom: 28px;
    padding-bottom: 14px;
    letter-spacing: .3px;
}

/* línea principal */
.footer_widget_title::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 55px;
    height: 3px;
    border-radius: 30px;
    background: #fff;
}

/* línea suave secundaria */
.footer_widget_title::before {
    content: "";
    position: absolute;
    left: 0;
    bottom: 1px;
    width: 100%;
    max-width: 140px;
    height: 1px;
    background: rgba(255, 255, 255, 0.12);
}

.payment_methods img {
    max-width: 220px;
    width: 100%;
}

.footer_bottom {
    padding: 22px 0;
    background: #090909;
}

.footer_bottom_content {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.copyright_text {
    margin: 0;
    color: rgba(255, 255, 255, .65);
    font-size: 14px;
}

.copyright_text strong {
    color: #fff;
}

/* WHATSAPP FLOAT */

.whatsapp-float {
    position: fixed;
    width: 65px;
    height: 65px;
    bottom: 25px;
    right: 25px;
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: #fff;
    border-radius: 50%;
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
    box-shadow: 0 10px 25px rgba(0, 0, 0, .25);
    transition: .3s ease;
    animation: pulse 2s infinite;
}

.whatsapp-float:hover {
    transform: scale(1.08);
    color: #fff;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, .6);
    }

    70% {
        box-shadow: 0 0 0 18px rgba(37, 211, 102, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

/* RESPONSIVE */

@media(max-width:768px) {

    .footer_widget_area {
        padding: 70px 0 40px;
    }

    .footer_widget {
        margin-bottom: 35px;
    }

    .footer_bottom_content {
        flex-direction: column;
        gap: 10px;
    }

    .whatsapp-float {
        width: 58px;
        height: 58px;
        font-size: 28px;
        right: 18px;
        bottom: 18px;
    }

}
</style>