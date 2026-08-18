@extends('admin.layouts.app')

@section('title', 'Vendedores')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Vendedores</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Vendedor
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
                            <th>Nombre</th>
                            <th>Documento</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Tiendas</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($vendedores as $vendedor)
                        <tr>
                            <td>{{ $vendedor->id_vendedor }}</td>
                            <td class="fw-semibold">{{ $vendedor->nombre }}</td>
                            <td>{{ $vendedor->documento ?? '—' }}</td>
                            <td>{{ $vendedor->telefono ?? '—' }}</td>
                            <td>{{ $vendedor->correo ?? '—' }}</td>
                            <td>
                                @forelse($vendedor->tiendas as $tienda)
                                <span class="badge bg-light text-dark border">{{ $tienda->nombre }}</span>
                                @empty
                                <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td>{{ $vendedor->usuario->nombres ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $vendedor->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $vendedor->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $vendedor->id_vendedor }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.vendedores.destroy', $vendedor->id_vendedor) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.vendedores.modals.edit')

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.vendedores.modals.create')

@endsection
