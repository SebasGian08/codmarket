<div class="modal fade" id="modalCreateTransferenciaDinero">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.transferencias-dinero.store') }}" method="POST" id="formTransferenciaDinero">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Nueva Transferencia de Dinero</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tienda <span class="text-danger">*</span></label>
                            <select name="id_tienda" id="tiendaTransferencia" class="form-control" required>
                                <option value="">Selecciona una tienda</option>
                                @foreach($tiendas as $tienda)
                                <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Fecha <span class="text-danger">*</span></label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-12 border-top pt-3">
                            <label class="form-label fw-semibold">Origen del dinero <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="origen_tipo" id="origenCaja" value="caja" checked>
                                    <label class="form-check-label" for="origenCaja">Caja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="origen_tipo" id="origenCuenta" value="cuenta">
                                    <label class="form-check-label" for="origenCuenta">Cuenta Bancaria</label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Caja origen</label>
                                    <select name="id_caja_origen" id="cajaOrigen" class="form-control">
                                        <option value="">Selecciona una caja</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Cuenta origen</label>
                                    <select name="id_cuenta_origen" id="cuentaOrigen" class="form-control" disabled>
                                        <option value="">Selecciona una cuenta</option>
                                        @foreach($cuentasBancarias as $cuenta)
                                        <option value="{{ $cuenta->id_cuenta_bancaria }}">{{ $cuenta->nombre_banco }} (S/ {{ number_format($cuenta->saldo_actual, 2) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 border-top pt-3">
                            <label class="form-label fw-semibold">Destino del dinero <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="destino_tipo" id="destinoCaja" value="caja" checked>
                                    <label class="form-check-label" for="destinoCaja">Caja</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="destino_tipo" id="destinoCuenta" value="cuenta">
                                    <label class="form-check-label" for="destinoCuenta">Cuenta Bancaria</label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Caja destino</label>
                                    <select name="id_caja_destino" id="cajaDestino" class="form-control">
                                        <option value="">Selecciona una caja</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Cuenta destino</label>
                                    <select name="id_cuenta_destino" id="cuentaDestino" class="form-control" disabled>
                                        <option value="">Selecciona una cuenta</option>
                                        @foreach($cuentasBancarias as $cuenta)
                                        <option value="{{ $cuenta->id_cuenta_bancaria }}">{{ $cuenta->nombre_banco }} (S/ {{ number_format($cuenta->saldo_actual, 2) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Monto (S/) <span class="text-danger">*</span></label>
                            <input type="number" name="monto" class="form-control" min="0.01" step="0.01" placeholder="0.00" required>
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
                    <button type="submit" class="btn btn-success" id="btnGuardarTransferencia">
                        <i class="fa fa-save"></i> Guardar transferencia
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
