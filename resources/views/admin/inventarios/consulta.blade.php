@extends('admin.layouts.app')

@section('title', 'Consulta de stock')

@section('content')

<div class="page-inner">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h4 class="page-title">Consulta de stock</h4>
        <a href="{{ route('admin.inventario.consulta.exportar', request()->query()) }}" class="btn btn-success btn-round">
            <i class="fa fa-file-excel"></i> Excel
        </a>
    </div>

    <div class="card inv-consulta-filtros">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventario.consulta') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label fw-semibold">Producto o SKU</label>
                    <input type="search" name="buscar" class="form-control" value="{{ $filtros['buscar'] }}"
                        placeholder="Buscar por producto, SKU o código de barras">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">Tienda</label>
                    <select name="id_tienda" class="form-select">
                        <option value="">Todas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}" {{ (string)$filtros['id_tienda'] === (string)$tienda->id_tienda ? 'selected' : '' }}>
                            {{ $tienda->codigo }} - {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado_stock" class="form-select">
                        <option value="">Todos</option>
                        <option value="agotado" {{ $filtros['estado_stock'] === 'agotado' ? 'selected' : '' }}>Agotado</option>
                        <option value="bajo" {{ $filtros['estado_stock'] === 'bajo' ? 'selected' : '' }}>Stock bajo</option>
                        <option value="disponible" {{ $filtros['estado_stock'] === 'disponible' ? 'selected' : '' }}>Disponible</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2 inv-consulta-acciones">
                    <button type="submit" class="btn btn-primary btn-round flex-grow-1">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('admin.inventario.consulta') }}" class="btn btn-light border btn-round" title="Limpiar filtros">
                        <i class="fa fa-eraser"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl">
            <div class="card inv-consulta-kpi">
                <div class="card-body">
                    <span>Registros</span>
                    <strong>{{ number_format($resumen->total) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card inv-consulta-kpi inv-consulta-kpi-agotado">
                <div class="card-body">
                    <span>Agotados</span>
                    <strong>{{ number_format($resumen->agotados) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card inv-consulta-kpi inv-consulta-kpi-bajo">
                <div class="card-body">
                    <span>Stock bajo</span>
                    <strong>{{ number_format($resumen->stock_bajo) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-6 col-xl">
            <div class="card inv-consulta-kpi inv-consulta-kpi-disponible">
                <div class="card-body">
                    <span>Disponibles</span>
                    <strong>{{ number_format($resumen->disponibles) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl">
            <div class="card inv-consulta-kpi inv-consulta-kpi-unidades">
                <div class="card-body">
                    <span>Unidades</span>
                    <strong>{{ number_format($resumen->unidades) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 inv-consulta-tabla">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Variante</th>
                        <th>SKU</th>
                        <th>Tienda</th>
                        <th class="text-center">Stock</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $registro)
                    <tr>
                        <td data-label="Producto" class="fw-semibold">{{ $registro->producto }}</td>
                        <td data-label="Variante">{{ $registro->variante }}</td>
                        <td data-label="SKU"><span class="inv-consulta-sku">{{ $registro->sku ?: '—' }}</span></td>
                        <td data-label="Tienda">
                            <span class="badge bg-dark me-1">{{ $registro->codigo_tienda }}</span>{{ $registro->tienda }}
                        </td>
                        <td data-label="Stock" class="text-center fw-bold">{{ $registro->stock }}</td>
                        <td data-label="Estado">
                            @if($registro->stock == 0)
                            <span class="badge bg-danger">Agotado</span>
                            @elseif($registro->stock <= 5)
                            <span class="badge bg-warning text-dark">Stock bajo</span>
                            @else
                            <span class="badge bg-success">Disponible</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Sin resultados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registros->total() > 0)
        <div class="card-footer inv-consulta-paginacion">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <span class="text-muted small">
                    Mostrando {{ number_format($registros->firstItem()) }} al {{ number_format($registros->lastItem()) }}
                    de {{ number_format($registros->total()) }} registros
                </span>
                <form method="GET" action="{{ route('admin.inventario.consulta') }}" class="inv-consulta-page-size">
                    <input type="hidden" name="buscar" value="{{ $filtros['buscar'] }}">
                    <input type="hidden" name="id_tienda" value="{{ $filtros['id_tienda'] }}">
                    <input type="hidden" name="estado_stock" value="{{ $filtros['estado_stock'] }}">
                    <label for="consultaPorPagina" class="small text-muted">Mostrar</label>
                    <select id="consultaPorPagina" name="por_pagina" class="form-select form-select-sm"
                        onchange="this.form.submit()">
                        @foreach([10, 25, 50, 100] as $cantidad)
                        <option value="{{ $cantidad }}" {{ $filtros['por_pagina'] === $cantidad ? 'selected' : '' }}>
                            {{ $cantidad }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if($registros->hasPages())
            {{ $registros->links('pagination::bootstrap-5') }}
            @endif
        </div>
        @endif
    </div>
</div>

@endsection
