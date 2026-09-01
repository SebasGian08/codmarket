<div class="modal fade" id="modalCreateTipoGasto">
    <div class="modal-dialog">
        <form action="{{ route('admin.tipos-gastos.store') }}" method="POST" id="formCreateTipoGasto">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Nuevo Tipo de Gasto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" maxlength="100"
                                placeholder="Ej: Limpieza, Seguros..." required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" class="form-control">
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
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
