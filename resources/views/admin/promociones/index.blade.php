@extends('admin.layouts.app')

@section('title', 'Promociones')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div>
            <h4 class="page-title">Promociones</h4>        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nueva Promoción
        </button>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Subtítulo</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($promociones as $p)
                        <tr>
                            <td>{{ $p->id_promocion }}</td>
                            <td>{{ $p->titulo }}</td>
                            <td>{{ $p->subtitulo }}</td>
                            <td>{{ $p->orden }}</td>

                            <td>
                                <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                @if($p->imagen)
                                    <img src="{{ asset($p->imagen) }}" width="50">
                                @endif
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $p->id_promocion }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.promociones.destroy', $p->id_promocion) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.promociones.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.promociones.modals.create')

@endsection