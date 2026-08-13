@extends('layouts.appweb')

@section('title')
{{ $producto->nombre }} - {{ $empresa->nombre ?? 'Mi Empresa' }}
@endsection

@section('content')

@php
$mostrarPrecio = $config['producto_mostrar_precio'] ?? 1;
$mostrarMarca = $config['producto_mostrar_marca'] ?? 1;
@endphp

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

                    <div class="tab-content" id="galeriaTabContent">
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

                    <ul class="nav ul_li clearfix" role="tablist" id="galeriaTabList">
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

                        @if($mostrarMarca)
                        <span class="text-muted" style="font-size: 14px; letter-spacing: 1px;">
                            {{ $producto->marca->nombre ?? 'Sin marca' }}
                        </span>
                        @endif

                        <span style="font-size: 14px;" id="skuActivo">
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

                    @php
                    $precioRegular = $varianteActiva->precio;
                    $precioPromo = $varianteActiva->precio_oferta ?? $varianteActiva->precio;

                    $descuento = $precioRegular > 0
                    ? round((($precioRegular - $precioPromo) / $precioRegular) * 100)
                    : 0;
                    @endphp

                    <hr>

                    @if($mostrarPrecio)

                    <div class="product-price-box mb-3">

                        <div class="price-row">
                            <span class="label">Precio regular</span>
                            <span class="old-price" id="precioRegular">S/ {{ number_format($precioRegular, 2) }}</span>
                        </div>

                        <div class="price-row">
                            <span class="label">Precio promocional</span>

                            <div class="d-flex align-items-center gap-2">
                                <span class="new-price" id="precioPromo">
                                    S/ {{ number_format($precioPromo, 2) }}
                                </span>

                                @if($descuento > 0)
                                <div class="badge-discount-black" id="badgeDescuento">
                                    -{{ $descuento }}%
                                </div>
                                @else
                                <div class="badge-discount-black" id="badgeDescuento" style="display:none;"></div>
                                @endif
                            </div>
                        </div>

                    </div>

                    @endif

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
                        data-variante="{{ $var->id_variante }}"
                        onclick="cambiarVariante({{ $var->id_variante }})">

                        <img src="{{ asset($imgVar->url) }}" width="52" height="52">
                    </button>
                    @endif
                    @endforeach

                    <!-- ATRIBUTOS -->
                    @php
                    $atributosProducto = [];

                    foreach ($producto->variantes as $var) {
                    foreach ($var->atributos as $atVal) {
                    $nombre = $atVal->atributo->nombre ?? 'Atributo';
                    $atributosProducto[$nombre][$atVal->id_valor] = [
                    'valor' => $atVal->valor,
                    'variante_id' => $var->id_variante,
                    ];
                    }
                    }
                    @endphp

                    @if($atributosProducto)
                    <div class="product-attributes mb-3">

                        @foreach($atributosProducto as $nombre => $valores)

                        <div class="d-flex align-items-center flex-wrap gap-2 mb-2">

                            <span class="fw-semibold" style="min-width: 70px;">
                                {{ $nombre }}:
                            </span>

                            <div class="d-flex flex-wrap gap-2">
                                @foreach($valores as $idValor => $info)
                                <button type="button"
                                    class="attribute-chip {{ $varianteActiva->atributos->contains('id_valor', $idValor) ? 'attribute-chip-selected' : '' }}"
                                    data-valor-id="{{ $idValor }}"
                                    onclick="cambiarVariante({{ $info['variante_id'] }})">
                                    {{ $info['valor'] }}
                                </button>
                                @endforeach
                            </div>

                        </div>

                        @endforeach

                    </div>
                    @endif

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
                            <a class="btn-whatsapp" id="btnWhatsapp" target="_blank"
                                data-telefono="{{ $telefono }}"
                                data-producto="{{ $producto->nombre }}"
                                data-empresa="{{ $empresa->nombre ?? '' }}"
                                data-mostrar-precio="{{ $mostrarPrecio ? 1 : 0 }}"
                                onclick="enviarWhatsApp(this)">
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
                // Obtenemos las imágenes y tomamos solo las primeras 2 para el efecto hover
                $imagenes = $variante && $variante->imagenes->count()
                ? $variante->imagenes->sortBy('orden')->take(2)
                : collect();
                @endphp

                <div class="item">

                    <a href="{{ route('producto.show', $producto->slug) }}" class="product_link_card">

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

                                @if($imagenes->count() === 1)

                                <img src="{{ asset($imagenes->first()->url) }}" class="img_producto img_hover"
                                    alt="{{ $producto->nombre }}">

                                @endif

                                @else

                                <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                    class="img_producto img_principal" alt="{{ $producto->nombre }}">

                                <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                    class="img_producto img_hover" alt="{{ $producto->nombre }}">

                                @endif

                            </div>

                            <div class="item_content">

                                <div class="category_text mb-2">
                                    {{ $producto->categorias->first()->nombre ?? 'Sin categoría' }}
                                </div>

                                <h3 class="item_title mb-3">
                                    {{ $producto->nombre }}
                                </h3>

                                @if($mostrarPrecio)

                                <div class="price_box mb-3">

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

                                <div class="product_action">

                                    <a href="{{ route('producto.show', $producto->slug) }}"
                                        class="product_link_more">

                                        Ver detalles

                                        <i class="fal fa-arrow-right"></i>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </a>

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

