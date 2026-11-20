<section id="contacto" class="contact-option3-section">
    @php
        $whatsappNumero = preg_replace('/[^0-9]/', '', $empresa->whatsapp ?? '');
        $whatsappMensaje = urlencode('Hola, vengo de la web. Quisiera solicitar más información.');
        $telefonoContacto = preg_replace('/[^0-9+]/', '', $empresa->telefono ?? '');
        $tipoDocumentoPersona = null;
        foreach ($documentosTipos ?? [] as $tipoDocumento) {
            if (stripos((string) ($tipoDocumento->codigo ?: $tipoDocumento->nombre), 'dni') !== false) {
                $tipoDocumentoPersona = $tipoDocumento;
                break;
            }
        }
        if (!$tipoDocumentoPersona) {
            $tipoDocumentoPersona = reset($documentosTipos ?? []);
        }

    @endphp

    <div class="container">
        <div class="contact-option3-grid">
            <div class="contact-option3-form-column">
                <div class="contact-option3-heading">
                    <nav class="contact-option3-breadcrumb" aria-label="breadcrumb">
                        <a href="{{ route('home') }}"><i class="bi bi-house-door-fill"></i></a>
                        <span>›</span>
                        <span>Contáctanos</span>
                    </nav>
                    <h2>¿Tienes alguna duda o consulta?</h2>
                    <p>Comunicate con nosotros</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="contact-option3-form">
                    @csrf
                    <input type="hidden" name="tipo_consulta" value="general">
                    @if($tipoDocumentoPersona)
                    <input type="hidden" name="id_documento_tipo" value="{{ $tipoDocumentoPersona->id_documento_tipo }}">
                    @endif
                    <input type="hidden" name="apellidos" value="Consulta">

                    <div class="contact-option3-field">
                        <label for="option3-nombre">Nombres o Razón Social<span class="contact-option3-required">*</span></label>
                        <input id="option3-nombre" type="text" name="nombre"
                            value="{{ old('nombre') }}" maxlength="100" placeholder="Nombres o Razón Social" required autofocus>
                    </div>

                    <div class="contact-option3-field">
                        <label for="option3-email">Correo electrónico<span class="contact-option3-required">*</span></label>
                        <input id="option3-email" type="email" name="email"
                            value="{{ old('email') }}" maxlength="120" placeholder="Correo electrónico" required>
                    </div>

                    <div class="contact-option3-field">
                        <label for="option3-telefono">Teléfono<span class="contact-option3-required">*</span></label>
                        <input id="option3-telefono" type="tel" name="telefono"
                            value="{{ old('telefono') }}" maxlength="9" pattern="[0-9]{9}"
                            inputmode="numeric" placeholder="Teléfono" required>
                    </div>

                    <div class="contact-option3-field">
                        <label for="option3-mensaje">Mensaje</label>
                        <textarea id="option3-mensaje" name="message" rows="4" maxlength="1000"
                            placeholder="Escribe tu mensaje aquí">{{ old('message') }}</textarea>
                    </div>

                    <p class="contact-option3-required-note">
                        <span class="contact-option3-required">*</span> Campos obligatorios
                    </p>

                    <label class="contact-option3-consent">
                        <input type="checkbox" name="acepta_privacidad" value="1" required>
                        <span>He leído y aceptado la política de privacidad y cookies</span>
                    </label>

                    <div class="honeypot">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    @if(config('services.turnstile.site_key'))
                    <div class="cf-turnstile"
                        data-sitekey="{{ config('services.turnstile.site_key') }}"
                        data-theme="auto"
                        data-size="normal">
                    </div>
                    @endif

                    <button type="submit" class="contact-option3-submit">Solicitar cotización</button>
                </form>
            </div>

            <div class="contact-option3-info-column">
                <h2>Más información</h2>

                @if($empresa->direccion ?? false)
                <div class="contact-option3-detail">
                    <i class="bi bi-geo-alt-fill"></i>
                    <div>
                        <strong>Ubícanos en</strong>
                        <span>{!! limpiarTextoEditor($empresa->direccion) !!}</span>
                    </div>
                </div>
                @endif

                @if($empresa->correo ?? false)
                <div class="contact-option3-detail">
                    <i class="bi bi-envelope-fill"></i>
                    <div>
                        <strong>Escríbenos al correo</strong>
                        <a href="mailto:{{ $empresa->correo }}">{{ $empresa->correo }}</a>
                    </div>
                </div>
                @endif

                <div class="contact-option3-detail">
                    <i class="bi bi-clock-fill"></i>
                    <div>
                        <strong>Horario de atención al cliente</strong>
                        <span>De lunes a Viernes: 09:00 a 17:30h</span>
                    </div>
                </div>

                <div class="contact-option3-cards">
                    @if($whatsappNumero)
                    <article class="contact-option3-card">
                        <span>Escríbenos al WhatsApp</span>
                        <p>Consulta por precios o dudas sobre nuestros productos.</p>
                        <a href="https://wa.me/{{ $whatsappNumero }}?text={{ $whatsappMensaje }}"
                            target="_blank" rel="noopener noreferrer">contáctanos</a>
                    </article>
                    @endif

                    @if($telefonoContacto)
                    <article class="contact-option3-card">
                        <span>Soporte técnico</span>
                        <p>Especialistas en instalación, verificación, mantenimiento y atención en campo o remoto.</p>
                        <a href="tel:{{ $telefonoContacto }}">contáctanos</a>
                    </article>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($empresa->ubicacion ?? false)
    <div class="contact-option3-map">
        <iframe
            src="{{ $empresa->ubicacion }}"
            title="Ubicación de {{ $empresa->nombre ?? 'la empresa' }}"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen>
        </iframe>
    </div>
    @endif
</section>

<script>
    (function () {
        const telefono = document.getElementById('option3-telefono');
        if (!telefono) return;

        telefono.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });
    })();
</script>
