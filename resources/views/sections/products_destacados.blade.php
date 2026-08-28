@php
$mostrarPrecio = $config['producto_mostrar_precio'] ?? 1;
$mostrarMarca = $config['producto_mostrar_marca'] ?? 1;
@endphp

<section class="bestseller_section sec_ptb_50 pb-0 clearfix">
    <div class="container maxw_1460">
        <div class="row mb_50 align-items-center justify-content-between">

            <!-- TÍTULO -->
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">

                <div class="section_heading mb-0">

                    <div class="section_heading_title justify-content-start">

                        <span></span>

                        <small>
                            {{ $config['seccion_destacados_titulo'] ?? 'Más vendidos' }}
                        </small>

                        <span></span>

                    </div>

                </div>

            </div>


            <!-- CATEGORÍAS -->
            <div class="col-lg-8">

                <ul class="supermarket_tab_nav ul_li_right nav clearfix flex-wrap" role="tablist">

                    <li>
                        <a class="active" data-toggle="tab" href="#top_tab">
                            Todos
                        </a>
                    </li>

                    @foreach($categoriasProductos as $categoria)

                    <li>
                        <a data-toggle="tab" href="#categoria_{{ $categoria->id_categoria }}">
                            {{ $categoria->nombre }}
                        </a>
                    </li>

                    @endforeach

                </ul>

            </div>

        </div>
        <div class="tab-content">

            <!-- TODOS -->
            <div id="top_tab" class="tab-pane active">
                <ul class="supermarket_product_columns has_3columns ul_li bg_white clearfix">
                    @foreach($productos as $producto)
                    @php
                    $variante = $producto->variantes->first();
                    $imagenes = $variante && $variante->imagenes->count() ? $variante->imagenes->sortBy('orden') :
                    collect();
                    $imagenPrincipal = $imagenes->first() ? asset($imagenes->first()->url) :
                    asset('assets/images/tienda_virtual/default.png');
                    @endphp
                    <li>
                        <div class="product_card_wrapper">
                            <a href="{{ route('producto.show',$producto->slug) }}" class="product_card">
                                <div class="product_image">
                                    <button class="product_favorite" type="button">
                                        <i class="far fa-heart"></i>
                                    </button>

                                    @if($producto->destacado)
                                    <span class="product_badge">BESTSELLER</span>
                                    @endif

                                    <div class="slideshow1_slider" data-slick='{"arrows":false}'>
                                        @if($imagenes->count())
                                        @foreach($imagenes as $img)
                                        <div><img src="{{ asset($img->url) }}" alt="{{ $producto->nombre }}"></div>
                                        @endforeach
                                        @else
                                        <div><img src="{{ asset('assets/images/tienda_virtual/default.png') }}"></div>
                                        @endif
                                    </div>
                                </div>

                                <div class="product_body">
                                    <!-- Rating abajo de la foto, estilo minimalista -->
                                    <div class="product_rating_inline">
                                        <span class="rating_num">4.8</span><i class="fas fa-star text-warning"></i>
                                        <span class="rating_count">({{ rand(20, 150) }})</span>
                                    </div>

                                    <h3 class="product_title">{{ strtoupper($producto->nombre) }}</h3>

                                    <div class="product_category_badge">
                                        {{ $producto->categorias->first()->nombre ?? 'General' }}
                                    </div>

                                    @if($mostrarPrecio)
                                    <div class="product_price">
                                        @if($variante)
                                        @if($variante->precio_oferta)
                                        <span class="current_price">
                                            S/. {{ number_format($variante->precio_oferta,2) }}
                                        </span>
                                        <span class="old_price">
                                            S/. {{ number_format($variante->precio,2) }}
                                        </span>
                                        @else
                                        <span class="current_price">
                                            S/. {{ number_format($variante->precio,2) }}
                                        </span>
                                        @endif
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- CATEGORÍAS (Mismo diseño premium que el "Todos") -->
            @foreach($categoriasProductos as $categoria)
            <div id="categoria_{{ $categoria->id_categoria }}" class="tab-pane fade">
                <ul class="supermarket_product_columns has_3columns ul_li bg_white clearfix">
                    @forelse($categoria->productos as $producto)
                    @php
                    $variante = $producto->variantes->first();
                    $imagenes = $variante && $variante->imagenes->count() ? $variante->imagenes->sortBy('orden') :
                    collect();
                    @endphp
                    <li>
                        <div class="product_card_wrapper">
                            <a href="{{ route('producto.show',$producto->slug) }}" class="product_card">
                                <div class="product_image">
                                    <button class="product_favorite" type="button">
                                        <i class="far fa-heart"></i>
                                    </button>

                                    @if($producto->destacado)
                                    <span class="product_badge">BESTSELLER</span>
                                    @endif

                                    <div class="slideshow1_slider" data-slick='{"arrows":false}'>
                                        @if($imagenes->count())
                                        @foreach($imagenes as $img)
                                        <div><img src="{{ asset($img->url) }}" alt="{{ $producto->nombre }}"></div>
                                        @endforeach
                                        @else
                                        <div><img src="{{ asset('assets/images/tienda_virtual/default.png') }}"></div>
                                        @endif
                                    </div>
                                </div>

                                <div class="product_body">
                                    <div class="product_rating_inline">
                                        <span class="rating_num">4.8</span><i class="fas fa-star text-warning"></i>
                                        <span class="rating_count">({{ rand(20, 150) }})</span>
                                    </div>

                                    <h3 class="product_title">{{ strtoupper($producto->nombre) }}</h3>

                                    <div class="product_category_badge">
                                        {{ $producto->categorias->first()->nombre ?? 'General' }}
                                    </div>

                                    @if($mostrarPrecio)
                                    <div class="product_price">
                                        @if($variante)
                                        @if($variante->precio_oferta)
                                        <span class="current_price">
                                            S/. {{ number_format($variante->precio_oferta,2) }}
                                        </span>
                                        <span class="old_price">
                                            S/. {{ number_format($variante->precio,2) }}
                                        </span>
                                        @else
                                        <span class="current_price">
                                            S/. {{ number_format($variante->precio,2) }}
                                        </span>
                                        @endif
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </li>
                    @empty
                    <li class="w-100 text-center p-5 text-muted">No hay productos en esta categoría</li>
                    @endforelse
                </ul>
            </div>
            @endforeach

        </div>
    </div>
</section>