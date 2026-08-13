<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.promociones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nueva Promoción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Subtítulo</label>
                        <input type="text" name="subtitulo" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control editor"></textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control" required>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen Mobile</label>
                        <input type="file" name="imagen_mobile" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Enlace</label>
                        <input type="text" name="enlace" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Texto botón</label>
                        <input type="text" name="texto_boton" class="form-control">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Color texto</label>
                        <input type="color" name="color_texto" class="form-control form-control-color" value="#ffffff">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" class="form-control" value="0">
                    </div>

                    <div class="col-md-4 mt-2">
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