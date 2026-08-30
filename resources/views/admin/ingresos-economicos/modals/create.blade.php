<div class="modal fade" id="modalCreateIngresoEco">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.ingresos-economicos.store') }}" method="POST" id="formIngresoEco">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Registrar Ingreso Económico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo de Ingreso <span class="text-danger">*</span></label>
                            <select name="id_tipo_ingreso_economico" class="form-control" required>
                                <option value="">Selecciona un tipo</option>
                                @foreach($tiposIngreso as $tipo)
                                <option value="{{ $tipo->id_tipo_ingreso_economico }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tienda <span class="text-danger">*</span></label>
                            <select name="id_tienda" id="tiendaIngresoEco" class="form-control" required>
                                <option value="">Selecciona una tienda</option>
                                @foreach($tiendas as $tienda)
                                <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Caja</label>
                            <select name="id_caja" id="cajaIngresoEco" class="form-control">
                                <option value="">Sin caja</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cuenta Bancaria</label>
                            <select name="id_cuenta_bancaria" class="form-control">
                                <option value="">Selecciona una cuenta</option>
                                @foreach($cuentasBancarias as $cuenta)
                                <option value="{{ $cuenta->id_cuenta_bancaria }}">{{ $cuenta->nombre_banco }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Monto (S/) <span class="text-danger">*</span></label>
                            <input type="number" name="monto" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                            <input type="text" name="descripcion" class="form-control" maxlength="500"
                                placeholder="Ej: Préstamo recibido, Aporte de capital..." required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Observación</label>
                            <textarea name="observacion" class="form-control" rows="2" maxlength="500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-success" id="btnGuardarIngresoEco">
                        <i class="fa fa-save"></i> Registrar ingreso
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
