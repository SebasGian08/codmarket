<div class="modal fade" id="modalConfig" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('admin.config.store') }}">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nueva Configuración</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6">

                            <label>Categoría</label>
                            <input name="categoria" class="form-control mb-2" required>

                            <label>Clave</label>
                            <input name="clave" class="form-control mb-2" required>

                            <label>Valor</label>
                            <input name="valor" class="form-control mb-2">

                        </div>

                        <div class="col-md-6">

                            <label>Descripción</label>
                            <input name="descripcion" class="form-control mb-2">

                            <label>Tipo</label>
                            <select name="tipo" class="form-control mb-2" required>
                                <option value="text">Text</option>
                                <option value="color">Color</option>
                                <option value="number">Number</option>
                                <option value="textarea">Textarea</option>
                                <option value="boolean">Boolean</option>
                                <option value="select">Select</option>
                                <option value="image">Image</option>
                                <option value="email">Email</option>
                                <option value="url">URL</option>
                            </select>

                            <label>Opciones (solo select)</label>
                            <input name="opciones" class="form-control" placeholder="op1,op2,op3">

                            <label>Orden</label>
                            <input name="orden" type="number" class="form-control">

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