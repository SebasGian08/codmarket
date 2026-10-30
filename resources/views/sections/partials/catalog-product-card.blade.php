@php
$variante = $producto->primera_variante_con_imagen;
$imagenes = $variante && $variante->imagenes->count()
    ? $variante->imagenes->sortBy('orden')->take(2)
    : collect();
$descripcionProducto = trim(strip_tags((string) (
    $producto->descripcion_corta ?: $producto->descripcion
)));
@endphp

<li class="catalogo-productos-item">
    <article class="catalogo-producto-card">
        <div class="catalogo-producto-imagen catalogo-producto-imagen-carrusel">
            <div class="slideshow1_slider catalogo-producto-imagen-slider" data-slick='{"arrows":false}'>
                @forelse($imagenes as $imagen)
                <div>
                    <img src="{{ asset($imagen->url) }}" alt="{{ $producto->nombre }}" loading="lazy">
                </div>
                @empty
                <div>
                    <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                        alt="{{ $producto->nombre }}" loading="lazy">
                </div>
                @endforelse
            </div>

            @include('sections.partials.product-quick-view-button', [
                'producto' => $producto,
                'varianteInicial' => $variante,
            ])

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

                <a href="{{ route('producto.show', $producto->slug) }}" class="catalogo-producto-boton">
                    Ver producto <i class="fal fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </article>
</li>
