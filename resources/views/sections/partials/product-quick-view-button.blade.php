@php
$precioVistaPrevia = $varianteInicial
    ? ($varianteInicial->precio_oferta ?? $varianteInicial->precio)
    : null;
@endphp

<button type="button"
    class="catalogo-vista-previa"
    data-preview-name="{{ $producto->nombre }}"
    data-preview-description="{{ trim(strip_tags((string) ($producto->descripcion_corta ?: $producto->descripcion))) }}"
    data-preview-url="{{ route('producto.show', $producto->slug) }}"
    data-preview-price="{{ $precioVistaPrevia !== null ? number_format((float) $precioVistaPrevia, 2, '.', '') : '' }}"
    data-preview-image="{{ $varianteInicial && $varianteInicial->imagenes->isNotEmpty()
        ? asset($varianteInicial->imagenes->sortBy('orden')->first()->url)
        : asset('assets/images/tienda_virtual/default.png') }}"
    data-preview-featured="{{ $producto->destacado ? '1' : '0' }}"
    data-preview-new="{{ $producto->nuevo ? '1' : '0' }}">
    <i class="fal fa-eye" aria-hidden="true"></i>
    Vista previa
</button>
