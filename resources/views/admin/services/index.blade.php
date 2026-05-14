@extends('admin.layouts.app')

@section('title', 'Gestión de Servicios')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Servicios</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.servicios.index') }}">Servicios</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a>Listado</a>
                </li>
            </ul>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Servicio
        </button>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id_service }}</td>
                            <td>{{ $service->nombre }}</td>
                            <td>
                                <span class="badge {{ $service->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $service->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>{{ $service->created_at ? $service->created_at->format('d/m/Y') : '-' }}</td>

                            <td>
                                <button class="btn btn-sm mt-2 btn-primary btn-border btn-round" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $service->id_service }}">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <form action="{{ route('admin.servicios.destroy', $service->id_service) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete" data-id="{{ $service->id_service }}" data-name="{{ $service->nombre }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        @include('admin.services.modals.edit')

                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

@include('admin.services.modals.create')

@push('scripts')
<script>   
var i = 1; 

window.addBeneficio = function() {
    let html = `
    <div class="row mb-2 animated fadeIn align-items-center">
        <div class="col-md-3">
            <input type="text" name="beneficios[${i}][titulo]" class="form-control" placeholder="Ingrese título del beneficio">
        </div>
        <div class="col-md-4">
            <input type="text" name="beneficios[${i}][descripcion]" class="form-control" placeholder="Ingrese descripción del beneficio">
        </div>
        <div class="col-md-4">
            <input type="text" name="beneficios[${i}][icono]" class="form-control" placeholder="Icono (fa fa-check)">
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>`;
    
    $('#beneficios-container').append(html);
    i++;
};

let p = 1;

window.addPlan = function() {
    let html = `
    <div class="card p-3 mb-3 border shadow-sm animated fadeIn">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-primary"></h6>
            <button type="button" class="btn btn-danger btn-xs" onclick="this.closest('.card').remove()">
                <i class="fa fa-trash"></i> Eliminar Plan
            </button>
        </div>

        <div class="row">
            <div class="col-md-8">
                <label class="small">Nombre del Plan</label>
                <input type="text" name="planes[${p}][nombre]" 
                    placeholder="Ej: Plan Pro, Membresía Anual..." 
                    class="form-control mb-2">
            </div>
            <div class="col-md-4">
                <label class="small">Precio</label>
                <input type="number" step="0.01" name="planes[${p}][precio]" 
                    class="form-control mb-2" placeholder="0.00">
            </div>
        </div>

        <label class="small">Descripción Corta</label>
        <textarea name="planes[${p}][descripcion]" class="form-control mb-2" rows="2" 
            placeholder="Resumen del plan..."></textarea>

        <div class="bg-light p-2 rounded mb-2">
            <div id="features-container-${p}" class="mb-2">
                <div class="input-group mb-1">
                    <input type="text" name="planes[${p}][features][]" 
                        class="form-control form-control-sm" placeholder="Ej: Soporte 24/7">
                    <button class="btn btn-outline-danger btn-sm" type="button" 
                        onclick="this.closest('.input-group').remove()">×</button>
                </div>
            </div>

            <button type="button" class="btn btn-outline-success btn-xs mt-1" 
                onclick="addFeature(${p})">
                <i class="fa fa-plus"></i> Añadir característica
            </button>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="planes[${p}][destacado]" 
                value="1" id="destacado${p}">
            <label class="form-check-label" for="destacado${p}">
                Marcar como Plan Destacado
            </label>
        </div>
    </div>`;

    $('#planes-container').append(html);
    p++;
};


window.addFeature = function(planIdx) {
    let featureHtml = `
    <div class="input-group mb-1 animated fadeIn">
        <input type="text" name="planes[${planIdx}][features][]" class="form-control form-control-sm" placeholder="Nueva característica">
        <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.closest('.input-group').remove()">×</button>
    </div>`;
    $(`#features-container-${planIdx}`).append(featureHtml);
};


window.planCounters = {};
window.benefitCounters = {};


/**
 * AGREGAR BENEFICIOS (EDIT)
 */
window.addBeneficioEdit = function(id_service) {

    if (!benefitCounters[id_service]) {
        benefitCounters[id_service] = $(`#beneficios-container-edit-${id_service} .row`).length;
    }

    let index = benefitCounters[id_service];

    let html = `
    <div class="row mb-2 align-items-center animated fadeIn">
        <div class="col-md-3">
            <input type="text" name="beneficios[${index}][titulo]" class="form-control" placeholder="Título">
        </div>
        <div class="col-md-4">
            <input type="text" name="beneficios[${index}][descripcion]" class="form-control" placeholder="Descripción">
        </div>
        <div class="col-md-4">
            <input type="text" name="beneficios[${index}][icono]" class="form-control" placeholder="fa fa-check">
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>`;

    $(`#beneficios-container-edit-${id_service}`).last().append(html);

    benefitCounters[id_service]++;
};

/**
 * AGREGAR PLANES (EDIT)
 */
window.addPlanEdit = function(id_service) {

    if (!planCounters[id_service]) {
        planCounters[id_service] = $(`#planes-container-edit-${id_service} .card`).length;
    }

    let index = planCounters[id_service];

    let html = `
    <div class="card p-3 mb-3 border shadow-sm animated fadeIn">
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold text-primary">Nuevo Plan</h6>
            <button type="button" class="btn btn-danger btn-xs" onclick="this.closest('.card').remove()">
                Eliminar Plan
            </button>
        </div>

        <div class="row">
            <div class="col-md-8">
                <label class="small">Nombre del Plan</label>
                <input type="text" name="planes[${index}][nombre]" class="form-control mb-2" placeholder="Ej: Plan Pro">
            </div>
            <div class="col-md-4">
                <label class="small">Precio</label>
                <input type="number" step="0.01" name="planes[${index}][precio]" class="form-control mb-2" placeholder="0.00">
            </div>
        </div>

        <label class="small">Descripción Corta</label>
        <textarea name="planes[${index}][descripcion]" class="form-control mb-2" rows="2"></textarea>

        <div class="bg-light p-2 rounded">
            <div id="features-container-edit-${id_service}-${index}">
                <div class="input-group mb-1">
                    <input type="text" name="planes[${index}][features][]" class="form-control form-control-sm" placeholder="Característica">
                    <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.closest('.input-group').remove()">×</button>
                </div>
            </div>

            <button type="button" class="btn btn-outline-success btn-xs mt-1"
                onclick="addFeatureEdit(${id_service}, ${index})">
                <i class="fa fa-plus"></i> Añadir característica
            </button>
        </div>

        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="planes[${index}][destacado]" value="1" id="destEditNew${id_service}_${index}">
            <label class="form-check-label" for="destEditNew${id_service}_${index}">
                Marcar como Plan Destacado
            </label>
        </div>

    </div>`;

    $(`#planes-container-edit-${id_service}`).last().append(html);
    planCounters[id_service]++;
};

/**
 * AGREGAR CARACTERÍSTICAS A UN PLAN (EDIT)
 */
window.addFeatureEdit = function(id_service, planIdx) {

    let html = `
    <div class="input-group mb-1 animated fadeIn">
        <input type="text" name="planes[${planIdx}][features][]" 
            class="form-control form-control-sm" 
            placeholder="Nueva característica">

        <button class="btn btn-outline-danger btn-sm" type="button" 
            onclick="this.closest('.input-group').remove()">×</button>
    </div>`;

    $(`#features-container-edit-${id_service}-${planIdx}`).append(html);
};

let editors = {};

// CREATE
ClassicEditor.create(document.querySelector('#editorCreateService')).then(editor => editors['create'] = editor);

// EDIT
@foreach($services as $service)
ClassicEditor.create(document.querySelector('#editorEditService{{ $service->id_service }}')).then(editor => editors['edit{{ $service->id_service }}'] = editor);
@endforeach

</script>

@endpush

@endsection