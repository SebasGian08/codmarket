@extends('admin.layouts.app')

@section('title', 'Gestión de Blog')

@section('content')

<div class="page-inner">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Blogs</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.blogs.index') }}">Blogs</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a>Listado</a>
                </li>
            </ul>
        </div>

        <div class="ms-md-auto py-2 py-md-0">
            <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus"></i> Nuevo Blog
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="basic-datatables">

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
                                <button class="btn btn-sm mt-2 btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $blog->id_blog }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.blogs.destroy', $blog->id_blog) }}" method="POST"
                                    id="delete-form-{{ $blog->id_blog }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete"
                                        data-id="{{ $blog->id_blog }}" data-name="{{ $blog->title }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.blog.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.blog.modals.create')

@endsection


@push('scripts')

<script>
ClassicEditor.create(document.querySelector('#editorCreate'));
@foreach($blogs as $blog)
ClassicEditor.create(document.querySelector('#editorEdit{{ $blog->id_blog }}'));
@endforeach
</script>

@endpush