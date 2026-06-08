<div class="modal fade" id="modalImportar">
    <div class="modal-dialog">

        <form action="{{ route('admin.productos.importar') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Importar Productos</h5>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-info mb-3">

                        <i class="fa fa-info-circle"></i>

                        Descargue la plantilla Excel y complete los productos
                        antes de realizar la importación.

                    </div>

                    <div class="mb-3">

                        <a href="{{ route('admin.productos.plantilla') }}" class="btn btn-primary">

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