@extends('layouts.appweb')

@section('title', 'Libro de Reclamaciones')

@section('content')
<section class="reclamaciones-page">
    <div class="container">
        <nav class="reclamaciones-breadcrumb" aria-label="breadcrumb">
            <a href="{{ route('home') }}" aria-label="Inicio"><i class="bi bi-house-door-fill"></i></a>
            <span aria-hidden="true">›</span>
            <span aria-current="page">Libro de Reclamaciones</span>
        </nav>

        <header class="reclamaciones-heading">
            <span class="reclamaciones-eyebrow">Atención al consumidor</span>
            <h1>Libro de Reclamaciones</h1>
            <p>Registra tu reclamo o queja. Los campos marcados con <strong>*</strong> son obligatorios.</p>
        </header>

        @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <p class="mb-1">No se pudo enviar el formulario. Revisa estos datos:</p>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('reclamaciones.store') }}" class="reclamaciones-form">
            @csrf

            <section class="reclamaciones-form-section">
                <h2>Datos del consumidor</h2>
                <div class="reclamaciones-fields">
                    <div class="reclamaciones-field">
                        <label for="reclamacion-documento-tipo">Tipo de documento <span>*</span></label>
                        <select id="reclamacion-documento-tipo" name="id_documento_tipo" required>
                            <option value="">Selecciona un tipo</option>
                            @foreach($documentosTipos as $tipo)
                            <option value="{{ $tipo->id_documento_tipo }}"
                                {{ old('id_documento_tipo') == $tipo->id_documento_tipo ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="reclamaciones-field">
                        <label for="reclamacion-documento-numero">Número de documento <span>*</span></label>
                        <input id="reclamacion-documento-numero" name="numero_documento" type="text"
                            value="{{ old('numero_documento') }}" maxlength="20" required>
                    </div>

                    <div class="reclamaciones-field">
                        <label for="reclamacion-email">Correo electrónico <span>*</span></label>
                        <input id="reclamacion-email" name="correo_electronico" type="email"
                            value="{{ old('correo_electronico') }}" maxlength="150" autocomplete="email" required>
                    </div>

                    <div class="reclamaciones-field">
                        <label for="reclamacion-celular">Número celular <span>*</span></label>
                        <input id="reclamacion-celular" name="numero_celular" type="tel"
                            value="{{ old('numero_celular') }}" maxlength="20" inputmode="numeric"
                            pattern="[0-9]+" autocomplete="tel" required>
                    </div>
                </div>
            </section>

            <section class="reclamaciones-form-section">
                <h2>Información del bien contratado</h2>
                <div class="reclamaciones-fields">
                    <fieldset class="reclamaciones-field reclamaciones-field-full reclamaciones-choice-field">
                        <legend>Elija la clase <span>*</span></legend>
                        <div class="reclamaciones-type-choices">
                            <label class="reclamaciones-type-choice">
                                <input type="radio" name="tipo_bien" value="PRODUCTO" required
                                    {{ old('tipo_bien', 'PRODUCTO') === 'PRODUCTO' ? 'checked' : '' }}>
                                <span>Producto</span>
                            </label>
                            <label class="reclamaciones-type-choice">
                                <input type="radio" name="tipo_bien" value="SERVICIO" required
                                    {{ old('tipo_bien') === 'SERVICIO' ? 'checked' : '' }}>
                                <span>Servicio</span>
                            </label>
                        </div>
                    </fieldset>

                    <div class="reclamaciones-field">
                        <label for="reclamacion-monto">Monto reclamado <span>*</span></label>
                        <input id="reclamacion-monto" name="monto_reclamado" type="number" min="0"
                            max="9999999999.99" step="0.01" value="{{ old('monto_reclamado') }}" required>
                    </div>

                    <div class="reclamaciones-field reclamaciones-field-full">
                        <label for="reclamacion-detalle-bien">Detalle <span>*</span></label>
                        <textarea id="reclamacion-detalle-bien" name="detalle_bien" rows="3"
                            maxlength="10000" required>{{ old('detalle_bien') }}</textarea>
                    </div>
                </div>
            </section>

            <section class="reclamaciones-form-section">
                <h2>Detalle de la reclamación</h2>
                <div class="reclamaciones-fields">
                    <div class="reclamaciones-field">
                        <label for="reclamacion-tipo">Tipo <span>*</span></label>
                        <select id="reclamacion-tipo" name="id_reclamacion_tipo" required>
                            <option value="">Selecciona una opción</option>
                            @foreach($reclamacionesTipos as $tipo)
                            <option value="{{ $tipo->id_reclamacion_tipo }}"
                                {{ old('id_reclamacion_tipo') == $tipo->id_reclamacion_tipo ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="reclamaciones-field reclamaciones-field-full">
                        <label for="reclamacion-detalle">Detalle del reclamo o queja <span>*</span></label>
                        <textarea id="reclamacion-detalle" name="detalle_reclamacion" rows="5"
                            maxlength="10000" required>{{ old('detalle_reclamacion') }}</textarea>
                    </div>

                    <div class="reclamaciones-field reclamaciones-field-full">
                        <label for="reclamacion-pedido">Pedido del consumidor <span>*</span></label>
                        <textarea id="reclamacion-pedido" name="pedido_consumidor" rows="4"
                            maxlength="10000" required>{{ old('pedido_consumidor') }}</textarea>
                    </div>
                </div>
            </section>

            <div class="honeypot" aria-hidden="true">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            @if(config('services.turnstile.site_key'))
            <div class="reclamaciones-captcha">
                <div class="cf-turnstile"
                    data-sitekey="{{ config('services.turnstile.site_key') }}"
                    data-theme="auto"
                    data-size="normal">
                </div>
            </div>
            @endif

            <label class="reclamaciones-consent">
                <input type="checkbox" name="acepta_datos" value="1" required
                    {{ old('acepta_datos') ? 'checked' : '' }}>
                <span>Declaro que los datos proporcionados son verdaderos y autorizo su tratamiento para atender esta reclamación. <a href="{{ url('/politica-privacidad') }}">Ver política de privacidad</a> <span>*</span></span>
            </label>

            <button type="submit" class="reclamaciones-submit">Enviar reclamación</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (function () {
        const celular = document.getElementById('reclamacion-celular');
        if (!celular) return;

        celular.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 20);
        });
    })();
</script>
@endpush
