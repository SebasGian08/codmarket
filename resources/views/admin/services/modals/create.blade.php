<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-xl">

        <form method="POST" action="{{ route('admin.servicios.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <ul class="nav nav-tabs nav-line nav-color-secondary">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="pill" href="#tab-general">General</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab-beneficios">Beneficios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab-planes">Planes</a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">

                        <!-- GENERAL -->
                        <div class="tab-pane fade show active" id="tab-general">

                            <div class="row">

                                <!-- IZQUIERDA -->
                                <div class="col-md-6">

                                    <label>Nombre del Servicio</label>
                                    <input type="text" name="nombre" class="form-control mb-2"
                                        placeholder="Ingrese nombre del servicio">

                                    <label>Grupo o categoría</label>
                                    <input type="text" name="grupo" class="form-control mb-2"
                                        placeholder="Ej. SST, SIG, Medio Ambiente">

                                    <label>Descripción</label>
                                    <textarea name="descripcion" class="form-control mb-2 editor" rows="3"
                                        placeholder="Ingrese descripción del servicio"></textarea>

                                    <label>Descripción Portada</label>
                                    <input type="text" name="descripcion_portada" class="form-control mb-2"
                                        placeholder="Texto para portada">

                                    <label>Descripción Breve Portada</label>
                                    <input type="text" name="descripcion_breve_portada" class="form-control mb-2"
                                        placeholder="Resumen corto">

                                </div>

                                <!-- DERECHA -->
                                <div class="col-md-6">

                                    <label>Portada</label>
                                    <input type="file" name="portada" class="form-control mb-3">

                                    <label>Imagen Portada</label>
                                    <input type="file" name="imagen_portada" class="form-control mb-3">

                                    <label>Imagen Referencial</label>
                                    <input type="file" name="imagen_referencial" class="form-control mb-2">

                                </div>

                            </div>

                            <!-- CONTENIDO FULL WIDTH -->
                            <div class="mt-3">
                                <label>Contenido</label>
                                <textarea name="content" id="editor" class="form-control editor"></textarea>
                            </div>

                        </div>

                        <!-- BENEFICIOS -->
                        <div class="tab-pane fade" id="tab-beneficios">
                            <div class="servicio-beneficios-intro">
                                <div>
                                    <h6>Beneficios del servicio</h6>
                                    <p>Agrega título, descripción y un icono o imagen para cada beneficio.</p>
                                </div>
                                <button type="button" class="btn btn-primary btn-round btn-sm"
                                    onclick="addBeneficio()">
                                    <i class="fa fa-plus"></i> Agregar beneficio
                                </button>
                            </div>

                            <div id="beneficios-container">
                                <div class="servicio-beneficio-card">
                                    <div class="servicio-beneficio-card-header">
                                        <div>
                                            <span class="servicio-beneficio-kicker">Beneficio</span>
                                            <span class="servicio-beneficio-hint">Completa la información que se mostrará en la página.</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="this.closest('.servicio-beneficio-card').remove()"
                                            aria-label="Quitar beneficio">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>

                                    <div class="servicio-beneficio-card-body">
                                        <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Título</label>
                                        <input type="text" name="beneficios[0][titulo]" class="form-control"
                                            placeholder="Ej. Atención personalizada">
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <label class="form-label">Descripción</label>
                                        <textarea name="beneficios[0][descripcion]" class="form-control" rows="2"
                                            placeholder="Describe brevemente este beneficio"></textarea>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <label class="form-label">Icono</label>
                                        <div class="input-group">
                                            <input type="text" name="beneficios[0][icono]" class="form-control"
                                                placeholder="Ej. fa fa-check">
                                            <span class="input-group-text servicio-beneficio-iconos"
                                                data-bs-toggle="modal" data-bs-target="#modalGuiaIconos"
                                                onclick="prepararIcono(this)" title="Ver guía de iconos">
                                                <i class="fa fa-th"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-7">
                                        <label class="form-label">Imagen (opcional)</label>
                                        <input type="file" name="beneficios[0][imagen]" class="form-control"
                                            accept="image/jpeg,image/png,image/webp" aria-label="Imagen del beneficio">
                                        <small class="form-text text-muted">JPG, PNG o WebP. Si agregas una imagen, se mostrará en lugar del icono.</small>
                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PLANES -->
                        <div class="tab-pane fade" id="tab-planes">

                            <div id="planes-container">

                                <div class="card p-3 mb-3 border shadow-sm">

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold text-primary"></h6>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="this.closest('.card').remove()">Eliminar Plan</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="small">Nombre del Plan</label>
                                            <input type="text" name="planes[0][nombre]" class="form-control mb-2"
                                                placeholder="Plan Pro">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="small">Precio</label>
                                            <input type="number" step="0.01" name="planes[0][precio]"
                                                class="form-control mb-2" placeholder="0.00">
                                        </div>
                                    </div>

                                    <label class="small">Descripción Corta</label>
                                    <textarea name="planes[0][descripcion]" class="form-control mb-2 editor" rows="2"
                                        placeholder="Resumen del plan"></textarea>

                                    <div class="bg-light p-2 rounded">

                                        <div id="features-container-0">

                                            <div class="input-group mb-1">
                                                <input type="text" name="planes[0][features][]"
                                                    class="form-control form-control-sm" placeholder="Característica">
                                                <button class="btn btn-outline-danger btn-sm" type="button"
                                                    onclick="this.closest('.input-group').remove()">×</button>
                                            </div>

                                        </div>

                                        <button type="button" class="btn btn-outline-success btn-xs mt-1"
                                            onclick="addFeature(0)">
                                            <i class="fa fa-plus"></i> Añadir característica
                                        </button>

                                    </div>

                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="planes[0][destacado]"
                                            value="1" id="destacado0">

                                        <label class="form-check-label" for="destacado0">
                                            Marcar como Plan Destacado
                                        </label>
                                    </div>

                                </div>

                            </div>

                            <button type="button" class="btn btn-primary btn-border btn-round btn-sm"
                                onclick="addPlan()">
                                <i class="fa fa-plus"></i> Agregar Otro Plan
                            </button>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Guardar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
