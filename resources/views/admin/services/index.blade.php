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

                                    <button type="submit" class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete" data-id="{{ $service->id_service }}" data-name="{{ $service->nombre }}">
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
$(document).ready(function() {
    // Contadores correlativos globales para la sección de "CREAR NUEVO"
    var i = 1; 
    var p = 1;

    /* ==========================================================================
       1. FUNCIONES PARA CREAR UN NUEVO SERVICIO (VISTA CREAR / MODAL CREAR)
       ========================================================================== */

    window.addBeneficio = function() {
        let html = `
        <div class="row mb-2 animated fadeIn align-items-center">
            <div class="col-md-3"><input type="text" name="beneficios[${i}][titulo]" class="form-control" placeholder="Ingrese título del beneficio"></div>
            <div class="col-md-4"><input type="text" name="beneficios[${i}][descripcion]" class="form-control" placeholder="Ingrese descripción del beneficio"></div>
            <div class="col-md-4"><input type="text" name="beneficios[${i}][icono]" class="form-control" placeholder="Icono (fa fa-check)"></div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()"><i class="fa fa-times"></i></button>
            </div>
        </div>`;
        $('#beneficios-container').append(html);
        i++;
    };

    window.addPlan = function() {
        let html = `
        <div class="card p-3 mb-3 border shadow-sm animated fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold text-primary">Nuevo Plan</h6>
                <button type="button" class="btn btn-danger btn-xs" onclick="this.closest('.card').remove()"><i class="fa fa-trash"></i> Eliminar</button>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label class="small">Nombre del Plan</label>
                    <input type="text" name="planes[${p}][nombre]" class="form-control mb-2" placeholder="Ej: Plan Pro">
                </div>
                <div class="col-md-4">
                    <label class="small">Precio</label>
                    <input type="number" step="0.01" name="planes[${p}][precio]" class="form-control mb-2" placeholder="0.00">
                </div>
            </div>
            <label class="small">Descripción Corta</label>
            <textarea name="planes[${p}][descripcion]" class="form-control mb-2 editor" rows="2" placeholder="Resumen del plan..."></textarea>
            <div class="bg-light p-2 rounded mb-2">
                <div id="features-container-${p}" class="mb-2">
                    <div class="input-group mb-1">
                        <input type="text" name="planes[${p}][features][]" class="form-control form-control-sm" placeholder="Ej: Soporte 24/7">
                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.closest('.input-group').remove()">×</button>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-success btn-xs mt-1" onclick="addFeature(${p})"><i class="fa fa-plus"></i> Añadir característica</button>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="planes[${p}][destacado]" value="1" id="destacado${p}">
                <label class="form-check-label" for="destacado${p}">Marcar como Plan Destacado</label>
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


    /* ==========================================================================
       2. FUNCIONES PARA EDICIÓN DE SERVICIOS EXISTENTES (MODAL EDITAR)
       ========================================================================== */

    /**
     * Añadir Beneficio en Modal de Edición
     */
    window.addBeneficioEdit = function(id_service) {
        let uniqueIdx = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 100);

        let html = `
        <div class="row mb-2 align-items-center animated fadeIn">
            <div class="col-md-3">
                <input type="text" name="beneficios[${uniqueIdx}][titulo]" class="form-control" placeholder="Título">
            </div>
            <div class="col-md-4">
                <input type="text" name="beneficios[${uniqueIdx}][descripcion]" class="form-control" placeholder="Descripción">
            </div>
            <div class="col-md-4">
                <input type="text" name="beneficios[${uniqueIdx}][icono]" class="form-control" placeholder="fa fa-check">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>`;

        $(`#beneficios-container-edit-${id_service}`).append(html);
    };

    /**
     * Añadir Plan Completo en Modal de Edición
     */
    window.addPlanEdit = function(id_service) {
        let uniquePlanIdx = 'new_plan_' + Date.now() + '_' + Math.floor(Math.random() * 100);

        let html = `
        <div class="card p-3 mb-3 border shadow-sm animated fadeIn">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold text-primary">Nuevo Plan</h6>
                <button type="button" class="btn btn-danger btn-xs" onclick="this.closest('.card').remove()">
                    <i class="fa fa-trash"></i> Eliminar Plan
                </button>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <label class="small">Nombre del Plan</label>
                    <input type="text" name="planes[${uniquePlanIdx}][nombre]" class="form-control mb-2" placeholder="Ej: Plan Pro" required>
                </div>
                <div class="col-md-4">
                    <label class="small">Precio</label>
                    <input type="number" step="0.01" name="planes[${uniquePlanIdx}][precio]" class="form-control mb-2" placeholder="0.00" required>
                </div>
            </div>

            <label class="small">Descripción Corta</label>
            <textarea name="planes[${uniquePlanIdx}][descripcion]" class="form-control mb-2" rows="2" placeholder="Resumen..."></textarea>

            <div class="bg-light p-2 rounded">
                <div id="features-container-edit-${id_service}-${uniquePlanIdx}">
                    <div class="input-group mb-1">
                        <input type="text" name="planes[${uniquePlanIdx}][features][]" class="form-control form-control-sm editor" placeholder="Característica">
                        <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.closest('.input-group').remove()">×</button>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-success btn-xs mt-1"
                    onclick="addFeatureEdit(${id_service}, '${uniquePlanIdx}')">
                    <i class="fa fa-plus"></i> Añadir característica
                </button>
            </div>

            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="planes[${uniquePlanIdx}][destacado]" value="1" id="destEditNew${id_service}_${uniquePlanIdx}">
                <label class="form-check-label" for="destEditNew${id_service}_${uniquePlanIdx}">
                    Marcar como Plan Destacado
                </label>
            </div>
        </div>`;

        $(`#planes-container-edit-${id_service}`).append(html);
    };

    /**
     * Añadir sub-características (features) a un plan dentro del Modal de Edición
     */
    window.addFeatureEdit = function(id_service, planIdx) {
        let html = `
        <div class="input-group mb-1 animated fadeIn">
            <input type="text" name="features_nuevas[${planIdx}][]" class="form-control form-control-sm editor" placeholder="Nueva característica">
            <button class="btn btn-outline-danger btn-sm" type="button" onclick="this.closest('.input-group').remove()">×</button>
        </div>`;

        $(`#features-container-edit-${id_service}-${planIdx}`).append(html);
    };


    /* ==========================================================================
       3. SEGURO INTERCEPTOR REFORZADO PARA FORMULARIOS EN ESPAÑOL (SERVICIOS)
       ========================================================================== */
    
    $(document).on('submit', 'form', function(e) {
        let $form = $(this);
        let actionAttr = $form.attr('action') || '';

        // Se ejecuta si la ruta contiene 'servicios' o 'services'
        if (actionAttr.includes('servicios') || actionAttr.includes('services')) {
            let $modal = $form.closest('.modal');

            if ($modal.length > 0) {
                $modal.find('input[name^="beneficios"], input[name^="planes"], textarea[name^="planes"], input[name^="features_nuevas"]').each(function() {
                    if (!$.contains($form[0], this)) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: $(this).attr('name'),
                            value: $(this).val()
                        }).appendTo($form);
                    }
                });
            }
        }
    });
});
</script>
@endpush

@endsection