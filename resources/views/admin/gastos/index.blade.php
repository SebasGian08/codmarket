@extends('admin.layouts.app')

@section('title', 'Gastos')

@section('content')

<div class="page-inner">
    @include('admin.partials.page_heading', [
        'icon' => 'fas fa-file-invoice-dollar',
        'title' => 'Gastos',
        'description' => 'Registra gastos y administra sus tipos desde un solo módulo.',
    ])

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

    @if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card admin-module-tabs-card mt-2">
        <div class="card-header py-4">
            <ul class="nav nav-tabs admin-module-tabs gastos-admin-tabs" role="tablist">
                @if($puedeVerGastos)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'gastos' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#gastos-panel-registros" role="tab"
                        data-tab-value="gastos" aria-controls="gastos-panel-registros"
                        aria-selected="{{ $pestanaActiva === 'gastos' ? 'true' : 'false' }}">
                        <i class="fas fa-file-invoice-dollar" aria-hidden="true"></i>
                        <span>Gastos</span>
                    </a>
                </li>
                @endif

                @if($puedeVerTiposGasto)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'tipos-gastos' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#gastos-panel-tipos" role="tab"
                        data-tab-value="tipos-gastos" aria-controls="gastos-panel-tipos"
                        aria-selected="{{ $pestanaActiva === 'tipos-gastos' ? 'true' : 'false' }}">
                        <i class="fas fa-tags" aria-hidden="true"></i>
                        <span>Tipos de gastos</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content">
                @if($puedeVerGastos)
                <div class="tab-pane fade {{ $pestanaActiva === 'gastos' ? 'show active' : '' }}"
                    id="gastos-panel-registros" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary btn-round"
                            data-bs-toggle="modal" data-bs-target="#modalCreateGasto">
                            <i class="fa fa-plus"></i> Nuevo Gasto
                        </button>
                    </div>

                    <div class="card gasto-filtros-card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.gastos.index') }}"
                                class="row g-3 align-items-end gasto-filtros">
                                <input type="hidden" name="tab" value="gastos">
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label fw-semibold">N° / Documento</label>
                                    <input type="text" name="numero" class="form-control" placeholder="GAS-0001"
                                        value="{{ $filtros['numero'] ?? '' }}">
                                </div>

                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label fw-semibold">Tipo de gasto</label>
                                    <select name="id_tipo_gasto" class="form-select">
                                        <option value="">Todos</option>
                                        @foreach($tiposGasto as $tg)
                                        <option value="{{ $tg->id_tipo_gasto }}"
                                            {{ (string)($filtros['id_tipo_gasto'] ?? '') === (string)$tg->id_tipo_gasto ? 'selected' : '' }}>
                                            {{ $tg->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label fw-semibold">Tienda</label>
                                    <select name="id_tienda" class="form-select">
                                        <option value="">Todas</option>
                                        @foreach($tiendas as $tienda)
                                        <option value="{{ $tienda->id_tienda }}"
                                            {{ (string)($filtros['id_tienda'] ?? '') === (string)$tienda->id_tienda ? 'selected' : '' }}>
                                            [{{ $tienda->codigo }}] {{ $tienda->nombre }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 col-xl-3">
                                    <label class="form-label fw-semibold">Estado</label>
                                    <select name="estado" class="form-select">
                                        <option value="">Todos</option>
                                        <option value="1" {{ ($filtros['estado'] ?? '') === '1' ? 'selected' : '' }}>Registrado</option>
                                        <option value="0" {{ ($filtros['estado'] ?? '') === '0' ? 'selected' : '' }}>Anulado</option>
                                    </select>
                                </div>

                                <div class="col-12 col-xl-6">
                                    <label class="form-label fw-semibold">Rango de fechas</label>
                                    <div class="input-group gasto-fechas">
                                        <input type="date" name="fecha_desde" class="form-control"
                                            aria-label="Fecha desde" value="{{ $filtros['fecha_desde'] ?? '' }}">
                                        <span class="input-group-text">hasta</span>
                                        <input type="date" name="fecha_hasta" class="form-control"
                                            aria-label="Fecha hasta" value="{{ $filtros['fecha_hasta'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-12 col-xl-6 d-flex flex-wrap justify-content-xl-end gap-2 gasto-filtros-actions">
                                    <button type="submit" class="btn btn-primary btn-round">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                    <a href="{{ route('admin.gastos.index', ['tab' => 'gastos']) }}"
                                        class="btn btn-light border btn-round">
                                        <i class="fa fa-eraser"></i> Limpiar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover admin-data-table" id="tabla-gastos">
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
                                                <button type="button"
                                                    class="btn btn-sm btn-info btn-border btn-round btn-ver-detalle-gasto"
                                                    data-url="{{ route('admin.gastos.detalle', $gasto->id_gasto) }}">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                @if($gasto->estado)
                                                <button type="button" class="btn btn-sm btn-danger btn-round btn-anular-gasto"
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
                @endif

                @if($puedeVerTiposGasto)
                <div class="tab-pane fade {{ $pestanaActiva === 'tipos-gastos' ? 'show active' : '' }}"
                    id="gastos-panel-tipos" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary btn-round"
                            data-bs-toggle="modal" data-bs-target="#modalCreateTipoGasto">
                            <i class="fa fa-plus"></i> Nuevo Tipo de Gasto
                        </button>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover admin-data-table" id="tabla-tipos-gastos">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Gastos asociados</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tipos as $tipo)
                                        <tr>
                                            <td>{{ $tipo->id_tipo_gasto }}</td>
                                            <td class="fw-semibold">
                                                <span class="badge bg-warning text-dark">{{ $tipo->nombre }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $tipo->gastos_count }}</span>
                                                <span class="text-muted">gasto(s)</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $tipo->estado ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $tipo->estado ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm btn-info btn-border btn-round btn-edit-tipo-gasto"
                                                    data-id="{{ $tipo->id_tipo_gasto }}"
                                                    data-nombre="{{ $tipo->nombre }}"
                                                    data-estado="{{ $tipo->estado }}">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                @if(!$tipo->gastos_count)
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-border btn-round btn-delete-tipo-gasto"
                                                    data-url="{{ route('admin.tipos-gastos.destroy', $tipo->id_tipo_gasto) }}">
                                                    <i class="fa fa-trash"></i>
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
                @endif
            </div>
        </div>
    </div>
</div>

@if($puedeVerGastos)
@include('admin.gastos.modals.create')
@include('admin.gastos.modals.detalle_modal')
@endif

@if($puedeVerTiposGasto)
@include('admin.tipos-gastos.modals.create')
@include('admin.tipos-gastos.modals.edit')
@endif

@push('scripts')
@if($puedeVerGastos)
<script>
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

$('#formGasto').on('submit', function() {
    $('#btnGuardarGasto').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
});

$(document).on('click', '.btn-ver-detalle-gasto', function() {
    var url = $(this).data('url');

    $.get(url, function(html) {
        $('#modalDetalleGasto .modal-content').html(html);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleGasto')).show();
    });
});

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
@endif

@if($puedeVerTiposGasto)
<script>
$(document).on('click', '.btn-edit-tipo-gasto', function() {
    var $btn = $(this);

    $('#editIdTipoGasto').val($btn.data('id'));
    $('#editNombreTipoGasto').val($btn.data('nombre'));
    $('#editEstadoTipoGasto').val(String($btn.data('estado')));

    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditTipoGasto')).show();
});

$('#formEditTipoGasto').on('submit', function() {
    var id = $('#editIdTipoGasto').val();
    $(this).attr('action', '{{ url("admin/tipos-gastos") }}/' + id + '/actualizar');
});

$(document).on('click', '.btn-delete-tipo-gasto', function() {
    var url = $(this).data('url');

    Swal.fire({
        title: '¿Eliminar tipo de gasto?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(function(r) {
        if (r.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' }
            }).done(function() {
                Swal.fire({ icon: 'success', title: 'Eliminado', timer: 1200, showConfirmButton: false });
                setTimeout(function() { location.reload(); }, 800);
            }).fail(function(xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo eliminar';
                Swal.fire('Error', msg, 'error');
            });
        }
    });
});
</script>
@endif

<script>
$(document).on('shown.bs.tab', '.gastos-admin-tabs [data-bs-toggle="tab"]', function(event) {
    var $tab = $(event.target);
    var target = $tab.attr('href');
    var currentUrl = new URL(window.location.href);

    currentUrl.searchParams.set('tab', $tab.data('tab-value'));
    window.history.replaceState({}, '', currentUrl.toString());

    $(target).find('.admin-data-table').each(function() {
        if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().columns.adjust();
        }
    });
});
</script>
@endpush

@endsection
