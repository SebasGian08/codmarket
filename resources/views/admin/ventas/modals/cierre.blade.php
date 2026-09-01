<div class="modal fade" id="modalCerrarVenta" tabindex="-1">
    <div class="modal-dialog modal-xl">

        <div class="modal-content cierre-modal-content">

            <div class="modal-header cierre-modal-header">
                <div class="d-flex align-items-center gap-2">
                    <span class="cierre-modal-icon"><i class="fa fa-lock"></i></span>
                    <div>
                        <h5 class="mb-0">Cerrar Venta <span class="badge bg-white text-dark ms-1" id="cierreVentaNumero">—</span></h5>
                        <span class="small text-white-50">Revisa el detalle, registra los pagos y cierra la venta</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- INFO DE LA VENTA -->
                <div class="cierre-info-grid">
                    <div class="cierre-info-item">
                        <span class="cierre-info-label"><i class="fa fa-user me-1"></i> Cliente</span>
                        <span class="cierre-info-value" id="cierreVentaCliente">—</span>
                    </div>
                    <div class="cierre-info-item">
                        <span class="cierre-info-label"><i class="fa fa-store me-1"></i> Tienda</span>
                        <span class="cierre-info-value" id="cierreVentaTienda">—</span>
                    </div>
                    <div class="cierre-info-item">
                        <span class="cierre-info-label"><i class="fa fa-calendar me-1"></i> Fecha</span>
                        <span class="cierre-info-value" id="cierreVentaFecha">—</span>
                    </div>
                    <div class="cierre-info-item">
                        <span class="cierre-info-label"><i class="fa fa-user-tie me-1"></i> Vendedor</span>
                        <span class="cierre-info-value" id="cierreVentaVendedor">—</span>
                    </div>
                </div>

                <!-- PASO 1: DETALLE -->
                <div class="cierre-step">
                    <div class="cierre-step-head">
                        <div class="cierre-step-title">
                            <span class="cierre-step-num">1</span>
                            <h6 class="mb-0">Detalle de la venta</h6>
                            <span class="cierre-step-hint">Ajusta cantidades si es necesario</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary btn-round" id="btnGuardarDetalleCierre">
                            <i class="fa fa-save"></i> Guardar cambios
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 cierre-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width:110px">Cantidad</th>
                                    <th class="text-end" style="width:120px">Precio</th>
                                    <th style="width:170px">Motivo desc.</th>
                                    <th class="text-end" style="width:110px">Desc.</th>
                                    <th class="text-end" style="width:130px">Subtotal</th>
                                    <th style="width:50px"></th>
                                </tr>
                            </thead>
                            <tbody id="cierreDetalleBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- PASO 1.5: DESCUENTOS / TIPO DE VENTA -->
                <div class="cierre-step">
                    <div class="cierre-step-head">
                        <div class="cierre-step-title">
                            <span class="cierre-step-num">2</span>
                            <h6 class="mb-0">Descuentos y tipo de venta</h6>
                            <span class="cierre-step-hint">Aplica descuentos solo al cerrar la venta</span>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Tipo de venta</label>
                            <select class="form-select form-select-sm" id="cierreTipoVenta">
                                <option value="">— Sin tipo —</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Descuento global (S/)</label>
                            <input type="number" step="0.01" min="0" class="form-control form-control-sm" id="cierreDescuentoGlobal" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Motivo global (cabecera)</label>
                            <select class="form-select form-select-sm" id="cierreMotivoGlobal">
                                <option value="">— Sin motivo —</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- PASO 3: PAGOS -->
                <div class="cierre-step">
                    <div class="cierre-step-head">
                        <div class="cierre-step-title">
                            <span class="cierre-step-num">3</span>
                            <h6 class="mb-0">Registrar pagos</h6>
                            <span class="cierre-step-hint">La suma de pagos debe igualar el total</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-success btn-round" id="btnAgregarPago">
                            <i class="fa fa-plus"></i> Agregar pago
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0 cierre-table">
                            <thead>
                                <tr>
                                    <th>Método de pago</th>
                                    <th>Cuenta</th>
                                    <th style="width:170px">Monto</th>
                                    <th style="width:50px"></th>
                                </tr>
                            </thead>
                            <tbody id="cierrePagosBody"></tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- RESUMEN / PIE FIJO -->
            <div class="modal-footer cierre-modal-footer">
                <div class="cierre-resumen d-flex flex-wrap align-items-center gap-3">

                    <div class="cierre-resumen-celda">
                        <span class="small text-muted d-block">Total venta</span>
                        <span class="cierre-resumen-valor" id="cierreResumenTotal">S/ 0.00</span>
                    </div>

                    <div class="cierre-resumen-celda">
                        <span class="small text-muted d-block">Total pagado</span>
                        <span class="cierre-resumen-valor text-success" id="cierreResumenPagado">S/ 0.00</span>
                    </div>

                    <div class="cierre-resumen-celda">
                        <span class="small text-muted d-block">Diferencia</span>
                        <span class="cierre-resumen-valor" id="cierreResumenDiferencia">S/ 0.00</span>
                    </div>

                    <div class="cierre-status ms-auto" id="cierreStatusWrap">
                        <i class="fa fa-spinner fa-spin me-1"></i>
                        <span id="cierreStatusText">Completa los pagos</span>
                    </div>

                    <button type="button" class="btn btn-success btn-lg btn-round px-4" id="btnProcesarCierre" disabled>
                        <i class="fa fa-lock"></i> Cerrar Venta
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>
