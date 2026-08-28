@extends('admin.layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Clientes</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Cliente
        </button>

    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id_cliente }}</td>
                            <td>
                                @if($cliente->logo)
                                <img src="{{ asset($cliente->logo) }}" alt="Logo" style="height: 36px; object-fit: contain;">
                                @elseif($cliente->imagen)
                                <img src="{{ asset($cliente->imagen) }}" alt="Imagen"
                                    style="height: 36px; width: 36px; object-fit: cover; border-radius: 6px;">
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $cliente->nombre }}</td>
                            <td>
                                {{ $cliente->tipoDocumento->codigo ?? '' }}
                                {{ $cliente->numero_documento ?? '—' }}
                            </td>
                            <td>{{ $cliente->telefono ?? '—' }}</td>
                            <td>{{ $cliente->correo ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $cliente->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $cliente->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $cliente->id_cliente }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.clientes.destroy', $cliente->id_cliente) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.clientes.modals.edit')

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.clientes.modals.create')

@endsection
