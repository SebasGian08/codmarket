<div class="table-responsive">
    <table class="table table-hover admin-data-table" id="{{ $dataTableId ?? 'basic-datatables' }}">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Reglas</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tipos as $tipo)
            <tr>
                <td>{{ $tipo->id_tipo_venta }}</td>
                <td class="fw-semibold">
                    <span class="badge bg-primary">{{ $tipo->nombre }}</span>
                </td>
                <td>{{ $tipo->descripcion ?: '—' }}</td>
                <td><span class="badge bg-secondary">{{ $tipo->reglas_count }}</span></td>
                <td>
                    <span class="badge {{ $tipo->estado ? 'bg-success' : 'bg-danger' }}">
                        {{ $tipo->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-info btn-border btn-round btn-edit-tipo-venta"
                        data-id="{{ $tipo->id_tipo_venta }}"
                        data-nombre="{{ $tipo->nombre }}"
                        data-descripcion="{{ $tipo->descripcion }}"
                        data-estado="{{ $tipo->estado }}">
                        <i class="fa fa-pen"></i>
                    </button>

                    @if(!$tipo->reglas_count)
                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete-tipo-venta"
                        data-url="{{ route('admin.tipos-venta.destroy', $tipo->id_tipo_venta) }}">
                        <i class="fa fa-trash"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@include('admin.tipos-venta.modals.create')
@include('admin.tipos-venta.modals.edit')

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-edit-tipo-venta', function() {
            var $btn = $(this);
            $('#editIdTipoVenta').val($btn.data('id'));
            $('#editNombreTipoVenta').val($btn.data('nombre'));
            $('#editDescripcionTipoVenta').val($btn.data('descripcion') || '');
            $('#editEstadoTipoVenta').val(String($btn.data('estado')));
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditTipoVenta')).show();
        });

        $('#formEditTipoVenta').on('submit', function() {
            var id = $('#editIdTipoVenta').val();
            $(this).attr('action', '{{ url("admin/tipos-venta") }}/' + id + '/actualizar');
        });

        $(document).on('click', '.btn-delete-tipo-venta', function() {
            var url = $(this).data('url');
            Swal.fire({
                title: '¿Eliminar tipo de venta?',
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
