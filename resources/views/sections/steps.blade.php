<section class="gadget_feature_section ecommerce_features sec_ptb_50 clearfix mt-2" style="background: #f8fafc;">
    <div class="container">

        <!-- TITLE -->
        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>
                    {{ $config['seccion_steps_titulo'] ?? '¿POR QUÉ ELEGIRNOS?' }}
                </small>
                <span></span>
            </div>

            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_steps_descripcion'] ?? 'Diseñamos una experiencia moderna, rápida y segura para que compres con total confianza.') !!}
            </p>
        </div>

        @php
            $ventajas = $empresa->empresa_ventajas ?: [
                ['icono' => 'fas fa-shipping-fast', 'titulo' => 'Envíos Rápidos', 'descripcion' => 'Realizamos entregas ágiles y seguras para que recibas tus productos en el menor tiempo posible.'],
                ['icono' => 'fas fa-shield-alt', 'titulo' => 'Compra 100% Segura', 'descripcion' => 'Protegemos cada transacción con métodos de pago confiables y seguridad avanzada.'],
                ['icono' => 'fab fa-whatsapp', 'titulo' => 'Atención Personalizada', 'descripcion' => 'Nuestro equipo está listo para ayudarte antes, durante y después de tu compra.'],
            ];
        @endphp

        <!-- GRID DE TARJETAS CON ÍCONO SOBRESALIENTE -->
        <div class="row justify-content-center g-4 pt-4">
            @foreach($ventajas as $ventaja)
            <div class="col-lg-4 col-md-6 col-sm-10 mt-5">
                <div class="feature_card">
                    <!-- Ícono flotante que sobresale -->
                    <div class="feature_icon_box">
                        <div class="feature_icon {{ $loop->first ? 'shipping' : ($loop->iteration === 2 ? 'secure' : 'support') }}">
                            <i class="{{ $ventaja['icono'] ?? '' }}"></i>
                        </div>
                    </div>
                    
                    <div class="feature_content">
                        <h3>{{ $ventaja['titulo'] ?? '' }}</h3>
                        <p>{{ $ventaja['descripcion'] ?? '' }}</p>
                    </div>

                    <!-- Detalle visual inferior -->
                    <div class="card_bottom_bar"></div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>