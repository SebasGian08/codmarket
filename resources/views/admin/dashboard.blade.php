@extends('admin.layouts.app')

@section('title', 'Dashboard Principal')

@section('content')

<div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <h4 class="page-title">Dashboard de Control</h4>
    </div>

    <a href="{{ route('admin.productos.index') }}" class="btn btn-primary btn-round">
        <i class="fa fa-box"></i> Gestionar Productos
    </a>
</div>

{{-- ================= KPIs ================= --}}
<div class="row">
    <div class="col-6 col-md-4 col-xl-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-primary">
                            <a href="{{ route('admin.productos.index') }}"><i class="fa fa-box"></i></a>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Productos</p>
                            <p class="card-title">{{ $totalProductos }}</p>
                            <p class="card-description">{{ $productosActivos }} activos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-info">
                            <i class="fa fa-cubes"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Stock Total</p>
                            <p class="card-title">{{ $stockTotal }}</p>
                            <p class="card-description">unidades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-success">
                            <i class="fa fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Valor Inventario</p>
                            <p class="card-title" style="font-size:1.2rem">
                                S/ {{ number_format($valorInventarioCosto, 2) }}
                            </p>
                            <p class="card-description">
                                venta: S/ {{ number_format($valorInventarioVenta, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-danger">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Agotados</p>
                            <p class="card-title">{{ $agotados }}</p>
                            <p class="card-description">variantes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-warning">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Stock Bajo</p>
                            <p class="card-title">{{ $stockBajo }}</p>
                            <p class="card-description">1 a 5 uds</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="row">
                    <div class="col-5 col-md-4">
                        <div class="icon-big text-center icon-secondary">
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                    <div class="col-7 col-md-8">
                        <div class="numbers">
                            <p class="card-category">Destacados</p>
                            <p class="card-title">{{ $destacados }}</p>
                            <p class="card-description">{{ $nuevos }} nuevos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= Gráficos ================= --}}
<!-- <div class="row">
    <div class="col-md-12 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Productos por categoría</div>
            </div>
            <div class="card-body">
                <canvas id="chartCategorias" style="min-height:300px"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Estado de inventario</div>
            </div>
            <div class="card-body">
                <canvas id="chartEstadoStock" style="min-height:240px"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Productos por marca</div>
            </div>
            <div class="card-body">
                <canvas id="chartMarcas" style="min-height:240px"></canvas>
            </div>
        </div>
    </div>
</div> -->

{{-- ================= Tablas ================= --}}
<!-- <div class="row">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title">Alerta de stock <span class="text-muted">(5 o menos unidades)</span></div>
                <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-primary btn-round">Ver todo</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>SKU</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertasStock as $v)
                            <tr>
                                <td>{{ $v->producto->nombre ?? 'Sin producto' }}</td>
                                <td>{{ $v->sku }}</td>
                                <td>S/ {{ number_format($v->precio, 2) }}</td>
                                <td>
                                    @if($v->stock <= 0)
                                    <span class="badge bg-danger">Agotado</span>
                                    @else
                                    <span class="badge bg-warning">{{ $v->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $v->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $v->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.variantes.index', $v->id_producto) }}"
                                        class="btn btn-sm btn-info btn-round" title="Variantes">
                                        <i class="fa fa-cubes"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Sin alertas de stock</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Últimos productos agregados</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosProductos as $p)
                            <tr>
                                <td>{{ $p->nombre }}</td>
                                <td>{{ $p->marca->nombre ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                        {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td>{{ $p->created_at ? $p->created_at->diffForHumans() : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Sin productos</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> -->

@endsection

@push('scripts')
<script>
$(document).ready(function() {

    // Paleta de colores
    const palette = [
        '#157347', '#1d7af3', '#f3545d', '#fdaf4b', '#fcae04',
        '#177dff', '#ff9e27', '#8d63b8', '#3b3e4b', '#2bb2d9'
    ];

    // --- Productos por categoría (barras) ---
    let categorias = {!! $porCategoria->pluck('productos_count')->toJson() !!};
    let catLabels = {!! $porCategoria->pluck('nombre')->toJson() !!};

    new Chart(document.getElementById('chartCategorias'), {
        type: 'bar',
        data: {
            labels: catLabels,
            datasets: [{
                label: 'Productos',
                data: categorias,
                backgroundColor: palette.slice(0, categorias.length),
                borderColor: palette.slice(0, categorias.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }]
            }
        }
    });

    // --- Estado de inventario (dona) ---
    new Chart(document.getElementById('chartEstadoStock'), {
        type: 'doughnut',
        data: {
            labels: ['Con stock', 'Stock bajo', 'Agotados'],
            datasets: [{
                data: [
                    {{ $porEstadoStock['stockDisponible'] }},
                    {{ $porEstadoStock['stockBajo'] }},
                    {{ $porEstadoStock['agotados'] }}
                ],
                backgroundColor: ['#157347', '#fdaf4b', '#f3545d'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom' }
        }
    });

    // --- Productos por marca (barras horizontales) ---
    let marcas = {!! $porMarca->pluck('total')->toJson() !!};
    let marcaLabels = {!! $porMarca->pluck('nombre')->toJson() !!};

    new Chart(document.getElementById('chartMarcas'), {
        type: 'horizontalBar',
        data: {
            labels: marcaLabels,
            datasets: [{
                label: 'Productos',
                data: marcas,
                backgroundColor: '#1d7af3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }]
            }
        }
    });

});
</script>
@endpush
