@php
$mostrarPrecio = $config['producto_mostrar_precio'] ?? 1;
$mostrarMarca = $config['producto_mostrar_marca'] ?? 1;
@endphp
<section class="product_section sec_ptb_50 clearfix">
    <div class="container">

        @if(($config['seccion_productos_mostrar_encabezado'] ?? 1) == 1)
        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_productos_titulo'] ?? 'NUESTROS PRODUCTOS' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_productos_descripcion'] ?? 'Explora nuestra variedad de productos seleccionados para ti') !!}
            </p>
        </div>
        @endif

        <div class="tab-content has_multy_carousel">
            <div id="painkiller_tab" class="tab-pane active">
                <div class="product_carousel arrow_ycenter">
                    <div class="slideshow4_slider medical_product_group" data-slick='{"dots":false}'>
                        @foreach($productos as $producto)

                        @php
                        $variante = $producto->primera_variante_con_imagen;
                        $imagenes = $variante && $variante->imagenes->count()
                        ? $variante->imagenes->sortBy('orden')->take(2)
                        : collect();
                        $descripcionProducto = trim(strip_tags((string) (
                            $producto->descripcion_corta ?: $producto->descripcion
                        )));
                        @endphp

                        <div class="item">
                            <article class="catalogo-producto-card product_link_card">
                                <div class="catalogo-producto-imagen slideshow_producto">
                                    @if($imagenes->count())
                                    @foreach($imagenes as $key => $img)
                                    <img src="{{ asset($img->url) }}"
                                        class="img_producto {{ $key === 0 ? 'img_principal' : 'img_hover' }}"
                                        alt="{{ $producto->nombre }}" loading="lazy">
                                    @endforeach

                                    @if($imagenes->count() === 1)
                                    <img src="{{ asset($imagenes->first()->url) }}"
                                        class="img_producto img_hover" alt="{{ $producto->nombre }}" loading="lazy">
                                    @endif
                                    @else
                                    <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                        class="img_producto img_principal" alt="{{ $producto->nombre }}" loading="lazy">
                                    <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                        class="img_producto img_hover" alt="{{ $producto->nombre }}" loading="lazy">
                                    @endif

                                    <span class="catalogo-producto-etiquetas">
                                        @if($producto->destacado)
                                        <span class="catalogo-producto-etiqueta catalogo-producto-etiqueta-destacado">
                                            Más vendido
                                        </span>
                                        @endif
                                        @if($producto->nuevo)
                                        <span class="catalogo-producto-etiqueta catalogo-producto-etiqueta-nuevo">
                                            Nuevo
                                        </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="catalogo-producto-contenido">
                                    <div class="catalogo-producto-categoria">
                                        {{ $producto->categorias->first()->nombre ?? 'Producto' }}
                                    </div>

                                    <h2 class="catalogo-producto-titulo">
                                        <a href="{{ route('producto.show', $producto->slug) }}">
                                            {{ $producto->nombre }}
                                        </a>
                                    </h2>

                                    @if($mostrarMarca && $producto->marca)
                                    <div class="catalogo-producto-marca">{{ $producto->marca->nombre }}</div>
                                    @endif

                                    <p class="catalogo-producto-descripcion">
                                        {{ $descripcionProducto !== '' ? \Illuminate\Support\Str::limit($descripcionProducto, 92) : 'Conoce más sobre este producto.' }}
                                    </p>

                                    <div class="catalogo-producto-pie">
                                        @if($mostrarPrecio)
                                        <div class="catalogo-producto-precio">
                                            <span class="catalogo-producto-precio-label">Precio</span>
                                            @if($variante)
                                                @if($variante->precio_oferta !== null)
                                                <span class="catalogo-producto-precio-anterior">
                                                    S/ {{ number_format($variante->precio, 2) }}
                                                </span>
                                                <span class="catalogo-producto-precio-actual">
                                                    S/ {{ number_format($variante->precio_oferta, 2) }}
                                                </span>
                                                @else
                                                <span class="catalogo-producto-precio-actual">
                                                    S/ {{ number_format($variante->precio, 2) }}
                                                </span>
                                                @endif
                                            @else
                                            <span class="catalogo-producto-precio-actual">Consultar</span>
                                            @endif
                                        </div>
                                        @endif

                                        <a href="{{ route('producto.show', $producto->slug) }}"
                                            class="catalogo-producto-boton">
                                            Ver producto <i class="fal fa-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
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
