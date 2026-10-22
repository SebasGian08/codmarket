@extends('admin.layouts.app')

@section('title', 'Movimientos de inventario')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Movimientos de inventario</h4>
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

    <div class="card kardex-card">
        <div class="card-header kardex-card-header">
           <!--  <div class="kardex-filtros-heading">
                <div>
                    <h5 class="mb-1">Movimientos de inventario</h5>
                    <small class="text-muted">Consulta entradas, salidas y saldo por producto y tienda.</small>
                </div>
            </div> -->
            <form method="GET" action="{{ route('admin.inventario.kardex') }}" class="row g-3 align-items-end kardex-filtros">
                <div class="col-12 col-xl-3">
                    <label class="form-label fw-semibold mb-1">Producto</label>
                    <select name="id_producto" id="kardexProducto" class="form-control">
                        <option value="">Todos los productos</option>
                        @foreach($productos as $producto)
                        <option value="{{ $producto->id_producto }}"
                            {{ ($filtros['idProducto'] ?? null) == $producto->id_producto ? 'selected' : '' }}>
                            {{ $producto->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-xl-3">
                    <label class="form-label fw-semibold mb-1">Variante / SKU</label>
                    <select name="id_variante" id="kardexVariante" class="form-control">
                        <option value="">Todas las variantes</option>
                        @if($varianteSeleccionada)
                        @php
                            $atributosSel = $varianteSeleccionada->atributos->filter(fn($a) => $a->atributo)->map(fn($a) => ($a->atributo->nombre ?? '') . ': ' . $a->valor)->implode(' / ');
                        @endphp
                        <option value="{{ $varianteSeleccionada->id_variante }}" selected>
                            {{ $varianteSeleccionada->producto->nombre ?? '' }} — {{ $atributosSel ?: 'SKU ' . $varianteSeleccionada->sku }}
                        </option>
                        @endif
                    </select>
                </div>
                <div class="col-6 col-xl-3">
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
                <div class="col-6 col-xl-3">
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
                <div class="col-12 col-xl-6">
                    <label class="form-label fw-semibold mb-1">Rango de fechas</label>
                    <div class="input-group">
                        <input type="date" name="fecha_desde" class="form-control" value="{{ $filtros['fechaDesde'] }}">
                        <span class="input-group-text">hasta</span>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ $filtros['fechaHasta'] }}">
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <label class="form-label fw-semibold mb-1">Buscar movimiento</label>
                    <input type="search" name="buscar" class="form-control"
                        value="{{ $filtros['buscar'] }}" placeholder="Producto, SKU, tienda, tipo, documento..."
                        autocomplete="off">
                </div>
                <div class="col-6 col-xl-3">
                    <label class="form-label fw-semibold mb-1">Mostrar</label>
                    <select name="por_pagina" class="form-control" onchange="this.form.submit()">
                        @foreach($perPageOptions as $cantidad)
                        <option value="{{ $cantidad }}" {{ $filtros['perPage'] === $cantidad ? 'selected' : '' }}>
                            {{ $cantidad }} por página
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-xl-3 d-flex justify-content-xl-end gap-2">
                    <button type="submit" class="btn btn-primary btn-round flex-grow-1">
                        <i class="fa fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.inventario.kardex') }}" class="btn btn-light border btn-round kardex-clear-filter"
                        title="Limpiar filtros" aria-label="Limpiar filtros">
                        <i class="fa fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body kardex-card-body">
            @if($varianteSeleccionada)
            <div class="kardex-variante-info mb-3">
                <span class="fw-semibold me-2">{{ $varianteSeleccionada->producto->nombre ?? '' }}</span>
                @if($varianteSeleccionada->atributos->isNotEmpty())
                    @foreach($varianteSeleccionada->atributos as $attr)
                        <span class="badge bg-primary">{{ $attr->atributo->nombre ?? 'attr' }}: {{ $attr->valor }}</span>
                    @endforeach
                @else
                    <span class="badge bg-secondary">SKU: {{ $varianteSeleccionada->sku }}</span>
                @endif
            </div>
            @endif

            <div class="kardex-table-summary">
                <div>
                    <span class="kardex-summary-icon"><i class="fas fa-exchange-alt"></i></span>
                    <strong>Historial de movimientos</strong>
                </div>
                <span class="text-muted">
                    {{ $movimientosPag->total() }} {{ $movimientosPag->total() === 1 ? 'movimiento' : 'movimientos' }}
                </span>
            </div>

            <div class="table-responsive kardex-table-wrap">
                <table class="table kardex-table mb-0" id="kardex-movimientos">
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
                            $atributosVariante = $m->variante
                                ? $m->variante->atributos->filter(fn($attr) => $attr->atributo)->map(fn($attr) => ($attr->atributo->nombre ?? 'Atributo') . ': ' . $attr->valor)
                                : collect();
                        @endphp
                        <tr>
                            <td class="kardex-date">
                                <strong>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}</strong>
                                <span>{{ \Carbon\Carbon::parse($m->fecha)->format('H:i') }}</span>
                            </td>
                            <td class="kardex-product">
                                <div class="kardex-product-name">{{ $m->variante->producto->nombre ?? 'Producto eliminado' }}</div>
                                <div class="kardex-product-meta">
                                    <span class="kardex-sku">{{ $m->variante->sku ?? 'Sin SKU' }}</span>
                                    @foreach($atributosVariante as $atributo)
                                        <span class="kardex-attribute">{{ $atributo }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="kardex-store">
                                @if($m->tienda)
                                <span>{{ $m->tienda->nombre }}</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="kardex-type {{ $esEntrada ? 'kardex-type-entry' : 'kardex-type-exit' }}">
                                    <i class="fas {{ $esEntrada ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                    {{ $m->tipoMovimiento->nombre ?? '—' }}
                                </span>
                            </td>
                            <td class="kardex-document">
                                {{ $m->id_referencia ? '#' . $m->id_referencia : '—' }}
                            </td>
                            <td class="kardex-concept" title="{{ $m->observacion ?: ($m->tipoMovimiento->nombre ?? '—') }}">
                                {{ $m->observacion ?: ($m->tipoMovimiento->nombre ?? '—') }}
                            </td>
                            <td class="text-end">
                                @if($esEntrada)
                                    <span class="kardex-quantity kardex-quantity-entry">+{{ $cantidad }}</span>
                                @else
                                    <span class="kardex-quantity-empty">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if(!$esEntrada)
                                    <span class="kardex-quantity kardex-quantity-exit">−{{ abs($cantidad) }}</span>
                                @else
                                    <span class="kardex-quantity-empty">—</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="kardex-balance {{ $saldo < 0 ? 'kardex-balance-negative' : '' }}">
                                {{ $saldo }}
                                </span>
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

            <div class="kardex-pagination">
                <small class="text-muted">
                    @if($movimientosPag->total() > 0)
                        Mostrando {{ $movimientosPag->firstItem() }}–{{ $movimientosPag->lastItem() }}
                        de {{ $movimientosPag->total() }} movimientos
                    @else
                        No hay movimientos para mostrar
                    @endif
                </small>
                {{ $movimientosPag->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                var texto = v.atributos && v.atributos.length
                    ? v.atributos.map(function(a) {
                        return (a.atributo && a.atributo.nombre ? a.atributo.nombre + ': ' : '') + a.valor;
                      }).join(' / ')
                    : 'SKU ' + v.sku;
                $var.append('<option value="' + v.id_variante + '"' + sel + '>' + texto + '</option>');
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
