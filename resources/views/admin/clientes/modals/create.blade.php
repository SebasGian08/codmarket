<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.clientes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-8">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required maxlength="150">
                    </div>

                    <div class="col-md-4">
                        <label>Tipo de documento</label>
                        <select name="id_tipo_documento" class="form-control">
                            <option value="">—</option>
                            @foreach($tiposDocumento as $td)
                            <option value="{{ $td->id_tipo_documento }}">{{ $td->codigo }} - {{ $td->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>N° documento</label>
                        <input type="text" name="numero_documento" class="form-control" maxlength="20">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" maxlength="30">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control" maxlength="150">
                    </div>

                    <div class="col-md-8 mt-2">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" maxlength="255">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Imagen <small class="text-muted">(Se usa en el carrusel de clientes)</small></label>
                        <input type="file" name="imagen" class="form-control">
                    </div>

                    <div class="col-md-6 mt-3">
                        <label>Logo <small class="text-muted">(Se recomienda 800px x 400px)</small></label>
                        <input type="file" name="logo" class="form-control">
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
