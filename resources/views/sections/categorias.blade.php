<section class="deals_section sec_ptb_50 clearfix">
    <div class="container maxw_1460">

        <div class="row align-items-center">

            <div class="col-lg-3 row mb_30 align-items-center justify-content-lg-between">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="medical_section_title">
                        <h2 class="title_text mb-0">
                            Categorías
                        </h2>
                    </div>

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

            <div class="slideshow5_slider row clearfix" data-slick='{"dots": false}'>

                @foreach($categorias as $cat)

                @php
                $imagenCategoria = !empty($cat->imagen)
                ? asset($cat->imagen)
                : asset('assets/images/tienda_virtual/default.png');
                @endphp

                <div class="item col">

                    <div class="category_card text-center">

                        <a href="#!" class="category_image">
                            <img src="{{ $imagenCategoria }}"
                                 alt="{{ $cat->nombre }}">
                        </a>

                        <div class="category_content">

                            <h3 class="category_title">
                                <a href="#!">
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