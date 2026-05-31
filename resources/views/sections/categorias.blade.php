<section class="deals_section sec_ptb_50 clearfix">
    <div class="container maxw_1460">

        <div class="section_title text-center mb-4">
            <h4>Categorías</h4>
            <div class="title_line"></div>
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

        <div class="slideshow5_slider row clearfix"
            data-slick='{
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

                <a href="{{ route('productos.categoria', $cat->slug) }}"
                    class="category_carousel_card">

                    <div class="category_circle">

                        <img src="{{ $imagenCategoria }}"
                            alt="{{ $cat->nombre }}">

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

                <a href="{{ route('productos.categoria', $cat->slug) }}"
                    class="category_grid_card">

                    <div class="category_grid_image">

                        <img src="{{ $imagenCategoria }}"
                            alt="{{ $cat->nombre }}">

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

<style>

/* ========================================= */
/* OPCION 1 - CARRUSEL ECOMMERCE */
/* ========================================= */

.category_carousel_card{
    display:block;
    text-align:center;
    text-decoration:none;
}

.category_circle{
    width:130px;
    height:130px;
    margin:auto;
    border-radius:50%;
    overflow:hidden;
    background:#fff;
    border:1px solid #eee;
    transition:.3s;
}

.category_circle img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.4s;
}

.category_carousel_card:hover .category_circle{
    transform:translateY(-6px);
    box-shadow:0 15px 30px rgba(0,0,0,.08);
}

.category_carousel_card:hover img{
    transform:scale(1.08);
}

.category_carousel_card h5{
    margin-top:15px;
    font-size:15px;
    font-weight:600;
    color:#222;
}

/* ========================================= */
/* OPCION 2 - GRID CORPORATIVO */
/* ========================================= */

.category_grid_card{
    display:block;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    text-decoration:none;
    border:1px solid #eee;
    transition:.3s;
    height:100%;
}

.category_grid_card:hover{
    transform:translateY(-8px);
    box-shadow:0 20px 40px rgba(0,0,0,.08);
}

.category_grid_image{
    width:100%;
    height:260px;
    overflow:hidden;
}

.category_grid_image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.5s;
}

.category_grid_card:hover img{
    transform:scale(1.08);
}

.category_grid_content{
    padding:20px;
    text-align:center;
}

.category_grid_content h5{
    margin:0;
    font-size:18px;
    font-weight:700;
    color:#222;
}

@media(max-width:768px){

    .category_circle{
        width:100px;
        height:100px;
    }

    .category_grid_image{
        height:180px;
    }

    .category_grid_content h5{
        font-size:16px;
    }

}
</style>