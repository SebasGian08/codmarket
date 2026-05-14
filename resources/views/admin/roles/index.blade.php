@extends('admin.layouts.app')

@section('title', 'Gestión de Roles')

@section('content')

<div class="page-inner">

    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Roles</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>

                <li class="separator"><i class="icon-arrow-right"></i></li>

                <li class="nav-item">
                    <a href="{{ route('admin.roles.index') }}">Roles</a>
                </li>

                <li class="separator"><i class="icon-arrow-right"></i></li>

                <li class="nav-item">Listado</li>
            </ul>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Rol
        </button>
    </div>

    <!-- CARD -->
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($roles as $rol)
                        <tr>
                            <td>{{ $rol->id_rol }}</td>

                            <td>{{ $rol->nombre }}</td>

                            <td>
                                <span class="badge {{ $rol->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $rol->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                {{ $rol->created_at ? $rol->created_at->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <!-- EDIT -->
                                <button class="btn btn-sm mt-2 btn-primary btn-border btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $rol->id_rol }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <!-- DELETE -->
                                <form action="{{ route('admin.roles.destroy', $rol->id_rol) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete"
                                        data-id="{{ $rol->id_rol }}"
                                        data-name="{{ $rol->nombre }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.roles.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.roles.modals.create')

@endsection