<div class="modal fade" id="edit{{ $tienda->id_tienda }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.tiendas.update', $tienda->id_tienda) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Tienda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-4">
                        <label>Código</label>
                        <input type="text" name="codigo" class="form-control" value="{{ $tienda->codigo }}" required
                            maxlength="10">
                    </div>

                    <div class="col-md-8">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $tienda->nombre }}" required
                            maxlength="150">
                    </div>

                    <div class="col-md-8 mt-2">
                        <label>Dirección</label>
                        <input type="text" name="direccion" class="form-control" value="{{ $tienda->direccion }}"
                            maxlength="255">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ $tienda->telefono }}"
                            maxlength="30">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $tienda->estado ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$tienda->estado ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-2 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="es_principal" value="1"
                                id="esPrincipalEdit{{ $tienda->id_tienda }}" {{ $tienda->es_principal ? 'checked' : '' }}>
                            <label class="form-check-label" for="esPrincipalEdit{{ $tienda->id_tienda }}">Tienda
                                principal</label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Actualizar
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
