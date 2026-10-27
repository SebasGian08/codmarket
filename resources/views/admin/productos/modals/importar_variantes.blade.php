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

                        La primera pestaña <b>VARIANTES</b> ya incluye los productos y el valor
                        <b>producto_sku</b> para relacionarlos. Complete en esa misma fila los datos
                        de la variante; deje <b>sku</b> vacío para que se genere automáticamente.
                        Las filas sin datos de variante se omiten al importar.

                    </div>

                    <div class="mb-3">

                        <a href="{{ route('admin.variantes.plantilla') }}" class="btn btn-primary">

                            <i class="fa fa-download"></i>
                            Descargar Plantilla

                        </a>

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