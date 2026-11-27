<div class="table-responsive">
    <table class="table table-striped table-hover admin-data-table" id="{{ $dataTableId }}">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($blogs as $blog)
            <tr>
                <td>{{ $blog->id_blog }}</td>
                <td>{{ $blog->title }}</td>
                <td>{{ $blog->category->name ?? '-' }}</td>
                <td>
                    <span class="badge {{ $blog->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $blog->status ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>{{ $blog->created_at->format('d/m/Y') }}</td>
                <td>
                    <button type="button" class="btn btn-sm mt-2 btn-primary btn-border btn-round"
                        data-bs-toggle="modal" data-bs-target="#editBlog{{ $blog->id_blog }}">
                        <i class="fa fa-edit"></i>
                    </button>

                    <form action="{{ route('admin.blogs.destroy', $blog->id_blog) }}" method="POST"
                        id="delete-form-blog-{{ $blog->id_blog }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete"
                            data-id="{{ $blog->id_blog }}" data-name="{{ $blog->title }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