@php
$mostrarSuscripcion = $config['home_mostrar_suscripcion'] ?? 1;
@endphp

@if($mostrarSuscripcion == 1)
@include('sections.suscripcion')
@endif

<script>
const DEFAULT_IMG = "{{ asset('assets/images/tienda_virtual/default.png') }}";

function initZoom() {
    document.querySelectorAll('.zoom-container').forEach(container => {
        const img = container.querySelector('img');
        if (!img) return;

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
}

function updateGaleria(imagenes) {
    const tabContent = document.getElementById('galeriaTabContent');
    const tabList = document.getElementById('galeriaTabList');
    if (!tabContent || !tabList) return;

    const lista = imagenes && imagenes.length ? imagenes : [{ url: DEFAULT_IMG }];

    tabContent.innerHTML = lista.map((img, i) => `
        <div id="tabv_${i}" class="tab-pane fade ${i === 0 ? 'show active' : ''}">
            <div class="zoom-container">
                <img src="${escAttr(img.url)}" alt="">
            </div>
        </div>
    `).join('');

    tabList.innerHTML = lista.map((img, i) => `
        <li>
            <a class="${i === 0 ? 'active' : ''}" data-toggle="tab" href="#tabv_${i}">
                <img src="${escAttr(img.url)}" style="width:100px;height:100px;object-fit:cover;border:1px solid #eee;padding:2px;">
            </a>
        </li>
    `).join('');

    initZoom();
}

function escAttr(t) {
    return String(t == null ? '' : t).replace(/"/g, '&quot;');
}

function formatearPrecio(n) {
    return Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function enviarWhatsApp(btn) {
    const input = document.getElementById('cantidad');
    const cantidad = input ? input.value : 1;

    const producto = btn.getAttribute('data-producto');
    const empresa = btn.getAttribute('data-empresa');

    let base = "Hola, " + (empresa ? "soy de " + empresa + ", quiero este producto:\n" : "quiero este producto:\n");

    base += "• Producto: " + producto + "\n";

    const skuEl = document.getElementById('skuActivo');
    const sku = skuEl ? skuEl.textContent.replace('SKU:', '').trim() : '';
    if (sku) base += "• SKU: " + sku + "\n";

    const atributos = Array.from(document.querySelectorAll('.attribute-chip-selected'))
        .map(chip => chip.textContent.trim())
        .filter(Boolean);
    if (atributos.length) base += "• Variante: " + atributos.join(', ') + "\n";

    if (btn.getAttribute('data-mostrar-precio') === '1') {
        const precio = document.getElementById('precioPromo');
        base += "• Precio: S/" + (precio ? precio.textContent.trim() : '') + "\n";
    }

    base += "• Cantidad: " + cantidad + "\n";
    base += "• Ver producto: " + window.location.href;

    btn.href = 'https://wa.me/' + btn.getAttribute('data-telefono') + '?text=' + encodeURIComponent(base);
}

function cambiarVariante(id) {
    id = parseInt(id, 10);
    if (isNaN(id) || id === window.currentVariantId) return;

    const url = new URL(window.location.href);
    url.searchParams.set('variante', id);

    fetch(url.pathname + '/variante/' + id, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            if (!data || data.error) return;

            window.currentVariantId = data.id;
            history.replaceState(null, '', url.toString());

            const skuEl = document.getElementById('skuActivo');
            if (skuEl) skuEl.textContent = 'SKU: ' + data.sku;

            const precioRegular = document.getElementById('precioRegular');
            const precioPromo = document.getElementById('precioPromo');
            const badge = document.getElementById('badgeDescuento');

            if (precioRegular) precioRegular.textContent = 'S/ ' + formatearPrecio(data.precio);
            if (precioPromo) precioPromo.textContent = 'S/ ' + formatearPrecio(data.precio_oferta != null ? data.precio_oferta : data.precio);

            if (badge) {
                if (data.descuento > 0) {
                    badge.textContent = '-' + data.descuento + '%';
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            }

            updateGaleria(data.imagenes);

            document.querySelectorAll('.attribute-chip').forEach(function(chip) {
                const idValor = parseInt(chip.getAttribute('data-valor-id'), 10);
                chip.classList.toggle('attribute-chip-selected', data.valores_ids.indexOf(idValor) !== -1);
            });

            document.querySelectorAll('.thumbnail-border').forEach(function(t) {
                const vid = parseInt(t.getAttribute('data-variante'), 10);
                t.classList.toggle('thumbnail-border-selected', vid === data.id);
            });
        })
        .catch(function() { });
}

window.currentVariantId = {{ $varianteActiva->id_variante }};
initZoom();
</script>

@endsection