@extends('admin.layouts.app')

@section('title', 'Gestión de Banners')

@section('content')

<div class="page-inner">
    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Banners Principales</h4>

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
            <i class="fa fa-plus"></i> Nuevo Banner
        </button>

    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="basic-datatables">

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
                        @foreach($banners as $banner)
                        <tr>
                            <td>{{ $banner->id_banner }}</td>
                            <td>{{ $banner->titulo }}</td>
                            <td>{{ $banner->subtitulo }}</td>
                            <td>{{ $banner->orden }}</td>

                            <td>
                                <span class="badge {{ $banner->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $banner->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <img src="{{ asset($banner->imagen) }}" width="60">
                            </td>

                            <td>

                                <button class="btn btn-sm btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $banner->id_banner }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.banners.destroy', $banner->id_banner) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>

                        @include('admin.banners.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.banners.modals.create')

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll('.modal').forEach(modal => {

        modal.addEventListener('shown.bs.modal', function() {

            const tipo = modal.querySelector('.solo_imagen');

            function toggle() {
                let ocultar = tipo.value == 1;

                modal.querySelectorAll('.campo-texto').forEach(el => {
                    el.style.display = ocultar ? 'none' : 'block';
                });

                if (ocultar) {
                    modal.querySelectorAll('.campo-texto input, .campo-texto textarea').forEach(
                        el => {
                            el.value = '';
                        });
                }
            }

            tipo.addEventListener('change', toggle);
            toggle();

        });

    });

});
</script>

@endsection