@extends('admin.layouts.app')

@section('title', 'Proveedores')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Proveedores</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Proveedor
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
                            <th>Documento</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($proveedores as $p)
                        <tr>
                            <td>{{ $p->id_proveedor }}</td>
                            <td>{{ $p->nombre }}</td>

                            <td>
                                {{ $p->tipoDocumento->nombre ?? '' }} - {{ $p->numero_documento }}
                            </td>

                            <td>{{ $p->contacto }}</td>
                            <td>{{ $p->telefono }}</td>

                            <td>
                                <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $p->id_proveedor }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.proveedores.destroy', $p->id_proveedor) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.proveedores.modals.edit')

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.proveedores.modals.create')

@endsection