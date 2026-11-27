@php
    $wizardSuccess = session('contact_wizard_success');
    $turnstileSiteKey = config('services.turnstile.site_key');
    $initialWizardStep = (int) old('wizard_current_step', 1);
    if ($errors->has('servicio')) {
        $initialWizardStep = 1;
    } elseif ($errors->has('nombre') || $errors->has('apellidos')
        || $errors->has('email') || $errors->has('telefono')) {
        $initialWizardStep = 2;
    } elseif ($errors->any()) {
        $initialWizardStep = 3;
    }
@endphp

<section id="contacto" class="contact-wizard-section">
    <div class="container">
        <div class="contact-wizard" data-contact-wizard
            @if(!$wizardSuccess)
            data-initial-step="{{ $initialWizardStep }}"
            data-turnstile-required="{{ $turnstileSiteKey ? 'true' : 'false' }}"
            @endif>
            @if($wizardSuccess)
            <div class="contact-wizard__success">
                <div class="contact-wizard__success-icon" aria-hidden="true">
                    <i class="bi bi-check-lg"></i>
                </div>
                <span class="contact-wizard__eyebrow">CONSULTA ENVIADA</span>
                <h1>¡Gracias, {{ $wizardSuccess['name'] }}!</h1>
                <p>
                    Recibimos tu consulta sobre
                    <strong>{{ $wizardSuccess['service'] }}</strong>.
                    Nuestro equipo se pondrá en contacto contigo pronto.
                </p>
                <p class="contact-wizard__trust">
                    <i class="bi bi-shield-check" aria-hidden="true"></i>
                    Tus datos están protegidos y no se compartirán con terceros para fines comerciales.
                </p>
                <button type="button" class="contact-wizard__button contact-wizard__button--primary"
                    data-wizard-reset>Enviar otra consulta</button>
            </div>
            @else
            <div class="contact-wizard__heading">
                <span class="contact-wizard__eyebrow">CONTÁCTANOS</span>
                <h1>Cuéntanos qué necesitas</h1>
                <p>Completa estos pasos y nuestro equipo te ayudará.</p>
            </div>

            <div class="contact-wizard__progress" data-wizard-progress role="progressbar"
                aria-label="Progreso del formulario" aria-valuemin="1" aria-valuemax="3"
                aria-valuenow="{{ max(1, min(3, $initialWizardStep)) }}">
                <div class="contact-wizard__progress-track">
                    <span data-wizard-progress-fill></span>
                </div>
                <ol>
                    <li data-wizard-step-label="1">
                        <span class="contact-wizard__step-number">1</span>
                        <span class="contact-wizard__step-name">Servicio</span>
                    </li>
                    <li data-wizard-step-label="2">
                        <span class="contact-wizard__step-number">2</span>
                        <span class="contact-wizard__step-name">Tus datos</span>
                    </li>
                    <li data-wizard-step-label="3">
                        <span class="contact-wizard__step-number">3</span>
                        <span class="contact-wizard__step-name">Detalles</span>
                    </li>
                </ol>
            </div>

            <form method="POST" action="{{ route('contact.store') }}" class="contact-wizard__form"
                data-contact-wizard-form novalidate>
                @csrf
                <input type="hidden" name="tipo_consulta" value="servicio">
                <input type="hidden" name="formulario_contacto" value="opcion_4">
                <input type="hidden" name="wizard_current_step" value="{{ old('wizard_current_step', 1) }}"
                    data-wizard-current-step>

                <section class="contact-wizard__panel" data-wizard-panel="1">
                    <div class="contact-wizard__step-heading">
                        <h2 tabindex="-1" data-wizard-heading>¿Qué servicio necesitas?</h2>
                        <p>Elige una opción para continuar.</p>
                    </div>

                    <div class="contact-wizard__field @error('servicio') has-error @enderror"
                        data-wizard-field="servicio">
                        <div class="contact-wizard__services" data-service-cards>
                            @forelse($services as $service)
                            <button type="button" class="contact-wizard__service-card"
                                data-service-id="{{ $service->id_service }}"
                                data-service-name="{{ $service->nombre }}" aria-pressed="false">
                                <span class="contact-wizard__service-icon" aria-hidden="true">
                                    <i class="bi bi-grid-1x2"></i>
                                </span>
                                <span class="contact-wizard__service-copy">
                                    <strong>{{ $service->nombre }}</strong>
                                    <small>
                                        {{ \Illuminate\Support\Str::limit(strip_tags((string) ($service->descripcion_breve_portada ?: ($service->descripcion ?: 'Conoce más sobre este servicio y solicita información.'))), 100) }}
                                    </small>
                                </span>
                                <span class="contact-wizard__service-check" aria-hidden="true">
                                    <i class="bi bi-check-circle-fill"></i>
                                </span>
                            </button>
                            @empty
                            <p class="contact-wizard__empty">No hay servicios disponibles en este momento.</p>
                            @endforelse
                        </div>
                        <input type="hidden" name="servicio" value="{{ old('servicio') }}" data-service-input>
                        <small class="contact-wizard__field-error" data-wizard-error>
                            @error('servicio'){{ $message }}@enderror
                        </small>
                    </div>

                    <p class="contact-wizard__trust">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        Tus datos están protegidos y no se compartirán con terceros para fines comerciales.
                    </p>
                    <div class="contact-wizard__actions contact-wizard__actions--end">
                        <button type="button" class="contact-wizard__button contact-wizard__button--primary"
                            data-wizard-next>Continuar <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                    </div>
                </section>

                <section class="contact-wizard__panel" data-wizard-panel="2" hidden>
                    <div class="contact-wizard__step-heading">
                        <h2 tabindex="-1" data-wizard-heading>Tus datos</h2>
                        <p>¿Cómo podemos comunicarnos contigo?</p>
                    </div>

                    <div class="contact-wizard__fields">
                        <div class="contact-wizard__field" data-wizard-field="nombre">
                            <label for="contact-wizard-nombre">Nombres <span>*</span></label>
                            <input id="contact-wizard-nombre" type="text" name="nombre" maxlength="100"
                                value="{{ old('nombre') }}" autocomplete="given-name"
                                placeholder="Escribe tus nombres" data-validate-on-input
                                @error('nombre') aria-invalid="true" @enderror>
                            <small class="contact-wizard__field-error" data-wizard-error>
                                @error('nombre'){{ $message }}@enderror
                            </small>
                        </div>

                        <div class="contact-wizard__field" data-wizard-field="apellidos">
                            <label for="contact-wizard-apellidos">Apellidos <span>*</span></label>
                            <input id="contact-wizard-apellidos" type="text" name="apellidos" maxlength="100"
                                value="{{ old('apellidos') }}" autocomplete="family-name"
                                placeholder="Escribe tus apellidos" data-validate-on-input
                                @error('apellidos') aria-invalid="true" @enderror>
                            <small class="contact-wizard__field-error" data-wizard-error>
                                @error('apellidos'){{ $message }}@enderror
                            </small>
                        </div>

                        <div class="contact-wizard__field" data-wizard-field="email">
                            <label for="contact-wizard-email">Correo electrónico <span>*</span></label>
                            <input id="contact-wizard-email" type="email" name="email" maxlength="120"
                                value="{{ old('email') }}" autocomplete="email"
                                placeholder="correo@ejemplo.com" data-validate-on-input
                                @error('email') aria-invalid="true" @enderror>
                            <small class="contact-wizard__field-error" data-wizard-error>
                                @error('email'){{ $message }}@enderror
                            </small>
                        </div>

                        <div class="contact-wizard__field" data-wizard-field="telefono">
                            <label for="contact-wizard-telefono">Teléfono <span>*</span></label>
                            <div class="contact-wizard__phone">
                                <span aria-hidden="true">+51</span>
                                <input id="contact-wizard-telefono" type="tel" name="telefono" maxlength="9"
                                    value="{{ old('telefono') }}" autocomplete="tel-national"
                                    inputmode="numeric" pattern="9[0-9]{8}" placeholder="9XXXXXXXX"
                                    data-validate-on-input @error('telefono') aria-invalid="true" @enderror>
                            </div>
                            <small class="contact-wizard__field-error" data-wizard-error>
                                @error('telefono'){{ $message }}@enderror
                            </small>
                        </div>
                    </div>

                    <p class="contact-wizard__trust">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        Tus datos están protegidos y no se compartirán con terceros para fines comerciales.
                    </p>
                    <div class="contact-wizard__actions">
                        <button type="button" class="contact-wizard__button contact-wizard__button--back"
                            data-wizard-back><i class="bi bi-arrow-left" aria-hidden="true"></i> Atrás</button>
                        <button type="button" class="contact-wizard__button contact-wizard__button--primary"
                            data-wizard-next>Continuar <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
                    </div>
                </section>

                <section class="contact-wizard__panel" data-wizard-panel="3" hidden>
                    <div class="contact-wizard__step-heading">
                        <h2 tabindex="-1" data-wizard-heading>¿Deseas agregar detalles?</h2>
                        <p>Este campo es opcional.</p>
                    </div>

                    <div class="contact-wizard__service-summary">
                        <span>Servicio seleccionado</span>
                        <strong data-service-summary>Selecciona un servicio</strong>
                    </div>

                    <div class="contact-wizard__field" data-wizard-field="message">
                        <label for="contact-wizard-message">Detalles <small>(opcional)</small></label>
                        <textarea id="contact-wizard-message" name="message" rows="5" maxlength="1000"
                            placeholder="Puedes escribir tu consulta si deseas..." data-validate-on-input
                            @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                        <small class="contact-wizard__field-error" data-wizard-error>
                            @error('message'){{ $message }}@enderror
                        </small>
                    </div>

                    <div class="contact-wizard__field contact-wizard__field--consent @error('acepta_privacidad') has-error @enderror"
                        data-wizard-field="acepta_privacidad">
                        @include('partials.privacy-consent')
                        <small class="contact-wizard__field-error" data-wizard-error>
                            @error('acepta_privacidad'){{ $message }}@enderror
                        </small>
                    </div>

                    <div class="contact-wizard__field contact-wizard__captcha @error('cf-turnstile-response') has-error @enderror"
                        data-wizard-field="turnstile">
                        @if($turnstileSiteKey)
                        <div class="cf-turnstile" data-sitekey="{{ $turnstileSiteKey }}"
                            data-theme="auto" data-size="normal"></div>
                        @else
                        <p class="contact-wizard__captcha-warning">
                            La verificación anti-robot no está configurada en este momento.
                        </p>
                        @endif
                        <small class="contact-wizard__field-error" data-wizard-error>
                            @error('cf-turnstile-response'){{ $message }}@enderror
                        </small>
                    </div>

                    <p class="contact-wizard__trust">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        Tus datos están protegidos y no se compartirán con terceros para fines comerciales.
                    </p>
                    <div class="contact-wizard__actions">
                        <button type="button" class="contact-wizard__button contact-wizard__button--back"
                            data-wizard-back><i class="bi bi-arrow-left" aria-hidden="true"></i> Atrás</button>
                        <button type="submit" class="contact-wizard__button contact-wizard__button--primary">
                            Enviar consulta <i class="bi bi-send" aria-hidden="true"></i>
                        </button>
                    </div>
                </section>
            </form>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script src="{{ asset('assets/js/contacto-wizard.js') }}?v={{ filemtime(base_path('assets/js/contacto-wizard.js')) }}"></script>
@endpush
