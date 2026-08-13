<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.marcas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nueva Marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control editor"></textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Logo <small class="text-muted">(Se recomienda 800px x 400px)</small></label>
                        <input type="file" name="logo" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" class="form-control" value="0">
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