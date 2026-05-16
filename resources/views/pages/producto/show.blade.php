@extends('layouts.appweb')

@section('title')
{{ $producto->nombre }} - {{ $empresa->nombre ?? 'Mi Empresa' }}
@endsection

@section('content')

<div class="container breadcrumb-wrap">
    <div class="f2_breadcrumb_nav_wrap sec_ptb_30" style="margin-top: 40px;">
        <ul class="ce_breadcrumb_nav ul_li clearfix">
            <li><a href="{{ url('/') }}">Inicio</a></li>

            @if($categorias->count())
            <li>{{ $categorias->first()->nombre }}</li>
            @else
            <li>Productos</li>
            @endif

            <li>{{ $producto->nombre }}</li>
        </ul>
    </div>
</div>

<section class="details_section shop_details clearfix mt-5">
    <div class="container">
        <div class="row mb_100 justify-content-lg-between">

            <!-- IMÁGENES -->
            <div class="col-lg-5 col-md-5">
                <div class="shop_details_image" style="border: 1px solid #eee; padding: 10px;">

                    <div class="tab-content">
                        @forelse($imagenes as $key => $img)
                        <div id="tab_{{ $key + 1 }}" class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}">
                            <div class="zoom-container">
                                <img src="{{ asset($img->url) }}" alt="{{ $producto->nombre }}">
                            </div>
                        </div>
                        @empty
                        <div class="tab-pane fade show active">
                            <img src="{{ asset('assets/images/tienda_virtual/default.png') }}" alt="sin imagen">
                        </div>
                        @endforelse
                    </div>

                    <ul class="nav ul_li clearfix" role="tablist">
                        @forelse($imagenes as $key => $img)
                        <li>
                            <a class="{{ $key == 0 ? 'active' : '' }}" data-toggle="tab" href="#tab_{{ $key + 1 }}">
                                <img src="{{ asset($img->url) }}"
                                    style="width:100px;height:100px;object-fit:cover;border:1px solid #eee;padding:2px;">
                            </a>
                        </li>
                        @empty
                        <li>
                            <a class="active" data-toggle="tab">
                                <img src="{{ asset('assets/images/tienda_virtual/default.png') }}">
                            </a>
                        </li>
                        @endforelse
                    </ul>

                </div>
            </div>

            <!-- DETALLES -->
            <div class="col-lg-7 col-md-7">
                <div class="shop_details_content">

                    <!-- MARCA + SKU -->
                    <div class="d-flex justify-content-between align-items-center mb-2">

                        <span class="text-muted" style="font-size: 14px; letter-spacing: 1px;">
                            {{ $producto->marca->nombre ?? 'Sin marca' }}
                        </span>

                        <span style="font-size: 14px;">
                            SKU: {{ $varianteActiva->sku }}
                        </span>

                    </div>

                    <h2 class="item_title">{{ $producto->nombre }}</h2>

                    @if($producto->categorias->count())

                    <div class="product-categories mb-3">

                        @foreach($producto->categorias as $cat)
                        <a href="" class="badge-category">
                            {{ $cat->nombre }}
                        </a>
                        @endforeach

                    </div>

                    @endif

                    <hr>

                    <!-- PRECIOS -->
                    @php
                    $precioRegular = $varianteActiva->precio;
                    $precioPromo = $varianteActiva->precio_oferta ?? $varianteActiva->precio;

                    $descuento = $precioRegular > 0
                    ? round((($precioRegular - $precioPromo) / $precioRegular) * 100)
                    : 0;
                    @endphp

                    <div class="product-price-box mb-3">

                        <div class="price-row">
                            <span class="label">Precio regular</span>
                            <span class="old-price">S/ {{ number_format($precioRegular, 2) }}</span>
                        </div>

                        <div class="price-row">
                            <span class="label">Precio promocional</span>

                            <div class="d-flex align-items-center gap-2">
                                <span class="new-price">S/ {{ number_format($precioPromo, 2) }}</span>

                                @if($descuento > 0)
                                <div class="badge-discount-black">-{{ $descuento }}%</div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <p>{!! $producto->descripcion_corta ?? 'Sin descripción' !!}</p>

                    <hr>

                    <!-- VARIANTES -->
                    @foreach($producto->variantes as $var)
                    @php
                    $imgVar = $var->imagenes->firstWhere('principal', 1)
                    ?? $var->imagenes->first();
                    @endphp

                    @if($imgVar)
                    <button type="button"
                        class="mb-4 thumbnail-border {{ $varianteActiva->id_variante == $var->id_variante ? 'thumbnail-border-selected' : '' }}"
                        onclick="cambiarVariante({{ $var->id_variante }})">

                        <img src="{{ asset($imgVar->url) }}" width="52" height="52">
                    </button>
                    @endif
                    @endforeach

                    <!-- CANTIDAD -->
                    <ul class="btns_group_1 ul_li mb_30 clearfix product-actions">

                        <!--  <li>
                            <div class="quantity_input custom-qty">
                                <span class="input_number_decrement">–</span>
                                <input id="cantidad" class="input_number" type="text" value="1">
                                <span class="input_number_increment">+</span>
                            </div>
                        </li>

                        <li>
                            <a class="btn-cart" href="#!">
                                <i class="fal fa-shopping-bag mr-2"></i> Agregar
                            </a>
                        </li> -->

                        @php
                        $telefono = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');

                        $mensaje = urlencode(
                        "Hola, quiero este producto:\n" .
                        "• Producto: {$producto->nombre}\n" .
                        "• Precio: S/" . number_format($precioPromo, 2) . "\n" .
                        "• Cantidad: "
                        );
                        @endphp

                        <li>
                            <a class="btn-whatsapp" target="_blank" onclick="
                                        let input = document.getElementById('cantidad');
                                        let cantidad = input ? input.value : 1;

                                        let base = `Hola, quiero este producto:
                            • Producto: {{ $producto->nombre }}
                            • Precio: S/{{ number_format($precioPromo,2) }}
                            • Cantidad: ${cantidad}`;

                                        this.href = 'https://wa.me/{{ $telefono }}?text=' + encodeURIComponent(base);
                                ">
                                <i class="fab fa-whatsapp mr-2"></i> Pedir directo
                            </a>
                        </li>
                    </ul>

                    <div class="benefits-box mt-4">

                        <div class="benefit-card">
                            <div class="benefit-icon shipping">
                                <i class="fas fa-motorcycle"></i>
                            </div>

                            <div class="benefit-content">
                                <h4>Envío a domicilio</h4>
                                <span>Disponible en Lima y provincias</span>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon fast">
                                <i class="fas fa-shipping-fast"></i>
                            </div>

                            <div class="benefit-content">
                                <h4>Entregas en 24h</h4>
                                <span>Despacho rápido y seguro</span>
                            </div>
                        </div>

                        <!--  <div class="benefit-card">
                            <div class="benefit-icon secure">
                                <i class="fas fa-shield-check"></i>
                            </div>

                            <div class="benefit-content">
                                <h4>Compra segura</h4>
                                <span>Pagos protegidos y confiables</span>
                            </div>
                        </div>

                        <div class="benefit-card">
                            <div class="benefit-icon support">
                                <i class="fas fa-headset"></i>
                            </div>

                            <div class="benefit-content">
                                <h4>Atención personalizada</h4>
                                <span>Soporte directo por WhatsApp</span>
                            </div>
                        </div> -->

                    </div>
                </div>
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="col-lg-12 mt-4">
                <div class="product_description_card">

                    <div class="product_description_header">
                        <h2 class="product_title">
                            Descripción del producto
                        </h2>
                    </div>

                    <div class="product_description_body">
                        {!! $producto->descripcion ?? 'Sin descripción' !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="product_section sec_ptb_50 clearfix">
    <div class="container maxw_1600">

        <div class="row mb_30 align-items-center justify-content-lg-between">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="medical_section_title">
                    <h2 class="title_text mb-0">Productos Relacionados</h2>
                </div>
            </div>
        </div>

        <div class="product_carousel arrow_ycenter">
            <div class="slideshow4_slider medical_product_group" data-slick='{"dots": false}'>

                @foreach($relacionados as $producto)

                @php
                $variante = $producto->variantes->first();

                $imagen = $variante && $variante->imagenes->count()
                ? asset($variante->imagenes->sortBy('orden')->first()->url)
                : asset('assets/images/tienda_virtual/default.png');
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

                            <!-- Categoría -->
                            <div class="category_text">
                                {{ $producto->categorias->first()->nombre ?? 'Sin categoría' }}
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

            <!-- Navegación -->
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
</section>
{{-- Suscripcion --}}
@include('sections.suscripcion')

<script>
document.querySelectorAll('.zoom-container').forEach(container => {
    const img = container.querySelector('img');

    container.addEventListener('mousemove', (e) => {
        const rect = container.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        img.style.transformOrigin = `${x}% ${y}%`;
        img.style.transform = "scale(2)";
    });

    container.addEventListener('mouseleave', () => {
        img.style.transform = "scale(1)";
        img.style.transformOrigin = "center";
    });
});

function cambiarVariante(id) {
    const url = new URL(window.location.href);
    url.searchParams.set('variante', id);
    window.location.href = url.toString();
}
</script>

@endsection