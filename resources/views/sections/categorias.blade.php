<section class="deals_section sec_ptb_50 clearfix">
    <div class="container maxw_1460">

        <div class="section_heading text-center mb-5">

            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_categorias_titulo'] ?? 'CATEGORÍAS' }}</small>
                <span></span>
            </div>

            <p class="section_heading_description">
                {{ $config['seccion_categorias_descripcion'] ?? 'Descubre nuestras colecciones cuidadosamente organizadas para encontrar el producto ideal con una experiencia de compra rápida y sencilla.' }}
            </p>

        </div>
        @php
        $categoriasTipo = $config['categorias_tipo'] ?? 'opcion_1';
        @endphp

        @if($categoriasTipo === 'opcion_1')

        {{-- ========================================= --}}
        {{-- CARRUSEL ECOMMERCE --}}
        {{-- ========================================= --}}

        <div class="row align-items-center mb-4">

            <div class="col-lg-10"></div>

            <div class="col-lg-2">

                <div class="carousel_nav align_right">

                    <button type="button" class="left_arrow5">
                        <i class="fal fa-arrow-left"></i>
                    </button>

                    <button type="button" class="right_arrow5">
                        <i class="fal fa-arrow-right"></i>
                    </button>

                </div>

            </div>

        </div>

        <div class="slideshow5_slider row clearfix" data-slick='{
                "dots": false,
                "arrows": true,
                "infinite": true,
                "autoplay": false,
                "slidesToShow": 5,
                "slidesToScroll": 1,
                "prevArrow": ".left_arrow5",
                "nextArrow": ".right_arrow5",
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

            @foreach($categorias as $cat)

            @php
            $imagenCategoria = !empty($cat->imagen)
            ? asset($cat->imagen)
            : asset('assets/images/tienda_virtual/default.png');
            @endphp

            <div class="item px-2">

                <a href="{{ route('productos.categoria', $cat->slug) }}" class="category_carousel_card">

                    <div class="category_circle">

                        <img src="{{ $imagenCategoria }}" alt="{{ $cat->nombre }}">

                    </div>

                    <h5>{{ $cat->nombre }}</h5>

                </a>

            </div>

            @endforeach

        </div>

        @else

        {{-- ========================================= --}}
        {{-- GRID CORPORATIVO --}}
        {{-- ========================================= --}}

        <div class="row">

            @foreach($categorias as $cat)

            @php
            $imagenCategoria = !empty($cat->imagen)
            ? asset($cat->imagen)
            : asset('assets/images/tienda_virtual/default.png');
            @endphp

            <div class="col-lg-3 col-md-6 col-6 mb-4">

                <a href="{{ route('productos.categoria', $cat->slug) }}" class="category_grid_card">

                    <div class="category_grid_image">

                        <img src="{{ $imagenCategoria }}" alt="{{ $cat->nombre }}">

                    </div>

                    <div class="category_grid_content">

                        <h5>{{ $cat->nombre }}</h5>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

        @endif

    </div>
</section>