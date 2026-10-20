<section class="deals_section sec_ptb_50 clearfix">
    <div class="container maxw_1460">

        @if(($config['seccion_categorias_mostrar_encabezado'] ?? 1) == 1)
        <div class="section_heading text-center mb_30">

            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_categorias_titulo'] ?? 'CATEGORÍAS' }}</small>
                <span></span>
            </div>

            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_categorias_descripcion'] ?? 'Descubre nuestras colecciones cuidadosamente organizadas para encontrar el producto ideal con una experiencia de compra rápida y sencilla.') !!}
            </p>

        </div>
        @endif
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
            $imagenCategoria = imagenOrDefault($cat->imagen);
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

        @elseif($categoriasTipo === 'opcion_3')

        {{-- ========================================= --}}
        {{-- GRID DE CATEGORÍAS CON ICONOS --}}
        {{-- ========================================= --}}

        <div class="row justify-content-center category_icon_grid">

            @foreach($categorias as $cat)

            @php
            $imagenCategoria = imagenOrDefault($cat->imagen);
            @endphp

            <div class="col-6 col-md-4 col-lg-2 mb-3">

                <a href="{{ route('productos.categoria', $cat->slug) }}" class="category_icon_card">

                    <div class="category_icon_image">
                        <img src="{{ $imagenCategoria }}" alt="" aria-hidden="true">
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
            $imagenCategoria = imagenOrDefault($cat->imagen);
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