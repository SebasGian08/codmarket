<div class="modal fade" id="edit{{ $m->id_marca }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.marcas.update', $m->id_marca) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Marca</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" value="{{ $m->nombre }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Slug</label>
                        <input type="text" name="slug" value="{{ $m->slug }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control">{{ $m->descripcion }}</textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control">
                        @if($m->logo)
                            <img src="{{ asset($m->logo) }}" width="50" class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Orden</label>
                        <input type="number" name="orden" value="{{ $m->orden }}" class="form-control">
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