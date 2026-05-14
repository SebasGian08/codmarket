<div class="modal fade" id="edit{{ $p->id }}">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('admin.portafolios.update', $p->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Portafolio</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Servicio</label>
                    <select name="service_id" class="form-control mb-2">
                        @foreach($services as $s)
                        <option value="{{ $s->id_service }}" {{ $p->service_id == $s->id_service ? 'selected' : '' }}>
                            {{ $s->nombre }}
                        </option>
                        @endforeach
                    </select>

                    <label>Título</label>
                    <input type="text" name="titulo" value="{{ $p->titulo }}" class="form-control mb-2">

                    <label>Cliente</label>
                    <input type="text" name="cliente" value="{{ $p->cliente }}" class="form-control mb-2">

                    <label>Categoría</label>
                    <input type="text" name="categoria" value="{{ $p->categoria }}" class="form-control mb-2">

                    <label>Tipo</label>
                    <input type="text" name="tipo" value="{{ $p->tipo }}" class="form-control mb-2">

                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control mb-2">{{ $p->descripcion }}</textarea>

                    <label>URL Demo</label>
                    <input type="text" name="url_demo" value="{{ $p->url_demo }}" class="form-control mb-2">

                    <label>Imagen</label>
                    <input type="file" name="imagen" class="form-control">

                    @if($p->imagen)
                    <img src="{{ asset('storage/'.$p->imagen) }}" width="100" class="mt-2">
                    @endif

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