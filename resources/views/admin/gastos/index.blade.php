@extends('admin.layouts.app')

@section('title', 'Gastos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Gastos</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateGasto">
            <i class="fa fa-plus"></i> Nuevo Gasto
        </button>

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
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.gastos.index') }}" class="row g-2 align-items-end gasto-filtros">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">N° / Documento</label>
                    <input type="text" name="numero" class="form-control form-control-sm" placeholder="GAS-0001"
                        value="{{ $filtros['numero'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tipo de gasto</label>
                    <select name="id_tipo_gasto" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($tiposGasto as $tg)
                        <option value="{{ $tg->id_tipo_gasto }}" {{ (string)($filtros['id_tipo_gasto'] ?? '') === (string)$tg->id_tipo_gasto ? 'selected' : '' }}>
                            {{ $tg->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tienda</label>
                    <select name="id_tienda" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}" {{ (string)($filtros['id_tienda'] ?? '') === (string)$tienda->id_tienda ? 'selected' : '' }}>
                            [{{ $tienda->codigo }}] {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="1" {{ ($filtros['estado'] ?? '') === '1' ? 'selected' : '' }}>Registrado</option>
                        <option value="0" {{ ($filtros['estado'] ?? '') === '0' ? 'selected' : '' }}>Anulado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm"
                        value="{{ $filtros['fecha_desde'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm"
                        value="{{ $filtros['fecha_hasta'] ?? '' }}">
                </div>
                <div class="col-12 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-round">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('admin.gastos.index') }}" class="btn btn-sm btn-secondary btn-round">
                        <i class="fa fa-eraser"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Tienda</th>
                            <th>Destino</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($gastos as $gasto)
                        <tr>
                            <td><span class="badge bg-dark">{{ $gasto->numero }}</span></td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ $gasto->tipoGasto->nombre }}</span>
                            </td>
                            <td class="fw-semibold">{{ $gasto->descripcion }}</td>
                            <td>{{ $gasto->tienda->nombre }}</td>
                            <td>
                                @if($gasto->id_caja)
                                <span class="badge bg-success">Caja: {{ $gasto->caja->nombre ?? '—' }}</span>
                                @else
                                <span class="badge bg-info text-dark">Cuenta: {{ $gasto->cuentaBancaria->nombre_banco ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="fw-bold text-danger">S/ {{ number_format($gasto->monto, 2) }}</td>
                            <td>{{ $gasto->fecha->format('d/m/Y') }}</td>
                            <td>{{ $gasto->usuario->nombres }} {{ $gasto->usuario->apellidos }}</td>
                            <td>
                                <span class="badge {{ $gasto->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $gasto->estado ? 'Registrado' : 'Anulado' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-info btn-border btn-round btn-ver-detalle-gasto"
                                    data-url="{{ route('admin.gastos.detalle', $gasto->id_gasto) }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @if($gasto->estado)
                                <button class="btn btn-sm btn-danger btn-round btn-anular-gasto"
                                    data-url="{{ route('admin.gastos.anular', $gasto->id_gasto) }}">
                                    <i class="fa fa-ban"></i> Anular
                                </button>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.gastos.modals.create')
@include('admin.gastos.modals.detalle_modal')

@push('scripts')
<script>
    /* ============ CARGAR CAJAS POR TIENDA ============ */
    var CAJAS = @json($cajasAbiertas);

    function cargarCajasGasto() {
        var idTienda = $('#tiendaGasto').val();
        var select = $('#cajaGasto');
        select.html('<option value="">Sin caja</option>');

        if (!idTienda) return;

        CAJAS.forEach(function(c) {
            if (c.id_tienda == idTienda) {
                select.append('<option value="' + c.id_caja + '">' + c.nombre + '</option>');
            }
        });
    }

    $('#tiendaGasto').on('change', cargarCajasGasto);

    /* ============ SUBMIT ============ */
    $('#formGasto').on('submit', function() {
        $('#btnGuardarGasto').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    });

    /* ============ VER DETALLE ============ */
    $(document).on('click', '.btn-ver-detalle-gasto', function() {
        var url = $(this).data('url');

        $.get(url, function(html) {
            $('#modalDetalleGasto .modal-content').html(html);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleGasto')).show();
        });
    });

    /* ============ ANULAR ============ */
    $(document).on('click', '.btn-anular-gasto', function() {
        var url = $(this).data('url');

        Swal.fire({
            title: '¿Anular gasto?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.post(url, { _token: '{{ csrf_token() }}' })
                    .done(function() {
                        Swal.fire({ icon: 'success', title: 'Anulado', timer: 1200, showConfirmButton: false });
                        setTimeout(function() { location.reload(); }, 800);
                    })
                    .fail(function(xhr) {
                        Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo anular', 'error');
                    });
            }
        });
    });
</script>
@endpush

@endsection
