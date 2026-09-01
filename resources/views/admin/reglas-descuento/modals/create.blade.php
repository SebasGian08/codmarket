<div class="modal fade" id="modalCreateRegla">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.reglas-descuento.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Nueva Regla de Descuento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de descuento <span class="text-danger">*</span></label>
                            <select name="id_tipo_descuento" class="form-control" required>
                                @foreach($tiposDescuento as $td)
                                <option value="{{ $td->id_tipo_descuento }}">{{ $td->nombre }} ({{ $td->codigo }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Valor <span class="text-danger">*</span></label>
                            <input type="number" name="valor" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cantidad mínima de ítems</label>
                            <input type="number" name="cantidad_min" class="form-control" min="0" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cantidad máxima de ítems</label>
                            <input type="number" name="cantidad_max" class="form-control" min="0" placeholder="Sin límite">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Tipo de venta (opcional)</label>
                            <select name="id_tipo_venta" class="form-control">
                                <option value="">— Todos —</option>
                                @foreach($tiposVenta as $tv)
                                <option value="{{ $tv->id_tipo_venta }}">{{ $tv->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2" maxlength="255"></textarea>
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
