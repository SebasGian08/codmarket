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
            $clientesFilas = collect([
                $clientesCarousel->take(5),
                $clientesCarousel->slice(5),
            ])->filter(function ($fila) {
                return $fila->isNotEmpty();
            });
        @endphp

        @if($clientesCarousel->count())

        <div class="clientes_carousel_wrap">

            @foreach($clientesFilas as $indiceFila => $clientesFila)
            <div class="clientes_marquee clientes_marquee--{{ $indiceFila === 0 ? 'derecha' : 'izquierda' }}">
                <div class="clientes_track">
                    @foreach([false, true] as $esDuplicado)
                        @foreach($clientesFila as $cliente)
                        <div class="cliente_card">
                            <img src="{{ imagenOrDefault($cliente->logo ?: $cliente->imagen) }}"
                                alt="{{ $esDuplicado ? '' : $cliente->nombre }}"
                                class="cliente_logo"
                                @if($esDuplicado) aria-hidden="true" @endif>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            @endforeach

        </div>

        @endif

    </div>

</section>

@endif
