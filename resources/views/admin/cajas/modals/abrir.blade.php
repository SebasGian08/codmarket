<div class="modal fade" id="modalAbrir">
    <div class="modal-dialog">

        <form action="{{ route('admin.cajas.abrir') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Abrir Caja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label>Tienda</label>
                        <select name="id_tienda" class="form-control" required>
                            <option value="">Selecciona una tienda</option>
                            @foreach($tiendas as $tienda)
                            <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Vendedor</label>
                        <select name="id_vendedor" class="form-control" required>
                            <option value="">Selecciona un vendedor</option>
                            @foreach($vendedores as $vendedor)
                            <option value="{{ $vendedor->id_vendedor }}">{{ $vendedor->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Nombre de la caja</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Caja Principal" maxlength="100">
                    </div>

                    <div>
                        <label>Monto de apertura</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" name="monto_apertura" class="form-control" value="0" step="0.01" min="0"
                                required>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-folder-open"></i> Abrir
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
