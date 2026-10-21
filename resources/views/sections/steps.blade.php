<section class="gadget_feature_section ecommerce_features sec_ptb_50 clearfix mt-2" style="background: #f8fafc;">
    <div class="container">

        @php
            $tipoSteps = $config['seccion_steps_tipo'] ?? 'opcion_1';
            $ventajasPredeterminadas = [
                ['icono' => 'fas fa-shipping-fast', 'titulo' => 'Envíos Rápidos', 'descripcion' => 'Realizamos entregas ágiles y seguras para que recibas tus productos en el menor tiempo posible.'],
                ['icono' => 'fas fa-shield-alt', 'titulo' => 'Compra 100% Segura', 'descripcion' => 'Protegemos cada transacción con métodos de pago confiables y seguridad avanzada.'],
                ['icono' => 'fab fa-whatsapp', 'titulo' => 'Atención Personalizada', 'descripcion' => 'Nuestro equipo está listo para ayudarte antes, durante y después de tu compra.'],
                ['icono' => 'fas fa-balance-scale', 'titulo' => 'Precio-beneficio', 'descripcion' => 'Contamos con productos a precio justo, seguros y eficaces, sin comprometer la calidad.'],
            ];
            $ventajas = collect($empresa->empresa_ventajas ?: $ventajasPredeterminadas)
                ->take(4)
                ->values();
            while ($ventajas->count() < 4) {
                $ventajas->push($ventajasPredeterminadas[$ventajas->count()]);
            }
        @endphp

        @if($tipoSteps === 'opcion_2')
        <div class="row steps-opcion-2 g-3">
            @foreach($ventajas as $ventaja)
            @if(!empty($ventaja['titulo']) || !empty($ventaja['descripcion']))
            <div class="col-lg-3 col-md-6 mb-2">
                <article class="steps-opcion-2-card">
                    @if(!empty($ventaja['icono']))
                    <div class="steps-opcion-2-icon">
                        <i class="{{ $ventaja['icono'] }}" aria-hidden="true"></i>
                    </div>
                    @endif
                    @if(!empty($ventaja['titulo']))
                    <h3>{{ $ventaja['titulo'] }}</h3>
                    @endif
                    @if(!empty($ventaja['descripcion']))
                    <p>{{ $ventaja['descripcion'] }}</p>
                    @endif
                </article>
            </div>
            @endif
            @endforeach
        </div>
        @else
        @if(($config['seccion_steps_mostrar_encabezado'] ?? 1) == 1)
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
        @endif

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
        @endif

    </div>
</section>