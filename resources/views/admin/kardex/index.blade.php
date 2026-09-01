@extends('admin.layouts.app')

@section('title', 'Kardex de Inventario')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Kardex de Inventario</h4>
            <span class="page-title-category ms-2 small text-muted">Movimientos de stock</span>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.inventario.kardex') }}" class="row g-2 align-items-end kardex-filtros">
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Producto</label>
                    <select name="id_producto" id="kardexProducto" class="form-control">
                        <option value="">Todos los productos</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id_producto }}"
                            {{ $varianteSeleccionada && $varianteSeleccionada->id_producto == $producto->id_producto ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Variante / SKU</label>
                    <select name="id_variante" id="kardexVariante" class="form-control">
                        <option value="">Todas las variantes</option>
                        @if($varianteSeleccionada)
                        <option value="{{ $varianteSeleccionada->id_variante }}" selected>
                            [{{ $varianteSeleccionada->sku }}] {{ $varianteSeleccionada->producto->nombre ?? '' }}
                        </option>
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Tienda</label>
                    <select name="id_tienda" class="form-control">
                        <option value="">Todas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}" {{ $filtros['idTienda'] == $tienda->id_tienda ? 'selected' : '' }}>
                            {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Tipo de movimiento</label>
                    <select name="id_tipo_movimiento" class="form-control">
                        <option value="">Todos</option>
                        @foreach($tiposMovimiento as $tipo)
                        <option value="{{ $tipo->id_tipo_movimiento }}" {{ $filtros['idTipo'] == $tipo->id_tipo_movimiento ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold mb-1">Rango de fechas</label>
                    <div class="input-group">
                        <input type="date" name="fecha_desde" class="form-control" value="{{ $filtros['fechaDesde'] }}">
                        <span class="input-group-text">hasta</span>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ $filtros['fechaHasta'] }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-round w-100">
                        <i class="fa fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.inventario.kardex') }}" class="btn btn-secondary btn-round">
                        <i class="fa fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if($varianteSeleccionada)
            <div class="kardex-variante-info mb-3">
                <span class="badge bg-primary">SKU: {{ $varianteSeleccionada->sku }}</span>
                <span>{{ $varianteSeleccionada->producto->nombre ?? '' }}</span>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover" id="basic-datatables">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto / Variante</th>
                            <th>Tienda</th>
                            <th>Tipo</th>
                            <th>Documento</th>
                            <th>Concepto</th>
                            <th class="text-end">Entrada</th>
                            <th class="text-end">Salida</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientosPag as $fila)
                        @php
                            $m = $fila['mov'];
                            $cantidad = (int) $m->cantidad;
                            $esEntrada = $cantidad > 0;
                            $saldo = (int) $fila['saldo'];
                        @endphp
                        <tr>
                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-semibold">{{ $m->variante->producto->nombre ?? '—' }}</div>
                                <div class="small text-muted">[{{ $m->variante->sku ?? '' }}]</div>
                            </td>
                            <td>
                                @if($m->tienda)
                                <span class="badge bg-secondary">{{ $m->tienda->nombre }}</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $esEntrada ? 'bg-success' : 'bg-danger' }}">
                                    {{ $m->tipoMovimiento->nombre ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">#{{ $m->id_referencia ?? '—' }}</span>
                            </td>
                            <td>{{ $m->observacion ?: ($m->tipoMovimiento->nombre ?? '—') }}</td>
                            <td class="text-end text-success fw-bold">
                                {{ $esEntrada ? $cantidad : '' }}
                            </td>
                            <td class="text-end text-danger fw-bold">
                                {{ !$esEntrada ? abs($cantidad) : '' }}
                            </td>
                            <td class="text-end fw-bold {{ $saldo < 0 ? 'text-danger' : '' }}">
                                {{ $saldo }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No hay movimientos de inventario con los filtros seleccionados
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $movimientosPag->links() }}
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    var KARDEX_PRODUCTOS = @json($productos);

    $(document).ready(function() {
        function poblarVariantes(idProducto, seleccionado) {
            var $var = $('#kardexVariante');
            $var.empty();
            $var.append('<option value="">Todas las variantes</option>');

            if (!idProducto) {
                return;
            }

            var prod = KARDEX_PRODUCTOS.find(function(p) { return String(p.id_producto) === String(idProducto); });
            var variantes = (prod && prod.variantes) || [];

            variantes.forEach(function(v) {
                var sel = seleccionado && String(v.id_variante) === String(seleccionado) ? ' selected' : '';
                $var.append('<option value="' + v.id_variante + '"' + sel + '>[SKU ' + v.sku + ']</option>');
            });
        }

        // Poblar variantes según el filtro actual (producto seleccionado)
        var prodActual = $('#kardexProducto').val();
        var varActual = @json($filtros['idVariante'] ?? null);
        if (prodActual) {
            poblarVariantes(prodActual, varActual);
        }

        $('#kardexProducto').on('change', function() {
            poblarVariantes($(this).val(), null);
        });
    });
</script>
@endpush
