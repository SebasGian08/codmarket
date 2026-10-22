<div class="modal fade" id="modalFooterLogo" tabindex="-1">
    <div class="modal-dialog">

        <form method="POST" action="{{ route('admin.footer-logos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-certificate me-1"></i> Nuevo Logo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <label>Imagen <small class="text-danger">*</small></label>
                    <input type="file" name="imagen" class="form-control mb-2" accept="image/*" required>

                    <label>Título</label>
                    <input name="titulo" class="form-control mb-2" maxlength="150"
                        placeholder="Ej: ISO 9001">

                    <label>Enlace (opcional)</label>
                    <input name="url" type="url" class="form-control mb-2"
                        placeholder="https://...">

                    <label>Orden</label>
                    <input name="orden" type="number" class="form-control mb-2" value="0" min="0">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
