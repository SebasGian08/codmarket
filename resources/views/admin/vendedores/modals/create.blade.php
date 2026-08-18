<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.vendedores.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Vendedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-8">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" required maxlength="150">
                    </div>

                    <div class="col-md-4">
                        <label>Usuario del sistema</label>
                        <select name="id_usuario" class="form-control">
                            <option value="">—</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombres }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>N° documento</label>
                        <input type="text" name="documento" class="form-control" maxlength="20">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" maxlength="30">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control" maxlength="150">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Tiendas asignadas</label>
                        <select name="tiendas[]" class="form-control" multiple>
                            @foreach($tiendas as $tienda)
                            <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Mantén Ctrl (o Cmd) para seleccionar varias</div>
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
