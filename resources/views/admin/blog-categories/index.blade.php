@extends('admin.layouts.app')

@section('title', 'Categorías del Blog')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            @include('admin.partials.page_heading', [
                'icon' => 'fas fa-folder-open',
                'title' => 'Categorías del Blog',
                'description' => 'Organiza las publicaciones del blog por categorías.',
            ])
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nueva Categoría
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="basic-datatables">
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
                            <td>
                                <span class="badge bg-secondary">{{ $category->blogs_count }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $category->id_blogs_categories }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.blog-categories.destroy', $category->id_blogs_categories) }}"
                                      method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="btn btn-sm btn-danger btn-round btn-delete"
                                        data-name="{{ $category->name }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.blog-categories.modals.edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.blog-categories.modals.create')

@endsection
