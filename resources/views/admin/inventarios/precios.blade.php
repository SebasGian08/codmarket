@extends('admin.layouts.app')

@section('title', 'Actualización Masiva de Precios')

@section('content')
<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h4 class="page-title">Actualización Masiva de Precios</h4>

        <a href="{{ route('admin.inventario.precios.plantilla') }}" class="btn btn-light border btn-round">
            Descargar plantilla Excel
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
                    <h6 class="fw-bold mb-1">Subir archivo Excel</h6>
                    <p class="inventario-carga-nota text-muted small mb-3">
                        La plantilla incluye todas las variantes activas y sus precios actuales.
                        Edita los valores que necesites y vuelve a subir el archivo para actualizar los precios.
                    </p>

                    <form method="POST" action="{{ route('admin.inventario.precios.importar') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold text-uppercase text-muted mb-1">
                                    Archivo Excel (xlsx / xls)
                                </label>
                                <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls" required>
                                @error('archivo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-round">
                                    Procesar actualización
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
                    <h6 class="fw-bold mb-3">Columnas de la plantilla</h6>
                    <ul class="text-muted small mb-0 ps-3">
                        <li><b>sku, producto y variante:</b> identificación; no cambies el SKU.</li>
                        <li><b>precio:</b> requerido para cada SKU incluido.</li>
                        <li><b>precio_oferta, precio_mayorista y costo:</b> puedes actualizar o dejar vacíos.</li>
                        <li>Los precios actuales vienen precargados; deja la fila sin cambios si no deseas actualizar esa variante.</li>
                        <li>No repitas SKU ni cambies los encabezados.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
