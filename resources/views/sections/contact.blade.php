@if(($config['contacto_tipo_formulario'] ?? 'opcion_1') === 'opcion_2')
    @include('sections.contact-brand')
@else
<section id="contacto" class="contact-premium">

    @php
    $wspTelefono = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');
    $wspMensaje = urlencode('Hola, vengo de la web! Quisiera pedir más información.');
    @endphp


    <div class="contact-bg"></div>

    <div class="auto-container">

        <div class="contact-grid">

            <div class="contact-info">

                <span class="contact-eyebrow">
                    <i class="bi bi-chat-dots-fill"></i>
                    Hablemos de tu proyecto
                </span>

                <h2>
                    ¿Tienes una idea?
                    <span>Hagámoslo realidad.</span>
                </h2>

                <p class="contact-description">
                    Coméntanos lo que necesitas y nuestro equipo se pondrá
                    en contacto contigo para brindarte una solución
                    personalizada.
                </p>

                <div class="contact-benefits">

                    <div class="contact-benefit">
                        <div class="contact-benefit-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>

                        <div>
                            <strong>Respuesta rápida</strong>
                            <span>Te contactaremos lo antes posible.</span>
                        </div>
                    </div>

                    <div class="contact-benefit">
                        <div class="contact-benefit-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                        <div>
                            <strong>Atención personalizada</strong>
                            <span>Analizamos tus necesidades y objetivos.</span>
                        </div>
                    </div>

                    <div class="contact-benefit">
                        <div class="contact-benefit-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>

                        <div>
                            <strong>Información segura</strong>
                            <span>Tus datos serán tratados de forma confidencial.</span>
                        </div>
                    </div>

                </div>

                {{-- WhatsApp --}}
                <div class="contact-whatsapp">

                    <div class="whatsapp-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>

                    <div class="whatsapp-content">
                        <span>¿Prefieres hablar directamente?</span>
                        <strong>Escríbenos por WhatsApp</strong>
                    </div>

                    <a href="https://wa.me/{{ $wspTelefono }}?text={{ $wspMensaje }}" target="_blank"
                        rel="noopener noreferrer" class="whatsapp-button">
                        <i class="fab fa-whatsapp"></i>
                        WhatsApp
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                </div>

            </div>


            {{-- =====================================================
             FORMULARIO
        ====================================================== --}}
            <div class="contact-form-card">

                <div class="form-header">

                    <span class="form-step">
                        01
                    </span>

                    <div>
                        <span>Formulario de contacto</span>
                        <h3>Cuéntanos qué necesitas</h3>
                    </div>

                </div>

                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf

                    <div class="form-grid">

                        {{-- TIPO DE DOCUMENTO --}}
                        <div class="form-field full">

                            <label>
                                Tipo de documento
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper select-wrapper">
                                <select name="id_documento_tipo" id="id_documento_tipo" class="service-select" required>
                                    <option value="">Seleccione</option>
                                    @foreach($documentosTipos ?? [] as $dt)
                                    <option value="{{ $dt->id_documento_tipo }}"
                                        data-codigo="{{ $dt->codigo ?: $dt->nombre }}"
                                        data-longitud="{{ $dt->longitud }}"
                                        {{ old('id_documento_tipo') == $dt->id_documento_tipo ? 'selected' : '' }}>
                                        {{ $dt->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        {{-- PERSONA (DNI): NOMBRES --}}
                        <div class="form-field" data-doc="persona" style="display:none;">

                            <label>
                                Nombres
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-person"></i>

                                <input type="text" name="nombre" value="{{ old('nombre') }}"
                                    placeholder="Nombres completos" maxlength="100" autocomplete="given-name">
                            </div>

                        </div>


                        {{-- PERSONA (DNI): APELLIDOS --}}
                        <div class="form-field" data-doc="persona" style="display:none;">

                            <label>
                                Apellidos
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-person"></i>

                                <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                                    placeholder="Apellidos completos" maxlength="100" autocomplete="family-name">
                            </div>

                        </div>


                        {{-- EMPRESA (RUC): NÚMERO --}}
                        <div class="form-field" data-doc="empresa" style="display:none;">

                            <label>
                                RUC
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-card-text"></i>

                                <input type="text" name="documento_numero" id="documento_numero" class="has-counter"
                                    value="{{ old('documento_numero') }}" placeholder="RUC" maxlength="11"
                                    inputmode="numeric" pattern="[0-9]{6,11}">
                                <span class="input-counter" data-counter-for="documento_numero">0/11</span>
                            </div>

                        </div>


                        {{-- EMPRESA (RUC): RAZÓN SOCIAL --}}
                        <div class="form-field" data-doc="empresa" style="display:none;">

                            <label>
                                Razón social
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-building"></i>

                                <input type="text" name="razon_social" value="{{ old('razon_social') }}"
                                    placeholder="Razon Social" maxlength="200" autocomplete="organization">
                            </div>

                        </div>


                        {{-- SERVICIO --}}
                        <div class="form-field">

                            <label>
                                Servicio
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper select-wrapper">
                                <select name="servicio" class="service-select" required>
                                    <option value="">
                                        Seleccione un servicio
                                    </option>

                                    @foreach($services as $service)
                                    <option value="{{ $service->id_service }}"
                                        {{ request('servicio') == $service->id_service ? 'selected' : '' }}>
                                        {{ $service->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                        {{-- TELÉFONO --}}
                        <div class="form-field">

                            <label>
                                Teléfono
                                <small class="text-danger">*</small>
                            </label>

                            <div class="phone-wrapper">

                                <div class="phone-prefix">
                                    <span>Perú</span>
                                    +51
                                </div>

                                <input type="tel" name="telefono" placeholder="987654321" maxlength="9"
                                    pattern="[0-9]{9}" inputmode="numeric" autocomplete="tel" required>

                            </div>

                        </div>


                        {{-- EMAIL --}}
                        <div class="form-field full">

                            <label>
                                Correo electrónico
                                <small class="text-danger">*</small>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-envelope"></i>

                                <input type="email" name="email" placeholder="correo@ejemplo.com" maxlength="120"
                                    autocomplete="email" required>
                            </div>

                        </div>


                        {{-- MENSAJE --}}
                        <div class="form-field full">

                            <label>
                                Mensaje
                                <small class="text-danger">*</small>
                            </label>

                            <div class="textarea-wrapper">

                                <textarea name="message" rows="5" maxlength="1000"
                                    placeholder="Cuéntanos brevemente qué necesitas..." required></textarea>

                                <span class="textarea-icon">
                                    <i class="bi bi-chat-left-text"></i>
                                </span>

                            </div>

                        </div>


                        {{-- HONEYPOT --}}
                        <div class="honeypot">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>


                        {{-- RECAPTCHA --}}
                        <div class="form-field full">
                            <div class="recaptcha-container">
                                @if(config('services.turnstile.site_key'))
                                <div class="cf-turnstile"
                                     data-sitekey="{{ config('services.turnstile.site_key') }}"
                                     data-theme="auto"
                                     data-size="normal">
                                </div>
                                @else
                                <div class="alert alert-warning mb-0">
                                    La verificación anti-robot no está configurada. Contacta al administrador del sitio.
                                </div>
                                @endif
                            </div>

                        </div>


                        {{-- BOTÓN --}}
                        <div class="form-field full">

                            <button type="submit" class="contact-submit">

                                <span>
                                    Enviar consulta
                                </span>

                                <div class="submit-icon">
                                    <i class="bi bi-arrow-right"></i>
                                </div>

                            </button>

                        </div>

                    </div>

                </form>

                <div class="form-security">
                    <i class="bi bi-lock-fill"></i>
                    Tus datos están protegidos y no serán compartidos con terceros.
                </div>

            </div>

        </div>

    </div>


</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const observerOptions = {
            threshold: 0.15
        };

        const observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target); // Se ejecuta solo una vez
                }
            });
        }, observerOptions);

        document.querySelectorAll('.contact-info, .contact-form-card, .contact-benefit').forEach(function (el) {
            el.classList.add('reveal-on-scroll');
            observer.observe(el);
        });
    });
</script>

@endif
@include('sections.contact-document-script')
{{-- CSS movido a assets/css/contacto.css --}}