@extends('admin.layouts.app')

@section('title', 'Inventario')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Inventario</h4>
        </div>

        <a href="{{ route('admin.ingresos.index') }}" class="btn btn-primary btn-round">
            <i class="fa fa-plus"></i> Nuevo Ingreso
        </a>

    </div>

    <div class="row mb-3">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Productos con stock</span>
                            <div class="h4 mb-0 fw-bold">{{ $inventarios->total() }}</div>
                        </div>
                        <i class="fa fa-boxes fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Variantes activas</span>
                            <div class="h4 mb-0 fw-bold">{{ $totalVariantes }}</div>
                        </div>
                        <i class="fa fa-tags fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small">Stock valorizado</span>
                            <div class="h4 mb-0 fw-bold text-success">S/ {{ number_format($stockValorizado, 2) }}</div>
                        </div>
                        <i class="fa fa-dollar-sign fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card">
        <div class="card-body">

            <form method="GET" class="row g-2 align-items-end mb-3">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Tienda</label>
                    <select name="id_tienda" class="form-control">
                        <option value="">Todas las tiendas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}" {{ $idTienda == $tienda->id_tienda ? 'selected' : '' }}>
                            {{ $tienda->codigo }} - {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">Buscar</label>
                    <input type="text" name="buscar" class="form-control" value="{{ $buscar }}"
                        placeholder="Nombre del producto o SKU...">
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                    <a href="{{ route('admin.inventario.index') }}" class="btn btn-light border">Limpiar</a>
                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th>Tienda</th>
                            <th class="text-center">Costo</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($inventarios as $inv)
                        <tr>
                            <td class="fw-semibold">{{ $inv->variante->producto->nombre ?? 'Producto' }}</td>
                            <td>{{ $inv->variante->sku ?? '—' }}</td>
                            <td>
                                <span class="badge bg-dark me-1">{{ $inv->tienda->codigo }}</span>
                                {{ $inv->tienda->nombre }}
                            </td>
                            <td class="text-center">S/ {{ number_format($inv->variante->costo ?? 0, 2) }}</td>
                            <td class="text-center">
                                <span class="badge {{ $inv->cantidad <= 5 ? 'bg-danger' : ($inv->cantidad == 0 ? 'bg-secondary' : 'bg-success') }}">
                                    {{ $inv->cantidad }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $inv->variante->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $inv->variante->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Sin registros de stock</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-end">
                {{ $inventarios->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
