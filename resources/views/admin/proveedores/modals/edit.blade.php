<div class="modal fade" id="edit{{ $p->id_proveedor }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.proveedores.update', $p->id_proveedor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Proveedor</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ $p->nombre }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Tipo Documento</label>
                        <select name="id_tipo_documento" class="form-control">
                            @foreach($tipos as $t)
                            <option value="{{ $t->id_tipo_documento }}"
                                {{ $p->id_tipo_documento == $t->id_tipo_documento ? 'selected' : '' }}>
                                {{ $t->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>N° Documento</label>
                        <input type="text" name="numero_documento" value="{{ $p->numero_documento }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Contacto</label>
                        <input type="text" name="contacto" value="{{ $p->contacto }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" value="{{ $p->telefono }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Correo</label>
                        <input type="email" name="correo" value="{{ $p->correo }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Dirección</label>
                        <textarea name="direccion" class="form-control">{{ $p->direccion }}</textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $p->estado ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ !$p->estado ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Actualizar
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>