@extends('admin.layouts.app')

@section('title', 'Tipos de Gastos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Tipos de Gastos</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateTipoGasto">
            <i class="fa fa-plus"></i> Nuevo Tipo de Gasto
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
                                <button class="btn btn-sm btn-info btn-border btn-round btn-edit-tipo-gasto"
                                    data-id="{{ $tipo->id_tipo_gasto }}"
                                    data-nombre="{{ $tipo->nombre }}"
                                    data-estado="{{ $tipo->estado }}">
                                    <i class="fa fa-pen"></i>
                                </button>

                                @if(!$tipo->gastos_count)
                                <button class="btn btn-sm btn-danger btn-border btn-round btn-delete-tipo-gasto"
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

@include('admin.tipos-gastos.modals.create')
@include('admin.tipos-gastos.modals.edit')

@push('scripts')
<script>
    $(document).ready(function() {

        /* ============ RELLENAR MODAL DE EDICIÓN ============ */
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

        /* ============ ELIMINAR ============ */
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

    });
</script>
@endpush

@endsection
