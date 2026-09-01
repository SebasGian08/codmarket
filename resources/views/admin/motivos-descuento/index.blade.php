@extends('admin.layouts.app')

@section('title', 'Motivos de Descuento')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Motivos de Descuento</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateMotivoDescuento">
            <i class="fa fa-plus"></i> Nuevo Motivo de Descuento
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
                            <th>Aplica a</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($motivos as $motivo)
                        <tr>
                            <td>{{ $motivo->id_motivo_descuento }}</td>
                            <td class="fw-semibold">
                                <span class="badge bg-info text-dark">{{ $motivo->nombre }}</span>
                            </td>
                            <td>
                                @if($motivo->aplica_a === 'ITEM')
                                <span class="badge bg-primary">Ítem</span>
                                @else
                                <span class="badge bg-dark">Cabecera</span>
                                @endif
                            </td>
                            <td>{{ $motivo->descripcion ?: '—' }}</td>
                            <td>
                                <span class="badge {{ $motivo->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $motivo->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-border btn-round btn-edit-motivo"
                                    data-id="{{ $motivo->id_motivo_descuento }}"
                                    data-nombre="{{ $motivo->nombre }}"
                                    data-descripcion="{{ $motivo->descripcion }}"
                                    data-aplica="{{ $motivo->aplica_a }}"
                                    data-estado="{{ $motivo->estado }}">
                                    <i class="fa fa-pen"></i>
                                </button>

                                <button class="btn btn-sm btn-danger btn-border btn-round btn-delete-motivo"
                                    data-url="{{ route('admin.motivos-descuento.destroy', $motivo->id_motivo_descuento) }}">
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

@include('admin.motivos-descuento.modals.create')
@include('admin.motivos-descuento.modals.edit')

@push('scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '.btn-edit-motivo', function() {
            var $btn = $(this);
            $('#editIdMotivo').val($btn.data('id'));
            $('#editNombreMotivo').val($btn.data('nombre'));
            $('#editDescripcionMotivo').val($btn.data('descripcion') || '');
            $('#editAplicaMotivo').val($btn.data('aplica'));
            $('#editEstadoMotivo').val(String($btn.data('estado')));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditMotivo')).show();
        });

        $('#formEditMotivo').on('submit', function() {
            var id = $('#editIdMotivo').val();
            $(this).attr('action', '{{ url("admin/motivos-descuento") }}/' + id + '/actualizar');
        });

        $(document).on('click', '.btn-delete-motivo', function() {
            var url = $(this).data('url');
            Swal.fire({
                title: '¿Eliminar motivo de descuento?',
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
