@extends('admin.layouts.app')

@section('title', 'Reglas de Descuento')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            @include('admin.partials.page_heading', [
                'icon' => 'fas fa-sliders-h',
                'title' => 'Reglas de Descuento',
                'description' => 'Configura las condiciones que determinan los descuentos automáticos.',
            ])
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateRegla">
            <i class="fa fa-plus"></i> Nueva Regla de Descuento
        </button>

    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            @include('admin.reglas-descuento.partials.table', ['dataTableId' => 'tabla-reglas-descuento'])
        </div>
    </div>

</div>

@endsection
