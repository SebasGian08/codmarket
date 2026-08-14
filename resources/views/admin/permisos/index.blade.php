@extends('admin.layouts.app')

@section('title', 'Permisos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Permisos</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Permiso
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
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($permisos as $permiso)

                        <tr>

                            <td>{{ $permiso->id_permiso }}</td>

                            <td>{{ $permiso->nombre }}</td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $permiso->codigo }}
                                </span>
                            </td>

                            <td>{{ $permiso->descripcion }}</td>

                            <td>
                                <span class="badge {{ $permiso->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $permiso->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $permiso->id_permiso }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.permisos.destroy', $permiso->id_permiso) }}" method="POST"
                                    style="display:inline;">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                        @include('admin.permisos.modals.edit')

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.permisos.modals.create')

@endsection