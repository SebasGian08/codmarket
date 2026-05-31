<div class="modal fade" id="edit{{ $cat->id_categoria }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.categorias.update', $cat->id_categoria) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Categoría</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ $cat->nombre }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Icono</label>
                        <input type="text" name="icono" value="{{ $cat->icono }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control editor">{{ $cat->descripcion }}</textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control">

                        @if($cat->imagen)
                        <img src="{{ asset($cat->imagen) }}" width="80" class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" value="{{ $cat->orden }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $cat->estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $cat->estado == 0 ? 'selected' : '' }}>Inactivo</option>
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