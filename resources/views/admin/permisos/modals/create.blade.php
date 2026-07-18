<div class="modal fade" id="modalCreate">

    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.permisos.store') }}" method="POST">

            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Permiso</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Código</label>
                        <input type="text" name="codigo" class="form-control" placeholder="ejemplo: productos.crear"
                            required>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control"></textarea>
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
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>

            </div>

        </form>

    </div>

</div>