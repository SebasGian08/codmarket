<section class="gadget_feature_section ecommerce_features sec_ptb_50 py-5">
    <div class="container">

        <!-- HEADER SECCIÓN -->
        <div class="section_title text-center mb-5">
            <span class="small_title">
                {{ $config['seccion_steps_titulo'] ?? '¿POR QUÉ ELEGIRNOS?' }}
            </span>
            <h2 class="title_heading">Ventajas de comprar con nosotros</h2>
            <div class="section_description">
                {!! limpiarTextoEditor($config['seccion_steps_descripcion'] ?? 'Diseñamos una experiencia moderna, rápida y segura para que compres con total confianza.') !!}
            </div>
        </div>

        @php
            $ventajas = $empresa->empresa_ventajas ?: [
                ['icono' => 'fas fa-shipping-fast', 'titulo' => 'Envíos Rápidos', 'descripcion' => 'Realizamos entregas ágiles y seguras para que recibas tus productos en el menor tiempo posible.'],
                ['icono' => 'fas fa-shield-alt', 'titulo' => 'Compra 100% Segura', 'descripcion' => 'Protegemos cada transacción con métodos de pago confiables y seguridad avanzada.'],
                ['icono' => 'fab fa-whatsapp', 'titulo' => 'Atención Personalizada', 'descripcion' => 'Nuestro equipo está listo para ayudarte antes, durante y después de tu compra.'],
            ];
        @endphp

        <!-- GRID TARJETAS -->
        <div class="row justify-content-center g-4">
            @foreach($ventajas as $ventaja)
            <div class="col-lg-4 col-md-6">
                <div class="feature_card">
                    <div class="feature_card_inner">
                        <div class="feature_icon_wrapper">
                            <div class="feature_icon">
                                <i class="{{ $ventaja['icono'] ?? 'fas fa-check-circle' }}"></i>
                            </div>
                        </div>
                        <div class="feature_content">
                            <h3>{{ $ventaja['titulo'] ?? '' }}</h3>
                            <p>{{ $ventaja['descripcion'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>