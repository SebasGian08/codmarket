<div class="modal fade" id="modalEditCuenta">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" id="formEditCuenta">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Editar Cuenta Bancaria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Banco <span class="text-danger">*</span></label>
                            <input type="text" id="editBanco" name="nombre_banco" class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de cuenta</label>
                            <select id="editTipo" name="tipo_cuenta" class="form-control">
                                <option value="">Selecciona un tipo</option>
                                @foreach($tiposCuenta as $tipo)
                                <option value="{{ $tipo->id_tipo_cuenta }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Número de cuenta</label>
                            <input type="text" id="editNumero" name="numero_cuenta" class="form-control" maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Titular</label>
                            <input type="text" id="editTitular" name="titular" class="form-control" maxlength="150">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Actualizar cuenta
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
