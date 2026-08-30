<div class="modal fade" id="modalCreateCuenta">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.cuentas-bancarias.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Registrar Cuenta Bancaria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Banco <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_banco" class="form-control" maxlength="100"
                                placeholder="Ej: BCP, BBVA, Interbank..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de cuenta</label>
                            <select name="tipo_cuenta" class="form-control">
                                <option value="">Selecciona un tipo</option>
                                @foreach($tiposCuenta as $tipo)
                                <option value="{{ $tipo->id_tipo_cuenta }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Número de cuenta</label>
                            <input type="text" name="numero_cuenta" class="form-control" maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Titular</label>
                            <input type="text" name="titular" class="form-control" maxlength="150">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Saldo inicial (S/)</label>
                            <div class="input-group">
                                <span class="input-group-text">S/</span>
                                <input type="number" name="saldo_inicial" class="form-control" value="0" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar cuenta
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
