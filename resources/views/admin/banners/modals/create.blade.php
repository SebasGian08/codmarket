<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Banner</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mt-2">
                            <label>Tipo de Banner</label>
                            <select name="solo_imagen" class="form-control solo_imagen">
                                <option value="0">Con contenido</option>
                                <option value="1">Solo imagen</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-6 campo-texto">
                            <label>Título</label>
                            <input type="text" name="titulo" class="form-control">
                        </div>

                        <div class="col-md-6 campo-texto">
                            <label>Subtítulo</label>
                            <input type="text" name="subtitulo" class="form-control">
                        </div>

                        <div class="col-md-12 mt-2 campo-texto">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control"></textarea>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Imagen Mobile</label>
                            <input type="file" name="imagen_mobile" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2 campo-texto">
                            <label>Imagen Referencial</label>
                            <input type="file" name="imagen_referencial" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2 campo-texto">
                            <label>Link</label>
                            <input type="text" name="enlace" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2 campo-texto">
                            <label>Texto botón</label>
                            <input type="text" name="texto_boton" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Orden</label>
                            <input type="number" name="orden" class="form-control" value="0">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Fecha inicio</label>
                            <input type="datetime-local" name="fecha_inicio" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Fecha fin</label>
                            <input type="datetime-local" name="fecha_fin" class="form-control">
                        </div>

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