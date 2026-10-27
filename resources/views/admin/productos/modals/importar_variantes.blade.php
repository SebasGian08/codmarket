<div class="modal fade" id="modalImportarVariantes">
    <div class="modal-dialog">

        <form action="{{ route('admin.variantes.importar') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Importar Variantes</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info mb-3">

                        <i class="fa fa-info-circle"></i>

                        Descargue la plantilla Excel y complete las variantes
                        de sus productos ya creados. En la columna
                        <b>producto_sku</b> indique el SKU del producto
                        (el de su variante principal). Si deja la columna
                        <b>sku</b> vacía, se generará uno automáticamente.

                    </div>

                    <div class="mb-3">

                        <a href="{{ route('admin.variantes.plantilla') }}" class="btn btn-primary">

                            <i class="fa fa-download"></i>
                            Descargar Plantilla

                        </a>

                    </div>

                    <div class="mb-3">
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#listaProductosParaVariantes" aria-expanded="false"
                            aria-controls="listaProductosParaVariantes">
                            <i class="fa fa-list"></i> Consultar productos y SKU
                        </button>

                        <div class="collapse mt-3" id="listaProductosParaVariantes">
                            <div class="border rounded p-3">
                                <p class="small text-muted mb-2">
                                    Usa uno de estos SKU en la columna <b>producto_sku</b> para relacionar
                                    la nueva variante con su producto. Si no tiene SKU, puedes usar el nombre exacto.
                                </p>

                                <input type="search" class="form-control form-control-sm mb-2"
                                    id="buscarProductoParaVariante" placeholder="Buscar por producto o SKU">

                                <div class="table-responsive variante-importacion-lista">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>SKU para producto_sku</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productosParaVariantes">
                                            @forelse($productosParaVariantes as $producto)
                                                @php($skus = $producto->variantes->pluck('sku')->filter()->values())
                                                <tr data-busqueda="{{ strtolower($producto->nombre . ' ' . $skus->implode(' ')) }}">
                                                    <td>{{ $producto->nombre }}</td>
                                                    <td>
                                                        @forelse($skus as $sku)
                                                            <span class="badge bg-light text-dark border">{{ $sku }}</span>
                                                        @empty
                                                            <span class="text-muted">Sin SKU de variante</span>
                                                        @endforelse
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted">No hay productos disponibles.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">

                        <label class="form-label">
                            Archivo Excel
                        </label>

                        <input type="file" name="archivo" class="form-control" accept=".xlsx,.xls" required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">

                        Cerrar

                    </button>

                    <button type="submit" class="btn btn-success">

                        <i class="fa fa-file-excel"></i>
                        Importar

                    </button>

                </div>

            </div>

        </form>

    </div>
</div>

<script>
    document.getElementById('buscarProductoParaVariante').addEventListener('input', function () {
        var busqueda = this.value.toLocaleLowerCase();
        document.querySelectorAll('#productosParaVariantes tr[data-busqueda]').forEach(function (fila) {
            fila.hidden = !fila.dataset.busqueda.includes(busqueda);
        });
    });
</script>