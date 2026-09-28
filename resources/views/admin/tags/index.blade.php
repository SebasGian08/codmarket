@extends('admin.layouts.app')

@section('title', 'Tags del Blog')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Tags del Blog</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Tag
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

                        @foreach($tags as $tag)
                        <tr>
                            <td>{{ $tag->id_blogs_tags }}</td>
                            <td>{{ $tag->name }}</td>
                            <td>{{ $tag->slug }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $tag->blogs_count }}</span>
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $tag->id_blogs_tags }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.tags.destroy', $tag->id_blogs_tags) }}"
                                      method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.tags.modals.edit')

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.tags.modals.create')

@endsection