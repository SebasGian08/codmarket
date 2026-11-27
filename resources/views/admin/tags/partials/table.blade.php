<div class="table-responsive">
    <table class="table table-hover admin-data-table" id="{{ $dataTableId }}">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Blogs</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
            <tr>
                <td>{{ $tag->id_blogs_tags }}</td>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td><span class="badge bg-secondary">{{ $tag->blogs_count }}</span></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary btn-round"
                        data-bs-toggle="modal" data-bs-target="#editBlogTag{{ $tag->id_blogs_tags }}">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.tags.destroy', $tag->id_blogs_tags) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-round btn-delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
