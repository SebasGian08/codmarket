@extends('admin.layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="page-inner">
    <div class="page-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Usuarios</h4>        </div>

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
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end mb-3">
                        <div class="col-md-6 col-lg-4">
                            <label class="form-label fw-semibold">Buscar usuario</label>
                            <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombres, email"
                                value="{{ $filtros['nombre'] ?? '' }}">
                        </div>
                        <div class="col-md-4 col-lg-3">
                            <label class="form-label fw-semibold">Rol</label>
                            <select name="id_rol" class="form-select form-select-sm">
                                <option value="">Todos</option>
                                @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}" {{ (string)($filtros['id_rol'] ?? '') === (string)$rol->id_rol ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-sm btn-primary btn-round">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary btn-round">
                                <i class="fa fa-eraser"></i> Limpiar
                            </a>
                        </div>
                    </form>
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