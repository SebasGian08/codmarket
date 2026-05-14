<section class="product_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="row mb_30 align-items-center justify-content-lg-between">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="medical_section_title">
                    <h2 class="title_text mb-0">Nuevos Productos</h2>
                </div>
            </div>
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
    </div>
</section>

<section class="bestseller_section sec_ptb_50 pb-0 clearfix">
    <div class="container maxw_1460">

        <div class="row mb_50 align-items-center justify-content-between">

            <!-- TÍTULO -->
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">

                <div class="medical_section_title">
                    <h2 class="title_text mb-0">
                        Más vendidos
                    </h2>
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

                    $imagenes = $variante && $variante->imagenes->count()
                    ? $variante->imagenes->sortBy('orden')
                    : collect();

                    $imagenPrincipal = $imagenes->first()
                    ? asset($imagenes->first()->url)
                    : asset('assets/images/tienda_virtual/default.png');
                    @endphp

                    <li>

                        <div class="supermarket_product_listlayout">
                            {{-- BADGES --}}
                            <!-- @if($producto->nuevo || $producto->destacado)

                            <ul class="product_label ul_li clearfix">

                                {{-- NUEVO --}}
                                @if($producto->nuevo)
                                <li class="label_new">
                                    Nuevo
                                </li>
                                @endif

                                {{-- DESTACADO --}}
                                @if($producto->destacado)
                                <li class="label_featured">
                                    <i class="fas fa-star"></i>
                                </li>
                                @endif

                            </ul>

                            @endif -->

                            <!-- IMÁGENES -->
                            <div class="slideshow1_slider item_image" data-slick='{"arrows": false}'>

                                @if($imagenes->count())

                                @foreach($imagenes as $img)
                                <div class="item">
                                    <img src="{{ asset($img->url) }}" alt="{{ $producto->nombre }}">
                                </div>
                                @endforeach

                                @else

                                <div class="item">
                                    <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                        alt="{{ $producto->nombre }}">
                                </div>

                                @endif

                            </div>

                            <!-- CONTENIDO -->
                            <div class="item_content">

                                <!-- MARCA -->
                                <span class="item_type text-uppercase" style="color: black;">
                                    {{ $producto->marca->nombre ?? 'Sin marca' }}
                                </span>

                                <!-- NOMBRE -->
                                <h3 class="item_title">
                                    <a href="{{ route('producto.show', $producto->slug) }}">
                                        {{ $producto->nombre }}
                                    </a>
                                </h3>

                                <!-- CATEGORÍA -->
                                <div class="category_text mb-2">
                                    {{ $producto->categorias->first()->nombre ?? 'Sin categoría' }}
                                </div>

                                <!-- PRECIO -->
                                <div class="price_box mb-3">

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

                                    <span class="price_text">
                                        Sin precio
                                    </span>

                                    @endif

                                </div>

                            </div>
                        </div>
                    </li>

                    @endforeach

                </ul>

            </div>

            <!-- CATEGORÍAS -->
            @foreach($categoriasProductos as $categoria)

            <div id="categoria_{{ $categoria->id_categoria }}" class="tab-pane fade">

                <ul class="supermarket_product_columns has_3columns ul_li bg_white clearfix">

                    @forelse($categoria->productos as $producto)

                    @php
                    $variante = $producto->variantes->first();

                    $imagenes = $variante && $variante->imagenes->count()
                    ? $variante->imagenes->sortBy('orden')
                    : collect();

                    $imagenPrincipal = $imagenes->first()
                    ? asset($imagenes->first()->url)
                    : asset('assets/images/tienda_virtual/default.png');
                    @endphp

                    <li>

                        <div class="supermarket_product_listlayout">

                            <!-- IMÁGENES -->
                            <div class="slideshow1_slider item_image" data-slick='{"arrows": false}'>

                                @if($imagenes->count())

                                @foreach($imagenes as $img)
                                <div class="item">
                                    <img src="{{ asset($img->url) }}" alt="{{ $producto->nombre }}">
                                </div>
                                @endforeach

                                @else

                                <div class="item">
                                    <img src="{{ $imagenPrincipal }}" alt="{{ $producto->nombre }}">
                                </div>

                                @endif

                            </div>

                            <!-- CONTENIDO -->
                            <div class="item_content">

                                <!-- MARCA -->
                                <span class="item_type text-uppercase" style="color: black;">
                                    {{ $producto->marca->nombre ?? 'Sin marca' }}
                                </span>

                                <!-- NOMBRE -->
                                <h3 class="item_title">
                                    <a href="{{ route('producto.show', $producto->slug) }}">
                                        {{ $producto->nombre }}
                                    </a>
                                </h3>

                                <!-- CATEGORÍA -->
                                <div class="category_text mb-2">
                                    {{ $producto->categorias->first()->nombre ?? 'Sin categoría' }}
                                </div>

                                <!-- PRECIO -->
                                <div class="price_box mb-3">

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

                                    <span class="price_text">
                                        Sin precio
                                    </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </li>

                    @empty

                    <li class="w-100 text-center p-4">
                        No hay productos en esta categoría
                    </li>

                    @endforelse

                </ul>

            </div>

            @endforeach

        </div>

    </div>
</section>