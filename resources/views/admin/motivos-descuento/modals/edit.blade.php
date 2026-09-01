<div class="modal fade" id="modalEditMotivo">
    <div class="modal-dialog">
        <form action="" method="POST" id="formEditMotivo">
            @csrf
            @method('PUT')
            <input type="hidden" id="editIdMotivo" name="id_motivo_descuento">

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Editar Motivo de Descuento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="editNombreMotivo" class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Aplica a <span class="text-danger">*</span></label>
                            <select name="aplica_a" id="editAplicaMotivo" class="form-control">
                                <option value="ITEM">Ítem (por línea)</option>
                                <option value="CABECERA">Cabecera (toda la venta)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" id="editDescripcionMotivo" class="form-control" rows="2" maxlength="255"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" id="editEstadoMotivo" class="form-control">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Actualizar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
