@extends('admin.layouts.app')

@section('title', 'Trabajos Realizados')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">

            <h4 class="page-title">Trabajos Realizados</h4>

        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">

            <i class="fa fa-plus"></i>
            Nuevo Trabajo

        </button>

    </div>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Cliente</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($trabajos as $trabajo)

                        <tr>

                            <td>{{ $trabajo->id_trabajo }}</td>

                            <td>
                                @if($trabajo->imagen)
                                <img src="{{ asset($trabajo->imagen) }}" width="60">
                                @endif
                            </td>

                            <td>{{ $trabajo->titulo }}</td>

                            <td>{{ $trabajo->cliente }}</td>

                            <td>{{ $trabajo->orden }}</td>

                            <td>
                                <span class="badge {{ $trabajo->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $trabajo->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $trabajo->id_trabajo }}">

                                    <i class="fa fa-edit"></i>

                                </button>

                                <form action="{{ route('admin.trabajos.update', $trabajo->id_trabajo) }}" method="POST"
                                    enctype="multipart/form-data">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">

                                        <i class="fa fa-trash"></i>

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @include('admin.trabajos.modals.edit')

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@include('admin.trabajos.modals.create')

@endsection