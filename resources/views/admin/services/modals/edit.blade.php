<div class="modal fade" id="edit{{ $service->id_service }}">
    <div class="modal-dialog modal-xl">
        <form action="{{ route('admin.servicios.update', $service->id_service) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <ul class="nav nav-tabs nav-line nav-color-secondary">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="pill"
                                href="#tab-general-edit-{{ $service->id_service }}">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill"
                                href="#tab-beneficios-edit-{{ $service->id_service }}">Beneficios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill"
                                href="#tab-planes-edit-{{ $service->id_service }}">Planes</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="tab-general-edit-{{ $service->id_service }}">

                            <div class="row">

                                <!-- IZQUIERDA -->
                                <div class="col-md-6">

                                    <label>Nombre del Servicio</label>
                                    <input type="text" name="nombre" value="{{ $service->nombre }}"
                                        class="form-control mb-2">

                                    <label>Grupo o categoría</label>
                                    <input type="text" name="grupo" value="{{ $service->grupo }}"
                                        class="form-control mb-2" placeholder="Ej. SST, SIG, Medio Ambiente">

                                    <label>Orden de visualización</label>
                                    <input type="number" name="orden" value="{{ $service->orden }}"
                                        class="form-control mb-2" min="1" required>

                                    <label>Descripción</label>
                                    <textarea name="descripcion"
                                        class="form-control mb-2 editor">{{ $service->descripcion }}</textarea>

                                    <label>Descripción Portada</label>
                                    <input type="text" name="descripcion_portada"
                                        value="{{ $service->descripcion_portada }}" class="form-control mb-2">

                                    <label>Descripción Breve Portada</label>
                                    <input type="text" name="descripcion_breve_portada"
                                        value="{{ $service->descripcion_breve_portada }}" class="form-control mb-2">

                                </div>

                                <div class="col-md-6">

                                    <label>Portada</label>
                                    @if($service->portada)
                                    <div class="mb-2 text-center">
                                        {{-- Eliminado 'storage/' --}}
                                        <img src="{{ asset($service->portada) }}" class="img-fluid rounded shadow"
                                            style="max-height:120px;">
                                        <div class="small text-muted">Actual</div>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input"
                                            id="eliminar-portada-{{ $service->id_service }}" name="eliminar_portada"
                                            value="1">
                                        <label class="form-check-label"
                                            for="eliminar-portada-{{ $service->id_service }}">Eliminar imagen actual</label>
                                    </div>
                                    @endif
                                    <input type="file" name="portada" class="form-control mb-3">

                                    <label>Imagen Portada</label>
                                    @if($service->imagen_portada)
                                    <div class="mb-2 text-center">
                                        {{-- Eliminado 'storage/' --}}
                                        <img src="{{ asset($service->imagen_portada) }}"
                                            class="img-fluid rounded shadow" style="max-height:120px;">
                                        <div class="small text-muted">Actual</div>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input"
                                            id="eliminar-imagen-portada-{{ $service->id_service }}"
                                            name="eliminar_imagen_portada" value="1">
                                        <label class="form-check-label"
                                            for="eliminar-imagen-portada-{{ $service->id_service }}">Eliminar imagen actual</label>
                                    </div>
                                    @endif
                                    <input type="file" name="imagen_portada" class="form-control mb-3">

                                    <label>Imagen Referencial</label>
                                    @if($service->imagen_referencial)
                                    <div class="mb-2 text-center">
                                        {{-- Eliminado 'storage/' --}}
                                        <img src="{{ asset($service->imagen_referencial) }}"
                                            class="img-fluid rounded shadow" style="max-height:120px;">
                                        <div class="small text-muted">Actual</div>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input"
                                            id="eliminar-imagen-referencial-{{ $service->id_service }}"
                                            name="eliminar_imagen_referencial" value="1">
                                        <label class="form-check-label"
                                            for="eliminar-imagen-referencial-{{ $service->id_service }}">Eliminar imagen actual</label>
                                    </div>
                                    @endif
                                    <input type="file" name="imagen_referencial" class="form-control mb-2">

                                </div>

                            </div>

                            <!-- CONTENIDO FULL WIDTH -->
                            <div class="mt-3">
                                <label>Contenido</label>
                                <textarea name="content" class="form-control editor">{{ $service->content }}</textarea>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="tab-beneficios-edit-{{ $service->id_service }}">
                            <div id="beneficios-container-edit-{{ $service->id_service }}">
                                @foreach($service->benefits as $index => $ben)
                                <div class="card p-3 mb-3 border shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-primary">Beneficio {{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="this.closest('.card').remove()">
                                            <i class="fa fa-trash"></i> Eliminar Beneficio
                                        </button>
                                    </div>

                                    <input type="hidden" name="beneficios[{{ $index }}][id]" value="{{ $ben->id }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="small">Título del Beneficio</label>
                                            <input type="text" name="beneficios[{{ $index }}][titulo]"
                                                value="{{ $ben->titulo }}" class="form-control mb-2"
                                                placeholder="Ej: Atención personalizada">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="small">Descripción</label>
                                            <textarea name="beneficios[{{ $index }}][descripcion]"
                                                class="form-control mb-2" rows="2"
                                                placeholder="Describe brevemente el beneficio">{{ $ben->descripcion }}</textarea>
                                        </div>
                                    </div>

                                    <label class="small">Detalles</label>
                                    <div id="beneficio-detalles-edit-{{ $service->id_service }}-{{ $index }}"
                                        class="servicio-beneficio-detalles mb-2"
                                        data-input-name="beneficios[{{ $index }}][detalles]">
                                        @forelse($ben->details as $detalleIndex => $detalle)
                                        <div class="input-group mb-1">
                                            <input type="hidden"
                                                name="beneficios[{{ $index }}][detalles][{{ $detalleIndex }}][id]"
                                                value="{{ $detalle->id }}">
                                            <input type="text"
                                                name="beneficios[{{ $index }}][detalles][{{ $detalleIndex }}][descripcion]"
                                                value="{{ $detalle->descripcion }}"
                                                class="form-control" maxlength="500">
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="this.closest('.input-group').remove()"
                                                aria-label="Eliminar detalle">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        @empty
                                        <div class="input-group mb-1">
                                            <input type="text" name="beneficios[{{ $index }}][detalles][0][descripcion]"
                                                class="form-control" maxlength="500"
                                                placeholder="Añade un detalle del beneficio">
                                            <button type="button" class="btn btn-outline-danger"
                                                onclick="this.closest('.input-group').remove()"
                                                aria-label="Eliminar detalle">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        @endforelse
                                    </div>
                                    <button type="button" class="btn btn-outline-success btn-xs mb-3"
                                        onclick="addDetalleBeneficio('beneficio-detalles-edit-{{ $service->id_service }}-{{ $index }}')">
                                        <i class="fa fa-plus"></i> Añadir detalle
                                    </button>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="small">Icono</label>
                                            <div class="input-group mb-2">
                                                <input type="text" name="beneficios[{{ $index }}][icono]"
                                                    value="{{ $ben->icono }}" class="form-control"
                                                    placeholder="Ej: fa fa-check">
                                                <span class="input-group-text servicio-beneficio-iconos"
                                                    data-bs-toggle="modal" data-bs-target="#modalGuiaIconos"
                                                    onclick="prepararIcono(this)" title="Ver guía de iconos">
                                                    <i class="fa fa-th"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="small">Imagen (opcional)</label>
                                            @if($ben->imagen)
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <img src="{{ asset($ben->imagen) }}" alt="Imagen de {{ $ben->titulo }}"
                                                    class="img-thumbnail"
                                                    style="width:52px;height:52px;object-fit:cover;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="eliminar-beneficio-imagen-{{ $ben->id }}"
                                                        name="beneficios[{{ $index }}][eliminar_imagen]" value="1">
                                                    <label class="form-check-label small"
                                                        for="eliminar-beneficio-imagen-{{ $ben->id }}">Eliminar imagen actual</label>
                                                </div>
                                            </div>
                                            @endif
                                            <input type="file" name="beneficios[{{ $index }}][imagen]"
                                                class="form-control mb-2" accept="image/jpeg,image/png,image/webp"
                                                aria-label="Imagen de {{ $ben->titulo }}">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-primary btn-border btn-round btn-sm"
                                onclick="addBeneficioEdit({{ $service->id_service }})">
                                <i class="fa fa-plus"></i> Agregar Beneficio
                            </button>
                        </div>

                        <div class="tab-pane fade" id="tab-planes-edit-{{ $service->id_service }}">
                            <div id="planes-container-edit-{{ $service->id_service }}">
                                @foreach($service->plans as $pIdx => $plan)
                                <div class="card p-3 mb-3 border shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-primary"></h6>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="this.closest('.card').remove()">Eliminar Plan</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="small">Nombre del Plan</label>
                                            <input type="text" name="planes[{{ $pIdx }}][nombre]"
                                                value="{{ $plan->nombre }}" class="form-control mb-2">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small">Precio</label>
                                            <input type="number" step="0.01" name="planes[{{ $pIdx }}][precio]"
                                                value="{{ $plan->precio }}" class="form-control mb-2">
                                        </div>
                                    </div>

                                    <label class="small">Descripción Corta</label>
                                    <textarea name="planes[{{ $pIdx }}][descripcion]" class="form-control mb-2 editor"
                                        rows="2">{{ $plan->descripcion }}</textarea>

                                    <div class="bg-light p-2 rounded">
                                        <div id="features-container-edit-{{ $service->id_service }}-{{ $pIdx }}">
                                            @foreach($plan->features as $feat)
                                            <div class="input-group mb-1">
                                                <input type="text" name="planes[{{ $pIdx }}][features][]"
                                                    value="{{ $feat->descripcion }}"
                                                    class="form-control form-control-sm">
                                                <button class="btn btn-outline-danger btn-sm" type="button"
                                                    onclick="this.closest('.input-group').remove()">×</button>
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-xs mt-1"
                                            onclick="addFeatureEdit({{ $service->id_service }}, {{ $pIdx }})">
                                            <i class="fa fa-plus"></i> Añadir característica
                                        </button>
                                    </div>

                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox"
                                            name="planes[{{ $pIdx }}][destacado]" value="1"
                                            id="destEdit{{ $plan->id_plan }}" {{ $plan->destacado ? 'checked' : '' }}>
                                        <label class="form-check-label" for="destEdit{{ $plan->id_plan }}">Marcar como
                                            Plan Destacado</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-primary btn-border btn-round btn-sm"
                                onclick="addPlanEdit({{ $service->id_service }})">
                                <i class="fa fa-plus"></i> Agregar Otro Plan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
