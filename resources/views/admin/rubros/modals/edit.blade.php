<div class="modal fade" id="edit{{ $rubro->id }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.rubros.update', $rubro->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Rubro</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ $rubro->nombre }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control">

                        @if($rubro->imagen)
                            <img src="{{ asset($rubro->imagen) }}" width="80" class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" value="{{ $rubro->orden }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $rubro->estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $rubro->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control">{{ $rubro->descripcion }}</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-dark" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-warning">Actualizar</button>
                </div>

            </div>

        </form>

    </div>
</div>