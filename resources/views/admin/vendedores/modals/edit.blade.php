<div class="modal fade" id="edit{{ $vendedor->id_vendedor }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.vendedores.update', $vendedor->id_vendedor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Vendedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-8">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ $vendedor->nombre }}" required
                            maxlength="150">
                    </div>

                    <div class="col-md-4">
                        <label>Usuario del sistema</label>
                        <select name="id_usuario" class="form-control">
                            <option value="">—</option>
                            @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id_usuario }}"
                                {{ $vendedor->id_usuario == $usuario->id_usuario ? 'selected' : '' }}>
                                {{ $usuario->nombres }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>N° documento</label>
                        <input type="text" name="documento" class="form-control" value="{{ $vendedor->documento }}"
                            maxlength="20">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ $vendedor->telefono }}"
                            maxlength="30">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Correo</label>
                        <input type="email" name="correo" class="form-control" value="{{ $vendedor->correo }}"
                            maxlength="150">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Tiendas asignadas</label>
                        @php($tiendasIds = $vendedor->tiendas->pluck('id_tienda')->toArray())
                        <select name="tiendas[]" class="form-control" multiple>
                            @foreach($tiendas as $tienda)
                            <option value="{{ $tienda->id_tienda }}" {{ in_array($tienda->id_tienda, $tiendasIds) ? 'selected' : '' }}>
                                {{ $tienda->codigo }} - {{ $tienda->nombre }}
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Mantén Ctrl (o Cmd) para seleccionar varias</div>
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $vendedor->estado ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$vendedor->estado ? 'selected' : '' }}>Inactivo</option>
                        </select>
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
