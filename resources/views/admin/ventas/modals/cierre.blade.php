<div class="modal fade" id="modalCerrarVenta" tabindex="-1">
    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">
                <h5><i class="fa fa-lock me-1"></i> Cerrar Venta — <span id="cierreVentaNumero"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- Info de venta -->
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <span class="small text-muted">Cliente</span>
                        <div class="fw-semibold" id="cierreVentaCliente">—</div>
                    </div>
                    <div class="col-md-3">
                        <span class="small text-muted">Tienda</span>
                        <div class="fw-semibold" id="cierreVentaTienda">—</div>
                    </div>
                    <div class="col-md-3">
                        <span class="small text-muted">Fecha</span>
                        <div class="fw-semibold" id="cierreVentaFecha">—</div>
                    </div>
                    <div class="col-md-3">
                        <span class="small text-muted">Vendedor</span>
                        <div class="fw-semibold" id="cierreVentaVendedor">—</div>
                    </div>
                </div>

                <hr>

                <!-- DETALLE EDITABLE -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-shopping-cart me-1"></i> Detalle de venta
                    </h6>
                    <button type="button" class="btn btn-sm btn-primary btn-round" id="btnGuardarDetalleCierre">
                        <i class="fa fa-save"></i> Guardar cambios
                    </button>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center" style="width:100px">Cant.</th>
                                <th class="text-end" style="width:120px">Precio</th>
                                <th class="text-end" style="width:120px">Subtotal</th>
                                <th style="width:60px"></th>
                            </tr>
                        </thead>
                        <tbody id="cierreDetalleBody"></tbody>
                    </table>
                </div>

                <hr>

                <!-- PAGOS -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-credit-card me-1"></i> Pagos
                    </h6>
                    <button type="button" class="btn btn-sm btn-success btn-round" id="btnAgregarPago">
                        <i class="fa fa-plus"></i> Agregar pago
                    </button>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-sm table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Método de pago</th>
                                <th>Cuenta</th>
                                <th style="width:140px">Monto</th>
                                <th style="width:60px"></th>
                            </tr>
                        </thead>
                        <tbody id="cierrePagosBody"></tbody>
                    </table>
                </div>

                <!-- RESUMEN -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <div class="row align-items-center">

                            <div class="col-md-6">
                                <div class="d-flex flex-wrap gap-3">
                                    <div>
                                        <span class="small text-muted d-block">Total venta</span>
                                        <span class="fs-5 fw-bold" id="cierreResumenTotal">S/ 0.00</span>
                                    </div>
                                    <div>
                                        <span class="small text-muted d-block">Total pagos</span>
                                        <span class="fs-5 fw-bold" id="cierreResumenPagado">S/ 0.00</span>
                                    </div>
                                    <div>
                                        <span class="small text-muted d-block">Pendiente</span>
                                        <span class="fs-5 fw-bold" id="cierreResumenPendiente">S/ 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-center">
                                <span class="small text-muted d-block mb-1">Diferencia</span>
                                <span class="fs-4 fw-bold" id="cierreResumenDiferencia">S/ 0.00</span>
                            </div>

                            <div class="col-md-3 text-end">
                                <button type="button" class="btn btn-success btn-lg btn-round" id="btnProcesarCierre" disabled>
                                    <i class="fa fa-lock"></i> Cerrar Venta
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cerrar
                </button>
            </div>

        </div>

    </div>
</div>
