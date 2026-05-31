<div class="modal fade" id="edit{{ $blog->id_blog }}" tabindex="-1">
    <div class="modal-dialog modal-xl">

        <form method="POST" action="{{ route('admin.blogs.update', $blog->id_blog) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        Editar Blog
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="row g-3">

                        <!-- IZQUIERDA -->
                        <div class="col-md-6">

                            <label class="form-label">Título</label>
                            <input type="text" name="title" value="{{ $blog->title }}" class="form-control">

                            <label class="form-label mt-2">Resumen</label>
                            <textarea name="excerpt" class="form-control editor" rows="3">{{ $blog->excerpt }}</textarea>

                            <label class="form-label mt-2">Categoría</label>
                            <select name="category_id" class="form-select">
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id_blogs_categories }}"
                                    {{ $blog->category_id == $cat->id_blogs_categories ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>

                            <label class="form-label mt-2">Estado</label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $blog->status ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !$blog->status ? 'selected' : '' }}>Inactivo</option>
                            </select>

                            <label class="form-label mt-2">Imagen</label>
                            <input type="file" name="image" class="form-control"
                                onchange="previewEdit(event, {{ $blog->id_blog }})">

                            <!-- IMAGEN ACTUAL -->
                            <div class="mt-2 text-center">
                                @if($blog->image)
                                <img id="previewEditImg{{ $blog->id_blog }}" src="{{ asset($blog->image) }}"
                                    class="img-fluid rounded shadow" style="max-height:150px;">
                                @else
                                <img id="previewEditImg{{ $blog->id_blog }}" class="img-fluid rounded shadow"
                                    style="max-height:150px; display:none;">
                                @endif
                            </div>

                        </div>

                        <!-- DERECHA -->
                        <div class="col-md-6">

                            <label class="form-label">Tags</label>
                            <select name="tags[]" class="form-select" multiple style="height: 150px;">
                                @foreach($tags as $tag)
                                <option value="{{ $tag->id_blogs_tags }}"
                                    {{ $blog->tags->contains($tag->id_blogs_tags) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                                @endforeach
                            </select>

                        </div>

                        <!-- CONTENIDO -->
                        <div class="col-md-12">
                            <label class="form-label mt-3">Contenido</label>
                            <textarea name="content" class="form-control editor" rows="6">
                                {!! $blog->content !!}
                            </textarea>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
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