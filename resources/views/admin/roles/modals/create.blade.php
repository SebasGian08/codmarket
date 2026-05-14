<div class="modal fade" id="modalCreate">
    <div class="modal-dialog">

        <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf

        <div class="modal-content">

            <div class="modal-header">
                <h5>Nuevo Rol</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control">

            </div>

            <div class="modal-footer">
                <button class="btn btn-success">Guardar</button>
            </div>

        </div>
        </form>

    </div>
</div>