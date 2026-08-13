<div class="modal fade" id="edit{{ $trabajo->id_trabajo }}">

    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.trabajos.update', $trabajo->id_trabajo) }}" method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Trabajo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-12">
                        <label>Título</label>
                        <input type="text" name="titulo" value="{{ $trabajo->titulo }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Cliente</label>
                        <input type="text" name="cliente" value="{{ $trabajo->cliente }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control">

                        @if($trabajo->imagen)
                        <img src="{{ asset($trabajo->imagen) }}" width="100" class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control editor">{{ $trabajo->descripcion }}</textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" value="{{ $trabajo->orden }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>

                        <select name="estado" class="form-control">

                            <option value="1" {{ $trabajo->estado ? 'selected' : '' }}>
                                Activo
                            </option>

                            <option value="0" {{ !$trabajo->estado ? 'selected' : '' }}>
                                Inactivo
                            </option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">

                        <i class="fa fa-times"></i>
                        Cerrar

                    </button>

                    <button type="submit" class="btn btn-warning">

                        <i class="fa fa-edit"></i>
                        Actualizar

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>