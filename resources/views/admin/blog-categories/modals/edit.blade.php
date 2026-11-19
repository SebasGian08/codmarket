<div class="modal fade" id="edit{{ $category->id_blogs_categories }}">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.blog-categories.update', $category->id_blogs_categories) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Editar Categoría del Blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Nombre</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" required maxlength="200">
                    <small class="text-muted">El slug se regenera automáticamente</small>
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
