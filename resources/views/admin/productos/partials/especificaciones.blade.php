@php
$especificaciones = collect($especificaciones ?? []);
$indiceSiguiente = max(1, $especificaciones->count());
@endphp

<div class="border-top mt-3 pt-3"
    data-product-specifications
    data-next-index="{{ $indiceSiguiente }}">
    <input type="hidden" name="especificaciones_present" value="1">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h6 class="fw-bold mb-1">Especificaciones del producto</h6>
            <small class="text-muted">Agrega datos que se mostrarán debajo de la descripción.</small>
        </div>

        <button type="button" class="btn btn-sm btn-outline-primary" data-add-product-specification>
            <i class="fa fa-plus"></i> Agregar
        </button>
    </div>

    <div data-product-specifications-list>
        @forelse($especificaciones as $index => $especificacion)
        @include('admin.productos.partials.especificacion-row', [
            'index' => $index,
            'especificacion' => $especificacion,
        ])
        @empty
        @include('admin.productos.partials.especificacion-row', [
            'index' => 0,
            'especificacion' => null,
        ])
        @endforelse
    </div>

    <template data-product-specification-template>
        @include('admin.productos.partials.especificacion-row', [
            'index' => '__INDEX__',
            'especificacion' => null,
        ])
    </template>
</div>
