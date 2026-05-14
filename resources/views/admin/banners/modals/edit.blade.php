<div class="modal fade" id="edit{{ $banner->id_banner }}">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('admin.banners.update', $banner->id_banner) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Banner</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mt-2">
                            <label>Tipo de Banner</label>
                            <select name="solo_imagen" class="form-control solo_imagen">
                                <option value="0" {{ $banner->solo_imagen == 0 ? 'selected' : '' }}>Con contenido
                                </option>
                                <option value="1" {{ $banner->solo_imagen == 1 ? 'selected' : '' }}>Solo imagen</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Estado</label>
                            <select name="estado" class="form-control">
                                <option value="1" {{ $banner->estado == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $banner->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>

                        <div class="col-md-6 campo-texto">
                            <label>Título</label>
                            <input type="text" name="titulo" value="{{ $banner->titulo }}" class="form-control">
                        </div>

                        <div class="col-md-6 campo-texto">
                            <label>Subtítulo</label>
                            <input type="text" name="subtitulo" value="{{ $banner->subtitulo }}" class="form-control">
                        </div>

                        <div class="col-md-12 mt-2 campo-texto">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control">{{ $banner->descripcion }}</textarea>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control">

                            @if($banner->imagen)
                            <img src="{{ url($banner->imagen) }}" width="120" class="mt-2 img-thumbnail">
                            @endif
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Imagen Mobile</label>
                            <input type="file" name="imagen_mobile" class="form-control">

                            @if($banner->imagen_mobile)
                            <img src="{{ url($banner->imagen_mobile) }}" width="120" class="mt-2 img-thumbnail">
                            @endif
                        </div>

                        <div class="col-md-6 mt-2 campo-texto">
                            <label>Imagen Referencial</label>
                            <input type="file" name="imagen_referencial" class="form-control">

                            @if($banner->imagen_referencial)
                            <img src="{{ url($banner->imagen_referencial) }}" width="120" class="mt-2 img-thumbnail">
                            @endif
                        </div>

                        <div class="col-md-6 mt-2 campo-texto">
                            <label>Link</label>
                            <input type="text" name="enlace" value="{{ $banner->enlace }}" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2 campo-texto">
                            <label>Texto botón</label>
                            <input type="text" name="texto_boton" value="{{ $banner->texto_boton }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Orden</label>
                            <input type="number" name="orden" value="{{ $banner->orden }}" class="form-control">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Fecha inicio</label>
                            <input type="datetime-local" name="fecha_inicio"
                                value="{{ $banner->fecha_inicio ? date('Y-m-d\TH:i', strtotime($banner->fecha_inicio)) : '' }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label>Fecha fin</label>
                            <input type="datetime-local" name="fecha_fin"
                                value="{{ $banner->fecha_fin ? date('Y-m-d\TH:i', strtotime($banner->fecha_fin)) : '' }}"
                                class="form-control">
                        </div>

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