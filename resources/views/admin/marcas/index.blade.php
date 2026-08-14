@extends('admin.layouts.app')

@section('title', 'Marcas')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Marcas</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nueva Marca
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
                            <th>Orden</th>
                            <th>Estado</th>
                            <th>Logo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($marcas as $m)
                        <tr>
                            <td>{{ $m->id_marca }}</td>
                            <td>{{ $m->nombre }}</td>
                            <td>{{ $m->slug }}</td>
                            <td>{{ $m->orden }}</td>

                            <td>
                                <span class="badge {{ $m->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $m->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                @if($m->logo)
                                    <img src="{{ asset($m->logo) }}" width="40">
                                @endif
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $m->id_marca }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.marcas.destroy', $m->id_marca) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.marcas.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.marcas.modals.create')

@endsection