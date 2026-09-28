<div class="modal fade" id="edit{{ $tag->id_blogs_tags }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.tags.update', $tag->id_blogs_tags) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Tag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-12">
                        <label>Nombre</label>
                        <input type="text" name="name" value="{{ $tag->name }}" class="form-control" required>
                        <small class="text-muted">El slug se regenera automáticamente</small>
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