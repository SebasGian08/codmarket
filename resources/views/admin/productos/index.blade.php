@extends('admin.layouts.app')

@section('title', 'Productos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Productos</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Listado</li>
            </ul>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Producto
        </button>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Proveedor</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($productos as $p)
                        <tr>
                            <td>{{ $p->id_producto }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->marca->nombre ?? '-' }}</td>
                            <td>{{ $p->proveedor->nombre ?? '-' }}</td>

                            <td>
                                <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-wrap gap-1">

                                    <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $p->id_producto }}">
                                        <i class="fa fa-edit"></i> <span class="d-none d-md-inline">Editar</span>
                                    </button>

                                    <form action="{{ route('admin.productos.destroy', $p->id_producto) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                            <i class="fa fa-trash"></i> <span class="d-none d-md-inline">Eliminar</span>
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.variantes.index', $p->id_producto) }}"
                                        class="btn btn-sm btn-info btn-round">
                                        <i class="fa fa-cubes"></i> <span class="d-none d-md-inline">Variantes</span>
                                    </a>

                                    <a href="{{ route('admin.producto_imagen.index', $p->id_producto) }}"
                                        class="btn btn-sm btn-warning btn-round">
                                        <i class="fa fa-image"></i> <span class="d-none d-md-inline">Imágenes</span>
                                    </a>

                                </div>
                            </td>
                        </tr>

                        @include('admin.productos.modals.edit')

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.productos.modals.create')

@endsection