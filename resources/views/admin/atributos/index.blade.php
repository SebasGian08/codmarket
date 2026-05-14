@extends('admin.layouts.app')

@section('title', 'Atributos')

@section('content')

<div class="page-inner">

    {{-- HEADER --}}
   <!--  <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Gestión de Atributos</h4>
            <small class="text-muted">Administra colores, tallas u otras variantes</small>
        </div>

        <form action="{{ route('admin.atributos.store') }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="nombre" class="form-control" placeholder="Nuevo atributo (Ej: Color)" required>
            <button class="btn btn-primary px-3">
                + Agregar
            </button>
        </form>
    </div> -->

    <div class="page-header d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Atributos</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Mantenimiento</li>
            </ul>
        </div>

         <form action="{{ route('admin.atributos.store') }}" method="POST" class="d-flex gap-2">
            @csrf
            <input type="text" name="nombre" class="form-control" placeholder="Nuevo atributo (Ej: Color)" required>
            <button class="btn btn-primary px-3">
                Agregar
            </button>
        </form>

    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- GRID --}}
    <div class="row g-3">

        @forelse($atributos as $attr)
        <div class="col-md-6 col-lg-4">

            <div class="card shadow-sm border-0 h-100">

                {{-- HEADER CARD --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong class="text-dark">{{ $attr->nombre }}</strong>

                    <form action="{{ route('admin.atributos.destroy', $attr->id_atributo) }}" method="POST"
                        onsubmit="return confirm('¿Eliminar atributo?')">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-danger">
                            Eliminar
                        </button>
                    </form>
                </div>

                <div class="card-body">

                    {{-- VALORES --}}
                    <div class="mb-3">
                        <label class="form-label text-muted">Valores</label>

                        @if($attr->valores->count())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($attr->valores as $val)
                            <form action="{{ route('admin.atributos_valores.destroy', $val->id_valor) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-light border d-flex align-items-center gap-1">
                                    {{ $val->valor }}
                                    <span class="text-danger">×</span>
                                </button>
                            </form>
                            @endforeach
                        </div>
                        @else
                        <small class="text-muted">Sin valores registrados</small>
                        @endif
                    </div>

                    {{-- AGREGAR VALOR --}}
                    <form action="{{ route('admin.atributos_valores.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id_atributo" value="{{ $attr->id_atributo }}">

                        <div class="input-group">
                            <input type="text" name="valor" class="form-control" placeholder="Nuevo valor" required>

                            <button class="btn btn-success">
                                Agregar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                No hay atributos registrados aún
            </div>
        </div>
        @endforelse

    </div>

</div>

@endsection