@extends('admin.layouts.app')

@section('title', 'Tiendas')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Tiendas</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nueva Tienda
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
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th class="text-center">Principal</th>
                            <th>Cajas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tiendas as $tienda)
                        <tr>
                            <td><span class="badge bg-dark">{{ $tienda->codigo }}</span></td>
                            <td class="fw-semibold">{{ $tienda->nombre }}</td>
                            <td>{{ $tienda->direccion ?? '—' }}</td>
                            <td>{{ $tienda->telefono ?? '—' }}</td>
                            <td class="text-center">
                                @if($tienda->es_principal)
                                <span class="badge bg-warning text-dark"><i class="fa fa-star"></i> Principal</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $tienda->cajas_count }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $tienda->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $tienda->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $tienda->id_tienda }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.tiendas.destroy', $tienda->id_tienda) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.tiendas.modals.edit')

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.tiendas.modals.create')

@endsection
