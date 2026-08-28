@if(($clientes ?? collect())->count())

<section class="cliente_section sec_ptb_50 clearfix">

    <div class="container">

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

        @php
            $clientesCarousel = ($clientes ?? collect())
                ->filter(fn($c) => $c->logo || $c->imagen)
                ->values();
        @endphp

        @if($clientesCarousel->count())

        <div class="row align-items-center mb-4">

            <div class="col-lg-10 col-6"></div>

            <div class="col-lg-2 col-6">
                <div class="carousel_nav align_right">
                    <button type="button" class="left_arrow_clientes">
                        <i class="fal fa-arrow-left"></i>
                    </button>
                    <button type="button" class="right_arrow_clientes">
                        <i class="fal fa-arrow-right"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="clientes_slider row clearfix"
            data-slick='{
                "dots": false,
                "arrows": true,
                "infinite": true,
                "autoplay": true,
                "autoplaySpeed": 4000,
                "slidesToShow": 4,
                "slidesToScroll": 1,
                "prevArrow": ".left_arrow_clientes",
                "nextArrow": ".right_arrow_clientes",
                "responsive": [
                    {
                        "breakpoint": 992,
                        "settings": {
                            "slidesToShow": 3
                        }
                    },
                    {
                        "breakpoint": 576,
                        "settings": {
                            "slidesToShow": 2
                        }
                    }
                ]
            }'>

            @foreach($clientesCarousel as $cliente)

            <div class="item px-2">

                <div class="cliente_card">

                    @if($cliente->logo)
                        <img src="{{ asset($cliente->logo) }}"
                            alt="{{ $cliente->nombre }}" class="cliente_logo">
                    @elseif($cliente->imagen)
                        <img src="{{ asset($cliente->imagen) }}"
                            alt="{{ $cliente->nombre }}" class="cliente_logo">
                    @endif
                </div>

            </div>

            @endforeach

        </div>

        @endif

    </div>

</section>

@endif
