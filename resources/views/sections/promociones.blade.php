@php
$mostrarPromociones = $config['home_mostrar_promociones'] ?? 1;
$disenoPromociones = $config['home_promociones_layout'] ?? 'opcion_1';
@endphp

@if($mostrarPromociones)
    @if($disenoPromociones === 'opcion_2')
    <section class="offer_section promociones-banner-ancho">
        @if($promociones->count() > 0)
        <div class="fm_offer_item">
            <a href="{{ $promociones[0]->enlace ?: '#' }}">
                <img src="{{ !empty($promociones[0]->imagen)
                        ? asset($promociones[0]->imagen)
                        : asset('assets/images/tienda_virtual/default.png') }}"
                    alt="{{ $promociones[0]->titulo ?? 'Promoción' }}">
            </a>
            @if($promociones[0]->texto_boton)
            <div class="promociones-banner-boton-wrap promociones-banner-boton-{{ $promociones[0]->posicion_boton ?? 'centro' }}">
                <a class="promociones-banner-boton" href="{{ $promociones[0]->enlace ?: '#' }}">
                    {{ $promociones[0]->texto_boton }}
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
            @endif
        </div>
        @endif
    </section>
    @else
    <section class="offer_section clearfix">
        <div class="container-fluid prl_100">
            <div class="row mt__30">

            {{-- BANNER GRANDE --}}
            <div class="col-lg-8 col-md-6 col-sm-12">

                @if($promociones->count() > 0)

                <div class="fm_offer_item has_border">
                    <a href="{{ $promociones[0]->enlace ?? '#' }}">

                        <img src="{{ !empty($promociones[0]->imagen)
                                ? asset($promociones[0]->imagen)
                                : asset('assets/images/tienda_virtual/default.png') }}"
                            alt="{{ $promociones[0]->titulo ?? 'Promoción' }}">

                    </a>
                </div>

                @endif

            </div>

            {{-- BANNERS PEQUEÑOS --}}
            <div class="col-lg-4 col-md-6 col-sm-12">

                {{-- ITEM 2 --}}
                @if($promociones->count() > 1)
                <div class="fm_offer_item mb-3">
                    <a href="{{ $promociones[1]->enlace ?? '#' }}">
                        <img src="{{ !empty($promociones[1]->imagen)
                                ? asset($promociones[1]->imagen)
                                : asset('assets/images/tienda_virtual/default.png') }}"
                            alt="{{ $promociones[1]->titulo ?? 'Promoción' }}">
                    </a>
                </div>
                @endif

                {{-- ITEM 3 --}}
                @if($promociones->count() > 2)
                <div class="fm_offer_item">
                    <a href="{{ $promociones[2]->enlace ?? '#' }}">
                        <img src="{{ !empty($promociones[2]->imagen)
                                ? asset($promociones[2]->imagen)
                                : asset('assets/images/tienda_virtual/default.png') }}"
                            alt="{{ $promociones[2]->titulo ?? 'Promoción' }}">
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif
@endif
