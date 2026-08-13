<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.categorias.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Icono</label>
                        <input type="text" name="icono" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control editor"></textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" value="0" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>