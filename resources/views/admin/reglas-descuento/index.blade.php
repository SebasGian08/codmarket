@extends('admin.layouts.app')

@section('title', 'Reglas de Descuento')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Reglas de Descuento</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateRegla">
            <i class="fa fa-plus"></i> Nueva Regla de Descuento
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
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descuento</th>
                            <th>Rango de ítems</th>
                            <th>Tipo de venta</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reglas as $regla)
                        <tr>
                            <td>{{ $regla->id_regla_descuento }}</td>
                            <td class="fw-semibold">{{ $regla->nombre }}</td>
                            <td>
                                @if($regla->tipoDescuento && $regla->tipoDescuento->codigo === 'PORCENTAJE')
                                <span class="badge bg-warning text-dark">{{ number_format($regla->valor, 2) }} %</span>
                                @else
                                <span class="badge bg-success">S/ {{ number_format($regla->valor, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $regla->cantidad_min ?? '0' }}
                                @if($regla->cantidad_max)
                                – {{ $regla->cantidad_max }}
                                @else
                                +
                                @endif
                                ítems
                            </td>
                            <td>
                                @if($regla->tipoVenta)
                                <span class="badge bg-primary">{{ $regla->tipoVenta->nombre }}</span>
                                @else
                                <span class="text-muted">Todos</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $regla->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $regla->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-border btn-round btn-edit-regla"
                                    data-id="{{ $regla->id_regla_descuento }}"
                                    data-nombre="{{ $regla->nombre }}"
                                    data-descripcion="{{ $regla->descripcion }}"
                                    data-tipo="{{ $regla->id_tipo_descuento }}"
                                    data-valor="{{ $regla->valor }}"
                                    data-min="{{ $regla->cantidad_min }}"
                                    data-max="{{ $regla->cantidad_max }}"
                                    data-tipoventa="{{ $regla->id_tipo_venta }}"
                                    data-estado="{{ $regla->estado }}">
                                    <i class="fa fa-pen"></i>
                                </button>

                                <button class="btn btn-sm btn-danger btn-border btn-round btn-delete-regla"
                                    data-url="{{ route('admin.reglas-descuento.destroy', $regla->id_regla_descuento) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.reglas-descuento.modals.create')
@include('admin.reglas-descuento.modals.edit')

@push('scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '.btn-edit-regla', function() {
            var $btn = $(this);
            $('#editIdRegla').val($btn.data('id'));
            $('#editNombreRegla').val($btn.data('nombre'));
            $('#editDescripcionRegla').val($btn.data('descripcion') || '');
            $('#editTipoRegla').val($btn.data('tipo'));
            $('#editValorRegla').val($btn.data('valor'));
            $('#editMinRegla').val($btn.data('min') == null ? '' : $btn.data('min'));
            $('#editMaxRegla').val($btn.data('max') == null ? '' : $btn.data('max'));
            $('#editTipoVentaRegla').val($btn.data('tipoventa') == null ? '' : $btn.data('tipoventa'));
            $('#editEstadoRegla').val(String($btn.data('estado')));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditRegla')).show();
        });

        $('#formEditRegla').on('submit', function() {
            var id = $('#editIdRegla').val();
            $(this).attr('action', '{{ url("admin/reglas-descuento") }}/' + id + '/actualizar');
        });

        $(document).on('click', '.btn-delete-regla', function() {
            var url = $(this).data('url');
            Swal.fire({
                title: '¿Eliminar regla de descuento?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
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
                    }).fail(function() {
                        Swal.fire('Error', 'No se pudo eliminar', 'error');
                    });
                }
            });
        });

    });
</script>
@endpush

@endsection
