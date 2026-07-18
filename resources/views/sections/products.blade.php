@php
$mostrarPrecio = $config['producto_mostrar_precio'] ?? 1;
$mostrarMarca = $config['producto_mostrar_marca'] ?? 1;
@endphp

<section class="product_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="section_heading text-center mb-5">
            <div class="section_heading_title">
                <span></span>
                <small>NUESTROS PRODUCTOS</small>
                <span></span>
            </div>
        </div>

        <div class="tab-content has_multy_carousel">
            <div id="painkiller_tab" class="tab-pane active">
                <div class="product_carousel arrow_ycenter">
                    <div class="slideshow4_slider medical_product_group" data-slick='{"dots":false}'>
                        @foreach($productos as $producto)

                        @php
                        $variante = $producto->variantes->first();
                        // Obtenemos las imágenes y tomamos solo las primeras 2 para el efecto hover
                        $imagenes = $variante && $variante->imagenes->count()
                        ? $variante->imagenes->sortBy('orden')->take(2)
                        : collect();
                        @endphp

                        <div class="item">
                            <a href="{{ route('producto.show',$producto->slug) }}" class="product_link_card">
                                <div class="medical_product_item">

                                    @if($mostrarMarca)
                                    <span class="brand_badge">
                                        {{ $producto->marca->nombre ?? 'Sin marca' }}
                                    </span>
                                    @endif

                                    <div class="slideshow_producto">
                                        @if($imagenes->count())
                                        @foreach($imagenes as $key => $img)
                                        <img src="{{ asset($img->url) }}"
                                            class="img_producto {{ $key === 0 ? 'img_principal' : 'img_hover' }}"
                                            alt="{{ $producto->nombre }}">
                                        @endforeach

                                        {{-- Si solo hay 1 imagen, duplicamos la principal con clase hover para evitar espacios vacíos --}}
                                        @if($imagenes->count() === 1)
                                        <img src="{{ asset($imagenes->first()->url) }}" class="img_producto img_hover"
                                            alt="{{ $producto->nombre }}">
                                        @endif

                                        @else
                                        {{-- Imagen por defecto si no hay imágenes --}}
                                        <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                            class="img_producto img_principal" alt="{{ $producto->nombre }}">
                                        <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                            class="img_producto img_hover" alt="{{ $producto->nombre }}">
                                        @endif
                                    </div>

                                    <div class="item_content">
                                        <h3 class="item_title">
                                            {{ $producto->nombre }}
                                        </h3>

                                        <div class="category_text">
                                            {{ $producto->categorias->first()->nombre ?? 'Sin categoría' }}
                                        </div>

                                        @if($mostrarPrecio)
                                        <div class="price_box">
                                            @if($variante)
                                            @if($variante->precio_oferta)
                                            <span class="old_price">
                                                S/. {{ number_format($variante->precio,2) }}
                                            </span>
                                            <span class="price_text">
                                                S/. {{ number_format($variante->precio_oferta,2) }}
                                            </span>
                                            @else
                                            <span class="price_text">
                                                S/. {{ number_format($variante->precio,2) }}
                                            </span>
                                            @endif
                                            @else
                                            <span class="price_text">
                                                Sin precio
                                            </span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </a>
                        </div>

                        @endforeach

                    </div>

                    <div class="carousel_nav">
                        <button type="button" class="ss4_left_arrow">
                            <i class="fal fa-angle-left"></i>
                        </button>
                        <button type="button" class="ss4_right_arrow">
                            <i class="fal fa-angle-right"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<style>
    /* Imagen más grande */
.medical_product_item .slideshow_producto{
    height: 320px;          /* antes probablemente 250-280px */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

/* La imagen ocupa casi todo el espacio */
.medical_product_item .img_producto{
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: .35s ease;
}

/* Reduce un poco el espacio del contenido */
.medical_product_item .item_content{
    padding: 14px 16px 18px;
}
</style>