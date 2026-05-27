<section class="product_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="section_title text-center mb-4">
            <h4>Nuestros Productos</h4>
            <div class="title_line"></div>
        </div>

        <div class="tab-content has_multy_carousel">
            <div id="painkiller_tab" class="tab-pane active">
                <div class="product_carousel arrow_ycenter">
                    <div class="slideshow4_slider medical_product_group" data-slick='{"dots": false}'>

                        @foreach($productos as $producto)

                        @php
                        $variante = $producto->variantes->first();

                        $imagen = $variante && $variante->imagenes->count()
                        ? asset($variante->imagenes->sortBy('orden')->first()->url)
                        : asset('assets/images/tienda_virtual/default.png')
                        @endphp

                        <div class="item">
                            <a href="{{ route('producto.show', $producto->slug) }}" class="product_link_card">
                                <div class="medical_product_item">

                                    <!-- Marca -->
                                    <span class="brand_badge">
                                        {{ $producto->marca->nombre ?? 'Sin marca' }}
                                    </span>

                                    <!-- Imagen -->
                                    <div class="item_image">
                                        <img src="{{ $imagen }}" alt="{{ $producto->nombre }}">
                                    </div>

                                    <div class="item_content">

                                        <!-- Título -->
                                        <h3 class="item_title">
                                            <a href="{{ route('producto.show', $producto->slug) }}">
                                                {{ $producto->nombre }}
                                            </a>
                                        </h3>

                                        <div class="category_text">
                                            {{ $categorias->first()->nombre ?? 'Falta categoría' }}
                                        </div>

                                        <!-- Precio -->
                                        <div class="price_box">

                                            @if($variante)

                                            @if($variante->precio_oferta)
                                            <span class="old_price">
                                                S/. {{ number_format($variante->precio, 2) }}
                                            </span>

                                            <span class="price_text">
                                                S/. {{ number_format($variante->precio_oferta, 2) }}
                                            </span>
                                            @else
                                            <span class="price_text">
                                                S/. {{ number_format($variante->precio, 2) }}
                                            </span>
                                            @endif

                                            @else
                                            <span class="price_text">Sin precio</span>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </a>
                        </div>

                        @endforeach

                    </div>
                    <div class="carousel_nav">
                        <button type="button" class="ss4_left_arrow"><i class="fal fa-angle-left"></i></button>
                        <button type="button" class="ss4_right_arrow"><i class="fal fa-angle-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


