<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-xl">

        <form action="{{ route('admin.transferencias.store') }}" method="POST" id="formTransferencia">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nueva Transferencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <label>Tienda origen</label>
                            <select name="id_tienda_origen" id="tiendaOrigen" class="form-control" required
                                {{ $tiendaAsignada ? 'disabled' : '' }}>
                                @if(!$tiendaAsignada)
                                <option value="">Selecciona una tienda</option>
                                @endif
                                @foreach($tiendas as $tienda)
                                <option value="{{ $tienda->id_tienda }}" {{ $tiendaAsignada && (int)$tiendaAsignada === (int)$tienda->id_tienda ? 'selected' : '' }}>
                                    {{ $tienda->codigo }} - {{ $tienda->nombre }}
                                </option>
                                @endforeach
                            </select>
                            @if($tiendaAsignada)
                            <input type="hidden" name="id_tienda_origen" value="{{ $tiendaAsignada }}">
                            @endif
                        </div>

                        <div class="col-md-3">
                            <label>Tienda destino</label>
                            <select name="id_tienda_destino" id="tiendaDestino" class="form-control" required>
                                <option value="">Selecciona una tienda</option>
                                @foreach($tiendas as $tienda)
                                @if(!$tiendaAsignada || (int)$tiendaAsignada !== (int)$tienda->id_tienda)
                                <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label>Observación</label>
                            <input type="text" name="observacion" class="form-control" maxlength="500">
                        </div>

                    </div>

                    <hr>

                    <label class="form-label small fw-semibold text-uppercase text-muted">
                        <i class="fa fa-search me-1"></i> Buscar variante (producto, SKU o atributo)
                    </label>

                    <div class="trf-buscador position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="varianteInputTrf" class="form-control" autocomplete="off"
                                placeholder="Escribe para buscar variante y agregar al detalle...">
                        </div>

                        <div id="varianteResultadosTrf" class="trf-resultados d-none"></div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Variante</th>
                                    <th class="text-center" style="width:140px">Cantidad</th>
                                    <th class="text-end" style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody id="trfFilas"></tbody>
                        </table>

                        <div id="trfVacio" class="text-center text-muted py-4">
                            <i class="fa fa-truck fa-2x d-block mb-2 opacity-50"></i>
                            Agrega variantes a la transferencia
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success" id="btnGuardarTrf">
                        <i class="fa fa-save"></i> Crear transferencia
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
