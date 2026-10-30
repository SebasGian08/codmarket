@php
$previewVariants = $producto->variantes
    ->where('estado', 1)
    ->map(function ($variante) {
        return [
            'id' => (int) $variante->id_variante,
            'sku' => (string) $variante->sku,
            'price' => $variante->precio !== null ? (float) $variante->precio : null,
            'salePrice' => $variante->precio_oferta !== null ? (float) $variante->precio_oferta : null,
            'attributes' => $variante->atributos->map(function ($valor) {
                return [
                    'name' => $valor->atributo->nombre ?? 'Opción',
                    'value' => $valor->valor,
                ];
            })->values(),
            'images' => $variante->imagenes
                ->sortBy('orden')
                ->map(function ($imagen) {
                    return asset($imagen->url);
                })
                ->values(),
        ];
    })
    ->values();
@endphp

<button type="button"
    class="catalogo-vista-previa"
    data-preview-name="{{ $producto->nombre }}"
    data-preview-description="{{ trim(strip_tags((string) ($producto->descripcion_corta ?: $producto->descripcion))) }}"
    data-preview-url="{{ route('producto.show', $producto->slug) }}"
    data-preview-image="{{ $varianteInicial && $varianteInicial->imagenes->isNotEmpty()
        ? asset($varianteInicial->imagenes->sortBy('orden')->first()->url)
        : asset('assets/images/tienda_virtual/default.png') }}"
    data-preview-variant-id="{{ $varianteInicial->id_variante ?? '' }}"
    data-preview-featured="{{ $producto->destacado ? '1' : '0' }}"
    data-preview-new="{{ $producto->nuevo ? '1' : '0' }}"
    data-preview-variants='@json($previewVariants)'>
    <i class="fal fa-eye" aria-hidden="true"></i>
    Vista previa
</button>
