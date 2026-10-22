@extends('admin.layouts.app')

@section('title', 'Configuración de Empresa')

@section('content')
<div class="page-inner">

    <div class="page-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Empresa</h4>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    <form method="POST"
                        action="{{ $empresa ? route('admin.empresa.update', $empresa->id_empresa) : route('admin.empresa.store') }}"
                        enctype="multipart/form-data">

                        @csrf
                        @if($empresa)
                        @method('PUT')
                        @endif

                        <div class="row">

                            {{-- IZQUIERDA --}}
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Nombre <small class="text-danger">*</small></label>
                                    <input type="text" name="nombre" class="form-control"
                                        value="{{ $empresa->nombre ?? '' }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Nombre Comercial</label>
                                    <input type="text" name="nombre_comercial" class="form-control"
                                        value="{{ $empresa->nombre_comercial ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>RUC</label>
                                    <input type="text" name="ruc" class="form-control"
                                        value="{{ $empresa->ruc ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" name="telefono" class="form-control"
                                        value="{{ $empresa->telefono ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Correo</label>
                                    <input type="email" name="correo" class="form-control"
                                        value="{{ $empresa->correo ?? '' }}">
                                </div>

                            </div>

                            {{-- DERECHA --}}
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Dirección</label>
                                    <textarea name="direccion"
                                        class="form-control editor">{{ $empresa->direccion ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Ubicación de Google Maps</label>
                                    <textarea name="ubicacion" class="form-control" rows="3"
                                        placeholder="Pega la URL de inserción de Google Maps">{{ old('ubicacion', $empresa->ubicacion ?? '') }}</textarea>
                                    <small class="form-text text-muted">
                                        Usa la URL del iframe de Google Maps que comienza con https://www.google.com/maps/embed.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Descripción General</label>
                                    <textarea name="descripcion"
                                        class="form-control editor">{{ $empresa->descripcion ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Facebook</label>
                                    <input type="text" name="facebook" class="form-control"
                                        value="{{ $empresa->facebook ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>Instagram</label>
                                    <input type="text" name="instagram" class="form-control"
                                        value="{{ $empresa->instagram ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>TikTok</label>
                                    <input type="text" name="tiktok" class="form-control"
                                        value="{{ $empresa->tiktok ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control"
                                        value="{{ $empresa->whatsapp ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label>LinkedIn</label>
                                    <input type="text" name="linkedin" class="form-control"
                                        value="{{ $empresa->linkedin ?? '' }}">
                                </div>

                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3">Indicadores de Nosotros</h5>

                        @php
                            $indicadores = $empresa->empresa_indicadores ?: [
                                ['valor' => $empresa->indicador_1_valor ?? '+10', 'titulo' => $empresa->indicador_1_titulo ?? 'Años de experiencia'],
                                ['valor' => $empresa->indicador_2_valor ?? '100%', 'titulo' => $empresa->indicador_2_titulo ?? 'Compromiso profesional'],
                                ['valor' => $empresa->indicador_3_valor ?? '360°', 'titulo' => $empresa->indicador_3_titulo ?? 'Soluciones integrales'],
                                ['valor' => $empresa->indicador_4_valor ?? 'ISO', 'titulo' => $empresa->indicador_4_titulo ?? 'Estándares internacionales'],
                            ];
                        @endphp

                        <div class="row">
                            @foreach($indicadores as $index => $indicador)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Indicador {{ $index + 1 }} - Valor</label>
                                    <input type="text" name="indicadores[{{ $index }}][valor]" class="form-control"
                                        value="{{ $indicador['valor'] ?? '' }}" maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <input type="text" name="indicadores[{{ $index }}][titulo]" class="form-control"
                                        value="{{ $indicador['titulo'] ?? '' }}" maxlength="150">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>

                        <h5 class="mb-2">Beneficios de la Empresa</h5>
                        <p class="text-muted">Agrega hasta 4 beneficios. Cada uno puede incluir una imagen, título, descripción y enlace.</p>

                        @php
                            $beneficiosEmpresa = collect($empresa->empresa_beneficios ?? [])->take(4);
                            $beneficiosEmpresa = $beneficiosEmpresa->concat(
                                array_fill(0, max(0, 4 - $beneficiosEmpresa->count()), [])
                            );
                        @endphp

                        <div class="row">
                            @foreach($beneficiosEmpresa as $index => $beneficio)
                            <div class="col-lg-6">
                                <div class="card border mb-3">
                                    <div class="card-header">
                                        <strong>Beneficio {{ $index + 1 }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Título</label>
                                            <input type="text" name="beneficios[{{ $index }}][titulo]"
                                                class="form-control" maxlength="150"
                                                value="{{ $beneficio['titulo'] ?? '' }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <textarea name="beneficios[{{ $index }}][descripcion]"
                                                class="form-control" rows="3" maxlength="1000">{{ $beneficio['descripcion'] ?? '' }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Imagen</label>
                                            <input type="file" name="beneficios[{{ $index }}][imagen]"
                                                class="form-control" accept="image/*">
                                            @if(!empty($beneficio['imagen']))
                                            <div class="d-flex align-items-center gap-2 mt-2 empresa-image-removal-control">
                                                <img src="{{ asset($beneficio['imagen']) }}"
                                                    alt="Beneficio {{ $index + 1 }}"
                                                    class="img-fluid rounded empresa-beneficio-preview empresa-removable-image">
                                                <input type="checkbox" class="btn-check empresa-image-remove-checkbox"
                                                    id="eliminar-beneficio-imagen-{{ $index }}"
                                                    name="beneficios[{{ $index }}][eliminar_imagen]" value="1">
                                                <label class="btn btn-sm btn-outline-danger"
                                                    for="eliminar-beneficio-imagen-{{ $index }}" data-delete-label="Eliminar"
                                                    data-undo-label="Deshacer">
                                                    <i class="fa fa-trash"></i> <span>Eliminar</span>
                                                </label>
                                                <span class="empresa-image-removal-message" role="status" hidden>
                                                    Se eliminará al guardar
                                                </span>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Texto del botón (opcional)</label>
                                            <input type="text" name="beneficios[{{ $index }}][texto_boton]"
                                                class="form-control" maxlength="80"
                                                value="{{ $beneficio['texto_boton'] ?? '' }}">
                                        </div>

                                        <div class="form-group mb-0">
                                            <label>Enlace (opcional)</label>
                                            <input type="text" name="beneficios[{{ $index }}][enlace]"
                                                class="form-control" maxlength="2048"
                                                value="{{ $beneficio['enlace'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>

                        <h5 class="mb-3">Ventajas de la Empresa (máximo 4)</h5>

                        @php
                            $ventajasPredeterminadas = [
                                ['icono' => 'fas fa-shipping-fast', 'titulo' => 'Envíos Rápidos', 'descripcion' => 'Realizamos entregas ágiles y seguras para que recibas tus productos en el menor tiempo posible.'],
                                ['icono' => 'fas fa-shield-alt', 'titulo' => 'Compra 100% Segura', 'descripcion' => 'Protegemos cada transacción con métodos de pago confiables y seguridad avanzada.'],
                                ['icono' => 'fab fa-whatsapp', 'titulo' => 'Atención Personalizada', 'descripcion' => 'Nuestro equipo está listo para ayudarte antes, durante y después de tu compra.'],
                                ['icono' => 'fas fa-balance-scale', 'titulo' => 'Precio-beneficio', 'descripcion' => 'Contamos con productos a precio justo, seguros y eficaces, sin comprometer la calidad.'],
                            ];
                            $ventajas = collect($empresa->empresa_ventajas ?: $ventajasPredeterminadas)
                                ->take(4)
                                ->values();
                            while ($ventajas->count() < 4) {
                                $ventajas->push($ventajasPredeterminadas[$ventajas->count()]);
                            }
                        @endphp

                        <div class="row">
                            @foreach($ventajas as $index => $ventaja)
                            <div class="col-lg-3 col-md-6">
                                <div class="form-group">
                                    <label>Ícono {{ $index + 1 }}</label>
                                    <div class="input-group">
                                        <input type="text" name="ventajas[{{ $index }}][icono]" class="form-control"
                                            value="{{ $ventaja['icono'] ?? '' }}" placeholder="fa fa-star">
                                        <span class="input-group-text" style="cursor: pointer;"
                                            data-bs-toggle="modal" data-bs-target="#modalGuiaIconos"
                                            onclick="prepararIcono(this)" title="Ver guía de iconos">
                                            <i class="fa fa-th"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Título</label>
                                    <input type="text" name="ventajas[{{ $index }}][titulo]" class="form-control"
                                        value="{{ $ventaja['titulo'] ?? '' }}" maxlength="150">
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="ventajas[{{ $index }}][descripcion]" class="form-control" rows="4">{{ $ventaja['descripcion'] ?? '' }}</textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>

                        <h5 class="mb-3">Información Empresarial (Nosotros)</h5>

                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Descripción Empresarial</label>
                                    <textarea name="descripcion_empresarial" class="form-control editor"
                                        rows="4">{{ $empresa->descripcion_empresarial ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Misión</label>
                                    <textarea name="mision_empresarial" class="form-control editor"
                                        rows="3">{{ $empresa->mision_empresarial ?? '' }}</textarea>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Visión</label>
                                    <textarea name="vision_empresarial" class="form-control editor"
                                        rows="3">{{ $empresa->vision_empresarial ?? '' }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Valores</label>
                                    <textarea name="valores_empresariales" class="form-control editor"
                                        rows="3">{{ $empresa->valores_empresariales ?? '' }}</textarea>
                                </div>

                            </div>

                        </div>
                        <hr>

                        <h5 class="mb-3">Imágenes Empresariales (Nosotros)</h5>

                        <div class="row">

                            {{-- IMAGEN EMPRESARIAL --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Imagen Empresarial</label>
                                    <input type="file" name="imagen_empresarial" class="form-control">

                                    @if($empresa && $empresa->imagen_empresarial)
                                    <div class="d-flex align-items-center gap-2 mt-2 empresa-image-removal-control">
                                        <img src="{{ asset($empresa->imagen_empresarial) }}" width="160"
                                            class="rounded empresa-removable-image">
                                        <input type="checkbox" class="btn-check empresa-image-remove-checkbox" id="eliminar-imagen-empresarial"
                                            name="eliminar_imagen_empresarial" value="1">
                                        <label class="btn btn-sm btn-outline-danger" for="eliminar-imagen-empresarial"
                                            data-delete-label="Eliminar" data-undo-label="Deshacer">
                                            <i class="fa fa-trash"></i> <span>Eliminar</span>
                                        </label>
                                        <span class="empresa-image-removal-message" role="status" hidden>
                                            Se eliminará al guardar
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- PORTADA EMPRESARIAL --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Portada Empresarial (Hero Nosotros)</label>
                                    <input type="file" name="portada_empresarial" class="form-control">

                                    @if($empresa && $empresa->portada_empresarial)
                                    <div class="d-flex align-items-center gap-2 mt-2 empresa-image-removal-control">
                                        <img src="{{ asset($empresa->portada_empresarial) }}" width="160"
                                            class="rounded empresa-removable-image">
                                        <input type="checkbox" class="btn-check empresa-image-remove-checkbox" id="eliminar-portada-empresarial"
                                            name="eliminar_portada_empresarial" value="1">
                                        <label class="btn btn-sm btn-outline-danger"
                                            for="eliminar-portada-empresarial" data-delete-label="Eliminar"
                                            data-undo-label="Deshacer">
                                            <i class="fa fa-trash"></i> <span>Eliminar</span>
                                        </label>
                                        <span class="empresa-image-removal-message" role="status" hidden>
                                            Se eliminará al guardar
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <hr>

                        {{-- LOGOS --}}
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Logo Header</label>
                                    <input type="file" name="logo_header" class="form-control">
                                    @if($empresa && $empresa->logo_header)
                                    <div class="d-flex align-items-center gap-2 mt-2 empresa-image-removal-control">
                                        <img src="{{ asset($empresa->logo_header) }}" width="120"
                                            class="empresa-removable-image">
                                        <input type="checkbox" class="btn-check empresa-image-remove-checkbox" id="eliminar-logo-header"
                                            name="eliminar_logo_header" value="1">
                                        <label class="btn btn-sm btn-outline-danger" for="eliminar-logo-header"
                                            data-delete-label="Eliminar" data-undo-label="Deshacer">
                                            <i class="fa fa-trash"></i> <span>Eliminar</span>
                                        </label>
                                        <span class="empresa-image-removal-message" role="status" hidden>
                                            Se eliminará al guardar
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Logo Footer</label>
                                    <input type="file" name="logo_footer" class="form-control">
                                    @if($empresa && $empresa->logo_footer)
                                    <div class="d-flex align-items-center gap-2 mt-2 empresa-image-removal-control">
                                        <img src="{{ asset($empresa->logo_footer) }}" width="120"
                                            class="empresa-removable-image">
                                        <input type="checkbox" class="btn-check empresa-image-remove-checkbox" id="eliminar-logo-footer"
                                            name="eliminar_logo_footer" value="1">
                                        <label class="btn btn-sm btn-outline-danger" for="eliminar-logo-footer"
                                            data-delete-label="Eliminar" data-undo-label="Deshacer">
                                            <i class="fa fa-trash"></i> <span>Eliminar</span>
                                        </label>
                                        <span class="empresa-image-removal-message" role="status" hidden>
                                            Se eliminará al guardar
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Favicon</label>
                                    <input type="file" name="favicon" class="form-control">
                                    @if($empresa && $empresa->favicon)
                                    <div class="d-flex align-items-center gap-2 mt-2 empresa-image-removal-control">
                                        <img src="{{ asset($empresa->favicon) }}" width="50"
                                            class="empresa-removable-image">
                                        <input type="checkbox" class="btn-check empresa-image-remove-checkbox" id="eliminar-favicon"
                                            name="eliminar_favicon" value="1">
                                        <label class="btn btn-sm btn-outline-danger" for="eliminar-favicon"
                                            data-delete-label="Eliminar" data-undo-label="Deshacer">
                                            <i class="fa fa-trash"></i> <span>Eliminar</span>
                                        </label>
                                        <span class="empresa-image-removal-message" role="status" hidden>
                                            Se eliminará al guardar
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="text-center mt-4">
                            <button class="btn btn-success btn-round">
                                <i class="fa fa-save"></i>
                                {{ $empresa ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@include('admin.services.partials.guia-iconos')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.empresa-image-remove-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var control = checkbox.closest('.empresa-image-removal-control');
            var image = control.querySelector('.empresa-removable-image');
            var label = control.querySelector('label[for="' + checkbox.id + '"]');
            var labelText = label.querySelector('span');
            var message = control.querySelector('.empresa-image-removal-message');

            image.hidden = checkbox.checked;
            message.hidden = !checkbox.checked;
            labelText.textContent = checkbox.checked
                ? label.dataset.undoLabel
                : label.dataset.deleteLabel;
            label.classList.toggle('btn-outline-danger', !checkbox.checked);
            label.classList.toggle('btn-outline-secondary', checkbox.checked);
        });
    });
});
</script>
@endsection
