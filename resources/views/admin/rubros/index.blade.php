@extends('admin.layouts.app')

@section('title', 'Rubros')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Rubros</h4>

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
            <i class="fa fa-plus"></i> Nuevo Rubro
        </button>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($rubros as $rubro)
                        <tr>
                            <td>{{ $rubro->id }}</td>
                            <td>{{ $rubro->nombre }}</td>
                            <td>{{ $rubro->slug }}</td>
                            <td>{{ $rubro->orden }}</td>

                            <td>
                                <span class="badge {{ $rubro->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $rubro->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                @if($rubro->imagen)
                                    <img src="{{ asset($rubro->imagen) }}" width="50">
                                @endif
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $rubro->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.rubros.destroy', $rubro->id) }}"
                                      method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.rubros.modals.edit')

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.rubros.modals.create')

@endsection