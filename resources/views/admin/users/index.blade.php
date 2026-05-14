@extends('admin.layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="page-inner">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Usuarios</h4>
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
                    <a href="{{ route('admin.users.index') }}">Usuarios</a>
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
                <i class="fa fa-plus"></i> Nuevo Usuario
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id_usuario }}</td>
                                    <td>{{ $user->nombres }} {{ $user->apellidos }}</td>
                                    <td>{{ $user->telefono }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge badge-info">{{ $user->rol->nombre ?? 'NO HAY' }}</span></td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm mt-2 btn-primary btn-border btn-round" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $user->id_usuario }}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.users.destroy', $user->id_usuario) }}"
                                            method="POST" id="delete-form-{{ $user->id_usuario }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete"
                                                data-id="{{ $user->id_usuario }}"
                                                data-name="{{ $user->nombres }} {{ $user->apellidos }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                @include('admin.users.modals.edit')

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@foreach($users as $user)
@include('admin.users.modals.edit')
@endforeach

@include('admin.users.modals.create')

@endsection

@push('scripts')


@endpush