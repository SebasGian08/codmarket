@extends('admin.layouts.app')

@section('title', 'Cuentas Bancarias')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Cuentas Bancarias</h4>
        </div>
        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateCuenta">
            <i class="fa fa-plus"></i> Nueva Cuenta
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
                            <th>Banco</th>
                            <th>Tipo</th>
                            <th>N° Cuenta</th>
                            <th>Titular</th>
                            <th class="text-end">Saldo actual</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuentas as $cuenta)
                        <tr>
                            <td class="fw-semibold">{{ $cuenta->nombre_banco }}</td>
                            <td>{{ $cuenta->tipoCuenta->nombre ?? '—' }}</td>
                            <td>{{ $cuenta->numero_cuenta ?? '—' }}</td>
                            <td>{{ $cuenta->titular ?? '—' }}</td>
                            <td class="text-end fw-bold text-success">S/ {{ number_format($cuenta->saldo_actual, 2) }}</td>
                            <td>
                                <span class="badge {{ $cuenta->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $cuenta->estado ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info btn-border btn-round btn-edit-cuenta"
                                    data-id="{{ $cuenta->id_cuenta_bancaria }}"
                                    data-banco="{{ $cuenta->nombre_banco }}"
                                    data-tipo="{{ $cuenta->tipo_cuenta }}"
                                    data-numero="{{ $cuenta->numero_cuenta }}"
                                    data-titular="{{ $cuenta->titular }}">
                                    <i class="fa fa-pen"></i>
                                </button>

                                <button class="btn btn-sm btn-danger btn-border btn-round btn-delete-cuenta"
                                    data-url="{{ route('admin.cuentas-bancarias.destroy', $cuenta->id_cuenta_bancaria) }}">
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

@include('admin.cuentas-bancarias.modals.create')
@include('admin.cuentas-bancarias.modals.edit')

@push('scripts')
<script>
    $(document).ready(function() {

        /* ============ RELLENAR MODAL DE EDICIÓN ============ */
        $(document).on('click', '.btn-edit-cuenta', function() {
            var $btn = $(this);

            $('#editId').val($btn.data('id'));
            $('#editBanco').val($btn.data('banco'));
            $('#editTipo').val($btn.data('tipo') || '');
            $('#editNumero').val($btn.data('numero') || '');
            $('#editTitular').val($btn.data('titular') || '');

            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditCuenta')).show();
        });

        $('#formEditCuenta').on('submit', function() {
            var id = $('#editId').val();
            $(this).attr('action', '{{ url("admin/cuentas-bancarias") }}/' + id + '/actualizar');
        });

        /* ============ ELIMINAR ============ */
        $(document).on('click', '.btn-delete-cuenta', function() {
            var url = $(this).data('url');

            Swal.fire({
                title: '¿Eliminar cuenta bancaria?',
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
