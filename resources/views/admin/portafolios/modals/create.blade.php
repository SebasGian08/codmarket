<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-lg">

        <form method="POST" action="{{ route('admin.portafolios.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Portafolio</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Servicio</label>
                    <select name="service_id" class="form-control mb-2">
                        @foreach($services as $s)
                        <option value="{{ $s->id_service }}">{{ $s->nombre }}</option>
                        @endforeach
                    </select>

                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control mb-2">

                    <label>Cliente</label>
                    <input type="text" name="cliente" class="form-control mb-2">

                    <label>Categoría</label>
                    <input type="text" name="categoria" class="form-control mb-2">

                    <label>Tipo</label>
                    <input type="text" name="tipo" class="form-control mb-2">

                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control mb-2"></textarea>

                    <label>URL Demo</label>
                    <input type="text" name="url_demo" class="form-control mb-2">

                    <label>Imagen</label>
                    <input type="file" name="imagen" class="form-control">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>