<div class="modal fade" id="edit{{ $permiso->id_permiso }}">

    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.permisos.update', $permiso->id_permiso) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Permiso</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ $permiso->nombre }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Código</label>
                        <input type="text" name="codigo" value="{{ $permiso->codigo }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>

                        <textarea name="descripcion" class="form-control">{{ $permiso->descripcion }}</textarea>

                    </div>

                    <div class="col-md-6 mt-2">

                        <label>Estado</label>

                        <select name="estado" class="form-control">

                            <option value="1" {{ $permiso->estado == 1 ? 'selected' : '' }}>
                                Activo
                            </option>

                            <option value="0" {{ $permiso->estado == 0 ? 'selected' : '' }}>
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button class="btn btn-dark" data-bs-dismiss="modal">
                        Cerrar
                    </button>

                    <button class="btn btn-warning">
                        Actualizar
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>