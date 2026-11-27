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
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->id_blogs_categories }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td><span class="badge bg-secondary">{{ $category->blogs_count }}</span></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary btn-round"
                        data-bs-toggle="modal"
                        data-bs-target="#editBlogCategory{{ $category->id_blogs_categories }}">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.blog-categories.destroy', $category->id_blogs_categories) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-round btn-delete"
                            data-name="{{ $category->name }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
