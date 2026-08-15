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

                    <p class="text-muted small mb-3">
                        La cantidad del archivo se <b>suma</b> al stock existente de la tienda seleccionada
                        y queda registrada como movimiento de tipo <span class="badge bg-primary">Ingreso</span>.
                    </p>

                    <form method="POST" action="{{ route('admin.inventario.carga.importar') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-uppercase text-muted mb-1">
                                    <i class="fa fa-store me-1"></i> Tienda destino
                                </label>
                                <select name="id_tienda" class="form-select" required>
                                    <option value="">Selecciona una tienda...</option>
                                    @foreach($tiendas as $tienda)
                                    <option value="{{ $tienda->id_tienda }}">{{ $tienda->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('id_tienda')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

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
                        <i class="fa fa-info-circle me-1"></i> Formato de la plantilla
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-2">
                            <thead class="table-light">
                                <tr>
                                    <th>sku</th>
                                    <th>producto</th>
                                    <th>cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>LAB001-ROJO</code></td>
                                    <td>Referencia (opcional)</td>
                                    <td>25</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <ul class="text-muted small mb-0 ps-3">
                        <li>El <b>SKU</b> debe coincidir con una variante existente.</li>
                        <li>La columna <b>producto</b> es solo referencia, no se usa.</li>
                        <li>La <b>cantidad</b> se suma al stock actual.</li>
                        <li>Las filas sin SKU o sin variante se omiten.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
