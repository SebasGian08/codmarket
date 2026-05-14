@extends('admin.layouts.app')

@section('title', 'Gestión de Portafolio')

@section('content')

<div class="page-inner">

    <!-- HEADER (IGUAL A SERVICIOS) -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Portafolio</h4>

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
                    <a href="{{ route('admin.portafolios.index') }}">Portafolio</a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    <a>Listado</a>
                </li>
            </ul>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Portafolio
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
                            <th>Servicio</th>
                            <th>Título</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($portafolios as $p)
                        <tr>
                            <td>{{ $p->id }}</td>

                            <td>{{ $p->service->nombre ?? '-' }}</td>

                            <td>{{ $p->titulo }}</td>

                            <td>{{ $p->cliente }}</td>

                            <td>
                                <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                {{ $p->created_at ? $p->created_at->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                <!-- EDIT -->
                                <button class="btn btn-sm mt-2 btn-primary btn-border btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $p->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <!-- DELETE -->
                                <form action="{{ route('admin.portafolios.destroy', $p->id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete"
                                        data-id="{{ $p->id }}"
                                        data-name="{{ $p->titulo }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.portafolios.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.portafolios.modals.create')

@endsection