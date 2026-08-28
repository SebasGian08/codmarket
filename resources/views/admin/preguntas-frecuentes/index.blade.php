@extends('admin.layouts.app')

@section('title', 'Preguntas Frecuentes')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Preguntas Frecuentes</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nueva Pregunta
        </button>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pregunta</th>
                            <th>Orden</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($preguntas as $pf)
                        <tr>
                            <td>{{ $pf->id }}</td>
                            <td>{{ $pf->pregunta }}</td>
                            <td>{{ $pf->orden }}</td>

                            <td>
                                <span class="badge {{ $pf->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $pf->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-round"
                                    data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $pf->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.preguntas.destroy', $pf->id) }}"
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

                        @include('admin.preguntas-frecuentes.modals.edit')

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.preguntas-frecuentes.modals.create')

@endsection
