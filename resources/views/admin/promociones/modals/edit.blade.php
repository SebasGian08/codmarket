<div class="modal fade" id="edit{{ $p->id_promocion }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.promociones.update', $p->id_promocion) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Promoción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Título</label>
                        <input type="text" name="titulo" value="{{ $p->titulo }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Subtítulo</label>
                        <input type="text" name="subtitulo" value="{{ $p->subtitulo }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control editor">{{ $p->descripcion }}</textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen</label>
                        <input type="file" name="imagen" class="form-control">
                        @if($p->imagen)
                        <img src="{{ asset($p->imagen) }}" width="60" class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Imagen Mobile</label>
                        <input type="file" name="imagen_mobile" class="form-control">
                        @if($p->imagen_mobile)
                        <img src="{{ asset($p->imagen_mobile) }}" width="60" class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Enlace</label>
                        <input type="text" name="enlace" value="{{ $p->enlace }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Texto botón</label>
                        <input type="text" name="texto_boton" value="{{ $p->texto_boton }}" class="form-control">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Color texto</label>
                        <input type="color" name="color_texto" value="{{ $p->color_texto }}"
                            class="form-control form-control-color">
                    </div>

                    <div class="col-md-4 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" value="{{ $p->orden }}" class="form-control">
                    </div>

                    <div class="col-md-4 mt-2">
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
                    <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button>
                </div>

            </div>

        </form>

    </div>
</div>