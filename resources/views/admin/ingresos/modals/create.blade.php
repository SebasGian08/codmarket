<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-xl">

        <form action="{{ route('admin.ingresos.store') }}" method="POST" id="formIngreso">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Ingreso de Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label>Tipo de ingreso</label>
                            <select name="tipo" id="tipoIngreso" class="form-control">
                                <option value="compra">Compra a proveedor</option>
                                <option value="ajuste">Ajuste / Stock inicial</option>
                            </select>
                        </div>

                        <div class="col-md-3" id="proveedorWrapIngreso">
                            <label>Proveedor</label>
                            <select name="id_proveedor" id="proveedorIngreso" class="form-control">
                                <option value="">Selecciona un proveedor</option>
                                @foreach($proveedores as $proveedor)
                                <option value="{{ $proveedor->id_proveedor }}">{{ $proveedor->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Tienda</label>
                            <select name="id_tienda" class="form-control" required>
                                <option value="">Selecciona una tienda</option>
                                @foreach($tiendas as $tienda)
                                <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-12">
                            <label>Observación</label>
                            <input type="text" name="observacion" class="form-control" maxlength="500">
                        </div>

                    </div>

                    <hr>

                    <label class="form-label small fw-semibold text-uppercase text-muted">
                        <i class="fa fa-search me-1"></i> Buscar producto (nombre o SKU)
                    </label>

                    <div class="ingreso-buscador position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="varianteInputIngreso" class="form-control" autocomplete="off"
                                placeholder="Escribe para buscar y agregar al detalle...">
                        </div>

                        <div id="varianteResultadosIngreso" class="ingreso-resultados d-none"></div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width:120px">Cantidad</th>
                                    <th class="text-center" style="width:130px">Costo unitario</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end" style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody id="ingresoFilas"></tbody>
                        </table>

                        <div id="ingresoVacio" class="text-center text-muted py-4">
                            <i class="fa fa-box-open fa-2x d-block mb-2 opacity-50"></i>
                            Agrega productos al detalle
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-2">
                        <span class="text-muted me-2">Total:</span>
                        <span class="h5 fw-bold text-success mb-0" id="ingresoTotal">S/ 0.00</span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success" id="btnGuardarIngreso">
                        <i class="fa fa-save"></i> Registrar ingreso
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
