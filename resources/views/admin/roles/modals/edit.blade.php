<div class="modal fade" id="edit{{ $rol->id_rol }}">
    <div class="modal-dialog">

        <form method="POST" action="{{ route('admin.roles.update', $rol->id_rol) }}">
        @csrf
        @method('PUT')

        <div class="modal-content">

            <div class="modal-header">
                <h5>Editar Rol</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label>Nombre</label>
                <input type="text" name="nombre" value="{{ $rol->nombre }}" class="form-control">

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Actualizar</button>
            </div>

        </div>
        </form>

    </div>
</div>