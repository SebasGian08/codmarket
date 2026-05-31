@extends('admin.layouts.app')

@section('title', 'Configuración de Empresa')

@section('content')
<div class="page-inner">

    <div class="page-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Empresa</h4>
        </div>
    </div>

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
                                    <label>Nombre *</label>
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
                                    <label>WhatsApp</label>
                                    <input type="text" name="whatsapp" class="form-control"
                                        value="{{ $empresa->whatsapp ?? '' }}">
                                </div>

                            </div>

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
                                    <img src="{{ asset($empresa->imagen_empresarial) }}" width="160"
                                        class="mt-2 rounded">
                                    @endif
                                </div>
                            </div>

                            {{-- PORTADA EMPRESARIAL --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Portada Empresarial (Hero Nosotros)</label>
                                    <input type="file" name="portada_empresarial" class="form-control">

                                    @if($empresa && $empresa->portada_empresarial)
                                    <img src="{{ asset($empresa->portada_empresarial) }}" width="160"
                                        class="mt-2 rounded">
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
                                    <img src="{{ asset($empresa->logo_header) }}" width="120" class="mt-2">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Logo Footer</label>
                                    <input type="file" name="logo_footer" class="form-control">
                                    @if($empresa && $empresa->logo_footer)
                                    <img src="{{ asset($empresa->logo_footer) }}" width="120" class="mt-2">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Favicon</label>
                                    <input type="file" name="favicon" class="form-control">
                                    @if($empresa && $empresa->favicon)
                                    <img src="{{ asset($empresa->favicon) }}" width="50" class="mt-2">
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
@endsection