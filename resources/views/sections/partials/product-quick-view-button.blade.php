@php
$precioVistaPrevia = $varianteInicial
    ? ($varianteInicial->precio_oferta ?? $varianteInicial->precio)
    : null;
$imagenesVistaPrevia = $varianteInicial
    ? $varianteInicial->imagenes->sortBy('orden')->map(function ($imagen) {
        return asset($imagen->url);
    })->values()->all()
    : [];
$imagenesVistaPrevia = $imagenesVistaPrevia ?: [asset('assets/images/tienda_virtual/default.png')];
@endphp

<button type="button"
    class="catalogo-vista-previa"
    data-preview-name="{{ $producto->nombre }}"
    data-preview-description="{{ trim(strip_tags((string) ($producto->descripcion_corta ?: $producto->descripcion))) }}"
    data-preview-url="{{ route('producto.show', $producto->slug) }}"
    data-preview-price="{{ $precioVistaPrevia !== null ? number_format((float) $precioVistaPrevia, 2, '.', '') : '' }}"
    data-preview-image="{{ $imagenesVistaPrevia[0] }}"
    data-preview-images="{{ json_encode($imagenesVistaPrevia) }}"
    data-preview-featured="{{ $producto->destacado ? '1' : '0' }}"
    data-preview-new="{{ $producto->nuevo ? '1' : '0' }}">
    <i class="fal fa-eye" aria-hidden="true"></i>
    Vista previa
</button>
