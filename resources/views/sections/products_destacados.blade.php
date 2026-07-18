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

                                    <div class="product_price">
                                        @if($variante)
                                        @if($variante->precio_oferta)
                                        <span class="current_price">S/.
                                            {{ number_format($variante->precio_oferta,2) }}</span>
                                        <span class="old_price">S/. {{ number_format($variante->precio,2) }}</span>
                                        @else
                                        <span class="current_price">S/. {{ number_format($variante->precio,2) }}</span>
                                        @endif
                                        @endif
                                    </div>
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

                                    <div class="product_price">
                                        @if($variante)
                                        @if($variante->precio_oferta)
                                        <span class="current_price">S/.
                                            {{ number_format($variante->precio_oferta,2) }}</span>
                                        <span class="old_price">S/. {{ number_format($variante->precio,2) }}</span>
                                        @else
                                        <span class="current_price">S/. {{ number_format($variante->precio,2) }}</span>
                                        @endif
                                        @endif
                                    </div>
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

<style>
/*=========================================
CARD PREMIUM MINIMALISTA (Estilo Boutique)
=========================================*/

.product_card_wrapper {
    padding: 10px;
    /* Separación sutil entre tarjetas */
}

.product_card {
    display: block;
    background: #fff;
    text-decoration: none !important;
    color: #111;
    transition: transform 0.3s ease;
}

.product_image {
    position: relative;
    overflow: hidden;
    background: #f7f7f7;
}

.product_image img {
    width: 100%;
    aspect-ratio: 3 / 4;
    /* Proporción vertical como la imagen */
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.15, 1, 0.3, 1);
}

.product_card:hover img {
    transform: scale(1.03);
    /* Zoom sutil premium */
}

/* BOTÓN FAVORITO TRANSPARENTE EN LA ESQUINA */
.product_favorite {
    position: absolute;
    right: 12px;
    top: 12px;
    z-index: 5;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.7);
    color: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: 0.3s;
    cursor: pointer;
}

.product_favorite:hover {
    background: #111;
    color: #fff;
}

/* BADGE ESTRECHO OSCURO (Top Izquierda) */
.product_badge {
    position: absolute;
    left: 0;
    top: 12px;
    background: #111;
    color: #fff;
    padding: 3px 8px;
    font-size: 9px;
    letter-spacing: 1px;
    font-weight: 600;
    z-index: 5;
    text-transform: uppercase;
}

/* CUERPO DEL TEXTO ALINEADO A LA IZQUIERDA */
.product_body {
    padding: 12px 0;
    text-align: left;
}

/* RATING INLINE MINIMALISTA */
.product_rating_inline {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #222;
    margin-bottom: 6px;
    border: 1px solid #ddd;
    display: inline-flex;
    padding: 2px 6px;
    border-radius: 3px;
}

.product_rating_inline i {
    font-size: 9px;
    color: #f39c12;
}

.product_rating_inline .rating_count {
    color: #888;
}

/* TÍTULO DEL PRODUCTO FINO */
.product_title {
    font-size: 13px;
    line-height: 1.4;
    font-weight: 400;
    letter-spacing: 0.5px;
    color: #333;
    margin-bottom: 6px;
    text-transform: uppercase;
}

/* ETIQUETA DE CATEGORÍA DELGADA */
.product_category_badge {
    display: inline-block;
    border: 1px solid #e5e5e5;
    padding: 3px 8px;
    font-size: 10px;
    color: #777;
    text-transform: capitalize;
    margin-bottom: 8px;
    border-radius: 2px;
}

/* PRECIOS */
.product_price {
    display: flex;
    align-items: center;
    gap: 8px;
}

.current_price {
    font-size: 14px;
    font-weight: 600;
    color: #111;
}

.old_price {
    font-size: 12px;
    color: #999;
    text-decoration: line-through;
}
</style>