@extends('admin.layouts.app')

@section('title', 'Carga Masiva de Productos y Variantes')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Carga Masiva de Productos y Variantes</h4>
        </div>

        <a href="{{ route('admin.productos.carga.plantilla') }}" class="btn btn-light border btn-round">
            <i class="fa fa-file-excel"></i> Descargar plantilla
        </a>

    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <h6 class="fw-bold mb-1">
                        <i class="fa fa-upload me-1"></i> Subir archivo Excel
                    </h6>

                    <p class="text-muted small mb-3">
                        Con una sola plantilla puedes <b>crear productos nuevos</b> (con su primera variante)
                        y a la vez <b>agregar o actualizar variantes</b> de productos existentes.
                        El stock se registra como movimiento de tipo <span class="badge bg-primary">Ingreso</span>
                        o <span class="badge bg-warning">Ajuste</span> según corresponda.
                    </p>

                    <form method="POST" action="{{ route('admin.productos.carga.importar') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-uppercase text-muted mb-1">
                                    <i class="fa fa-file-excel me-1"></i> Archivo (xlsx / xls)
                                </label>
                                <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls" required>
                                @error('archivo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-round">
                                    <i class="fa fa-upload"></i> Procesar carga
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fa fa-info-circle me-1"></i> ¿Cómo funciona?
                    </h6>

                    <ul class="text-muted small mb-3 ps-3">
                        <li class="mb-2">
                            <b>Producto nuevo:</b> en la primera fila completa las columnas del producto
                            (nombre, descripción, marca, etc.) y su primera variante.
                        </li>
                        <li class="mb-2">
                            <b>Producto existente:</b> indica en <b>producto_sku</b> el SKU de cualquiera de
                            sus variantes (o el nombre) y agrega la fila de la variante. No necesitas repetir
                            los datos del producto.
                        </li>
                        <li class="mb-2">
                            <b>Variantes:</b> si el <b>sku</b> ya existe se actualiza; si no, se crea.
                            Dejando <b>sku</b> vacío se genera uno automáticamente.
                        </li>
                        <li class="mb-2">
                            <b>Atributos:</b> formato <code>Color: Rojo, Talla: M</code>.
                        </li>
                        <li>
                            <b>Stock:</b> en variante nueva se registra un ingreso; en variante existente,
                            un ajuste por la diferencia.
                        </li>
                    </ul>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>producto_sku</th>
                                    <th>sku</th>
                                    <th>precio</th>
                                    <th>stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>LAB001</code></td>
                                    <td><code>LAB001-ROJO</code></td>
                                    <td>29.90</td>
                                    <td>100</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
