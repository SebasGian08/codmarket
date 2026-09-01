<div class="modal fade" id="modalEditRegla">
    <div class="modal-dialog modal-lg">
        <form action="" method="POST" id="formEditRegla">
            @csrf
            @method('PUT')
            <input type="hidden" id="editIdRegla" name="id_regla_descuento">

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Editar Regla de Descuento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="editNombreRegla" class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de descuento <span class="text-danger">*</span></label>
                            <select name="id_tipo_descuento" id="editTipoRegla" class="form-control" required>
                                @foreach($tiposDescuento as $td)
                                <option value="{{ $td->id_tipo_descuento }}">{{ $td->nombre }} ({{ $td->codigo }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Valor <span class="text-danger">*</span></label>
                            <input type="number" name="valor" id="editValorRegla" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cantidad mínima de ítems</label>
                            <input type="number" name="cantidad_min" id="editMinRegla" class="form-control" min="0" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cantidad máxima de ítems</label>
                            <input type="number" name="cantidad_max" id="editMaxRegla" class="form-control" min="0" placeholder="Sin límite">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Tipo de venta (opcional)</label>
                            <select name="id_tipo_venta" id="editTipoVentaRegla" class="form-control">
                                <option value="">— Todos —</option>
                                @foreach($tiposVenta as $tv)
                                <option value="{{ $tv->id_tipo_venta }}">{{ $tv->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="descripcion" id="editDescripcionRegla" class="form-control" rows="2" maxlength="255"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="estado" id="editEstadoRegla" class="form-control">
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
