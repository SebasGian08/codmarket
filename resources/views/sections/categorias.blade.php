<section class="deals_section sec_ptb_50 clearfix">
    <div class="container maxw_1460">

        <div class="section_title text-center mb-4">
            <h4>Categorías</h4>
            <div class="title_line"></div>
        </div>

        <div class="row align-items-center">


            <div class="col-lg-10">
                <div class="carousel_nav align_right">

                    <!--  -->
                </div>
            </div>
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

        <div class="supermarket_deals_carousel position-relative clearfix">

            <div class="slideshow5_slider row clearfix" data-slick='{
                    "dots": false,
                    "arrows": true,
                    "infinite": true,
                    "autoplay": false,
                    "slidesToShow": 5,
                    "slidesToScroll": 1,
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

                <div class="item col">
                    <div class="category_card text-center">
                        <a href="{{ route('productos.categoria', $cat->slug) }}" class="category_image">
                            <img src="{{ $imagenCategoria }}" alt="{{ $cat->nombre }}">
                        </a>
                        <div class="category_content">

                            <h3 class="category_title">
                                <a href="{{ route('productos.categoria', $cat->slug) }}">
                                    {{ $cat->nombre }}
                                </a>
                            </h3>

                            <!-- <span class="category_meta">
                                {{ $cat->productos_count ?? 0 }}
                                productos disponibles
                            </span> -->

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>