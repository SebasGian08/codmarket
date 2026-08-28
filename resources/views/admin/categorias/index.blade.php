@extends('admin.layouts.app')

@section('title', 'Categorías')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Categorías</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Categoría
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
                            <th>Categoría padre</th>
                            <th>Slug</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($categorias as $cat)
                        <tr>
                            <td>{{ $cat->id_categoria }}</td>
                            <td>
                                @if($cat->id_categoria_padre)
                                <span class="text-muted me-1">↳</span> {{ $cat->nombre }}
                                @else
                                <strong>{{ $cat->nombre }}</strong>
                                @endif
                            </td>
                            <td>{{ $cat->padre->nombre ?? '—' }}</td>
                            <td>{{ $cat->slug }}</td>
                            <td>{{ $cat->orden }}</td>

                            <td>
                                <span class="badge {{ $cat->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $cat->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                @if($cat->imagen)
                                <img src="{{ asset($cat->imagen) }}" width="50">
                                @endif
                            </td>
                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $cat->id_categoria }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.categorias.destroy', $cat->id_categoria) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.categorias.modals.edit')

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.categorias.modals.create')

@endsection