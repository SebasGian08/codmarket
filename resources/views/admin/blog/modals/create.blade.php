<div class="modal fade" id="modalCreate" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        Crear Blog
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="row g-3">

                        <!-- IZQUIERDA -->
                        <div class="col-md-6">

                            <label class="form-label">Título</label>
                            <input type="text" name="title" class="form-control" required>

                            <label class="form-label mt-2">Resumen</label>
                            <textarea name="excerpt" class="form-control editor" rows="3"></textarea>
                            
                            <label class="form-label mt-2">Categoría</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_blogs_categories }}">
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            <label class="form-label mt-2">Imagen</label>
                            <input type="file" name="image" class="form-control" onchange="previewCreate(event)">

                            <!-- PREVIEW -->
                            <div class="mt-2 text-center">
                                <img id="previewCreateImg" src="" class="img-fluid rounded shadow" style="max-height:150px; display:none;">
                            </div>

                        </div>

                        <!-- DERECHA -->
                        <div class="col-md-6">

                            <label class="form-label">Tags</label>
                            <select name="tags[]" class="form-select" multiple style="height: 150px;">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id_blogs_tags }}">
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <!-- CONTENIDO -->
                        <div class="col-md-12">
                            <label class="form-label mt-3">Contenido</label>
                            <textarea name="content" class="form-control editor" rows="6"></textarea>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar Blog
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>