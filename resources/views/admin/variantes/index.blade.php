@extends('admin.layouts.app')

@section('title', 'Variantes de Producto')

@section('content')
<div class="page-inner">

    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="page-title mb-0">Variantes - {{ $producto->nombre }}</h4>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus"></i> Nueva Variante
            </button>

            <a href="{{ route('admin.productos.index') }}" class="btn btn-light border">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
        </div>

    </div>

    <!-- CONTENIDO -->
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Precio</th>
                            <th>Oferta</th>
                            <th>Costo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($variantes as $v)
                        <tr>
                            <td>{{ $v->id_variante }}</td>
                            <td>{{ $v->sku }}</td>
                            <td>S/ {{ $v->precio }}</td>
                            <td>{{ $v->precio_oferta ?? '-' }}</td>
                            <td>{{ $v->costo ?? '-' }}</td>

                            <td>
                                <span class="badge {{ $v->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $v->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $v->id_variante }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.variantes.destroy', $v->id_variante) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.variantes.modals.edit')
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.variantes.modals.create')
@endsection