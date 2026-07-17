<section class="product_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="section_title text-center mb-4">
            <h4>Nuestros Productos</h4>
            <div class="title_line"></div>
        </div>

        <div class="tab-content has_multy_carousel">
            <div id="painkiller_tab" class="tab-pane active">
                <div class="product_carousel arrow_ycenter">

                    <!-- Carrusel Principal de Productos -->
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

                                    <!-- MARCA -->
                                    <span class="brand_badge">
                                        {{ $producto->marca->nombre ?? 'Sin marca' }}
                                    </span>

                                    <!-- GALERIA DE IMAGENES (EFECTO CSS HOVER) -->
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
                                    </div>

                                </div>
                            </a>
                        </div>

                        @endforeach

                    </div>

                    <!-- Navegación del carrusel principal -->
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
.slideshow_producto {
    width: 100%;
    height: 260px;
    /* <- AUMENTADO DE 180px A 260px (Cámbialo aquí si lo quieres aún más grande) */
    position: relative;
    overflow: hidden;
    display: block;
    background-color: #f9f9f9;
}

.slideshow_producto .img_producto {
    width: 100%;
    height: 100%;
    object-fit: contain;
    /* Ajusta la imagen sin deformarla */
    position: absolute;
    top: 0;
    left: 0;
    display: block;
    transition: opacity 0.4s ease-in-out, transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
    transform: scale(1);
    backface-visibility: hidden;
}

.slideshow_producto .img_principal {
    opacity: 1;
    z-index: 2;
}

.slideshow_producto .img_hover {
    opacity: 0;
    z-index: 1;
}

/* --- EFECTOS HOVER --- */
.product_link_card:hover .slideshow_producto .img_hover {
    opacity: 1;
    z-index: 3;
}

.product_link_card:hover .slideshow_producto .img_producto {
    transform: scale(1.05);
    /* Zoom elegante del 5% */
}

.product_link_card:hover .slideshow_producto .img_principal {
    opacity: 0;
}
</style>