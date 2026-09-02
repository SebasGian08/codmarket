@extends('admin.layouts.app')

@section('title', 'Carga Masiva de Inventario')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Carga Masiva de Inventario</h4>
        </div>

        <a href="{{ route('admin.inventario.carga.plantilla') }}" class="btn btn-light border btn-round">
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

                    <p class="inventario-carga-nota text-muted small mb-3">
                        La plantilla trae automáticamente todas las variantes activas y una pestaña por tienda.
                        Completa únicamente la cantidad; esta se <b>suma</b> al stock existente y queda registrada
                        como movimiento de tipo <span class="badge bg-primary">Ingreso</span>.
                    </p>

                    <form method="POST" action="{{ route('admin.inventario.carga.importar') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-8">
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
                        <i class="fa fa-info-circle me-1"></i> Formato de la plantilla
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead class="table-light">
                                <tr>
                                    <th>sku</th>
                                    <th>producto</th>
                                    <th>variante</th>
                                    <th>cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>LAB001-ROJO</code></td>
                                    <td>Producto real</td>
                                    <td>Color: Rojo</td>
                                    <td>Completar</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <ul class="text-muted small mb-0 ps-3">
                        <li>Cada pestaña corresponde a una tienda y se identifica por su código.</li>
                        <li>No cambies el <b>SKU</b> ni el nombre de las pestañas.</li>
                        <li>Completa solo <b>cantidad</b>; se suma al stock actual.</li>
                        <li>Las filas vacías o con cantidad cero se omiten.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
