@if(($clientes ?? collect())->count())

<section class="cliente_section clearfix sec_ptb_50 scroll-reveal reveal-scale" aria-label="Clientes satisfechos">

    <div class="container">

        @if(($config['seccion_clientes_mostrar_encabezado'] ?? 1) == 1)
        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_clientes_titulo'] ?? 'NUESTROS CLIENTES' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_clientes_descripcion'] ?? 'Empresas y personas que confían en nosotros.') !!}
            </p>
        </div>
        @endif

        @php
            $clientesCarousel = ($clientes ?? collect())->values();
        @endphp

        @if($clientesCarousel->count())

        <div class="clientes_carousel_wrap">

            <div class="clientes_slider row clearfix">

                @foreach($clientesCarousel as $cliente)

                <div class="item px-2 mt-2">

                    <div class="cliente_card mt-2">

                        <img src="{{ imagenOrDefault($cliente->logo ?: $cliente->imagen) }}"
                            alt="{{ $cliente->nombre }}" class="cliente_logo">

                    </div>

                </div>

                @endforeach

            </div>

            <button type="button" class="left_arrow_clientes" aria-label="Clientes anteriores">
                <i class="fal fa-arrow-up" aria-hidden="true"></i>
            </button>

            <button type="button" class="right_arrow_clientes" aria-label="Clientes siguientes">
                <i class="fal fa-arrow-down" aria-hidden="true"></i>
            </button>

        </div>

        @endif

    </div>

</section>

@endif
