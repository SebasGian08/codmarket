<section id="contacto" class="contact-premium">

    @php
        $wspTelefono = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');
        $wspMensaje = urlencode('Hola, vengo de la web! Quisiera pedir más información.');
    @endphp


    <div class="contact-bg"></div>

    <div class="auto-container">

        <div class="contact-grid">

            {{-- =====================================================
             INFORMACIÓN
        ====================================================== --}}
            <div class="contact-info">

                <span class="contact-eyebrow">
                    <i class="bi bi-chat-dots-fill"></i>
                    Hablemos de tu proyecto
                </span>

                <h2>
                    ¿Tienes una idea?
                    <span>Hagámosla realidad.</span>
                </h2>

                <p class="contact-description">
                    Cuéntanos qué necesitas y nuestro equipo se pondrá
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

                    <a href="https://wa.me/{{ $wspTelefono }}?text={{ $wspMensaje }}" target="_blank" rel="noopener noreferrer" class="whatsapp-button">
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
                        <h3>Cuéntanos sobre tu proyecto</h3>
                    </div>

                </div>

                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf

                    <div class="form-grid">

                        {{-- NOMBRES --}}
                        <div class="form-field">

                            <label>
                                Nombres
                                <span>*</span>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-person"></i>

                                <input type="text" name="nombre" placeholder="Nombres completos" maxlength="100"
                                    autocomplete="given-name" required>
                            </div>

                        </div>


                        {{-- APELLIDOS --}}
                        <div class="form-field">

                            <label>
                                Apellidos
                                <span>*</span>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-person"></i>

                                <input type="text" name="apellidos" placeholder="Apellidos completos" maxlength="100"
                                    autocomplete="family-name" required>
                            </div>

                        </div>


                        {{-- SERVICIO --}}
                        <div class="form-field">

                            <label>
                                Servicio
                                <span>*</span>
                            </label>

                            <div class="input-wrapper select-wrapper">
                                <i class="bi bi-grid"></i>

                                <select name="servicio" required>

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

                                <i class="bi bi-chevron-down select-arrow"></i>

                            </div>

                        </div>


                        {{-- TELÉFONO --}}
                        <div class="form-field">

                            <label>
                                Teléfono
                                <span>*</span>
                            </label>

                            <div class="phone-wrapper">

                                <div class="phone-prefix">
                                    <span>🇵🇪</span>
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
                                <span>*</span>
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
                                <span>*</span>
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
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}">
                                </div>
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

<style>
/* =========================================================
   CONTACT PREMIUM
========================================================= */

.contact-premium {
    position: relative;
    padding: 120px 0;
    overflow: hidden;
    background:
        linear-gradient(135deg,
            color-mix(in srgb, var(--color-primario) 6%, var(--color-fondo)) 0%,
            var(--color-fondo) 55%,
            color-mix(in srgb, var(--color-secundario) 6%, var(--color-fondo)) 100%);
}

.contact-bg {
    position: absolute;
    width: 600px;
    height: 600px;
    top: -300px;
    right: -200px;
    border-radius: 50%;
    background: var(--color-secundario);
    opacity: .07;
    filter: blur(30px);
}

.contact-grid {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: .85fr 1.15fr;
    gap: 80px;
    align-items: center;
}


/* =========================================================
   INFORMACIÓN
========================================================= */

.contact-info {
    color: var(--color-texto);
}

.contact-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 22px;
    color: var(--color-secundario);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.contact-info h2 {
    margin: 0 0 25px;
    color: var(--color-texto);
    font-size: clamp(42px, 4.5vw, 62px);
    line-height: 1.05;
    font-weight: 900;
    letter-spacing: -2px;
}

.contact-info h2 span {
    display: block;
    color: var(--color-secundario);
}

.contact-description {
    max-width: 520px;
    margin-bottom: 40px;
    color: color-mix(in srgb, var(--color-texto) 65%, transparent);
    font-size: 17px;
    line-height: 1.85;
}


/* =========================================================
   BENEFICIOS CONTACTO
========================================================= */

.contact-benefits {
    display: flex;
    flex-direction: column;
    gap: 22px;
    margin-bottom: 40px;
}

.contact-benefit {
    display: flex;
    align-items: center;
    gap: 15px;
}

.contact-benefit-icon {
    flex: 0 0 46px;
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid color-mix(in srgb, var(--color-texto) 10%, transparent);
    border-radius: 13px;
    background: color-mix(in srgb, var(--color-texto) 6%, transparent);
    color: var(--color-secundario);
    font-size: 19px;
}

.contact-benefit strong,
.contact-benefit span {
    display: block;
}

.contact-benefit strong {
    margin-bottom: 3px;
    color: var(--color-texto);
    font-size: 14px;
    font-weight: 800;
}

.contact-benefit span {
    color: color-mix(in srgb, var(--color-texto) 48%, transparent);
    font-size: 12px;
}


/* =========================================================
   WHATSAPP
========================================================= */

.contact-whatsapp {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 15px;
    border: 1px solid color-mix(in srgb, var(--color-texto) 9%, transparent);
    border-radius: 17px;
    background: color-mix(in srgb, var(--color-texto) 5%, transparent);
    backdrop-filter: blur(10px);
}

.whatsapp-icon {
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: #25d366;
    color: #fff;
    font-size: 24px;
}

.whatsapp-content {
    flex: 1;
}

.whatsapp-content span,
.whatsapp-content strong {
    display: block;
}

.whatsapp-content span {
    margin-bottom: 3px;
    color: color-mix(in srgb, var(--color-texto) 45%, transparent);
    font-size: 11px;
}

.whatsapp-content strong {
    color: var(--color-texto);
    font-size: 13px;
}

.whatsapp-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 11px 15px;
    border-radius: 10px;
    background: #25d366;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    transition: .3s ease;
}

.whatsapp-button:hover {
    transform: translateY(-2px);
    color: #fff;
}


/* =========================================================
   FORM CARD
========================================================= */

.contact-form-card {
    padding: 38px;
    border: 1px solid color-mix(in srgb, var(--color-texto) 12%, transparent);
    border-radius: 26px;
    background: rgba(255, 255, 255, .97);
    box-shadow: 0 35px 90px color-mix(in srgb, var(--color-texto) 14%, transparent);
}


/* =========================================================
   HEADER FORM
========================================================= */

.form-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding-bottom: 25px;
    margin-bottom: 25px;
    border-bottom: 1px solid #edf0f4;
}

.form-step {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: linear-gradient(135deg,
            var(--color-secundario),
            var(--color-primario));
    color: #fff;
    font-size: 12px;
    font-weight: 900;
}

.form-header span:not(.form-step) {
    display: block;
    margin-bottom: 4px;
    color: var(--color-secundario);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.form-header h3 {
    margin: 0;
    color: #111827;
    font-size: 23px;
    font-weight: 900;
}


/* =========================================================
   FORM GRID
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-field {
    min-width: 0;
}

.form-field.full {
    grid-column: 1 / -1;
}

.form-field label {
    display: block;
    margin-bottom: 8px;
    color: #263244;
    font-size: 12px;
    font-weight: 800;
}

.form-field label span {
    color: #ef4444;
}


/* =========================================================
   INPUTS
========================================================= */

.input-wrapper,
.phone-wrapper,
.textarea-wrapper {
    position: relative;
}

.input-wrapper i {
    position: absolute;
    z-index: 2;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 16px;
    pointer-events: none;
}

.input-wrapper input,
.input-wrapper select,
.phone-wrapper input,
.textarea-wrapper textarea {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    outline: none;
    background: #f8fafc;
    color: #1e293b;
    font-family: inherit;
    font-size: 13px;
    transition: .25s ease;
}

.input-wrapper input,
.input-wrapper select,
.phone-wrapper input {
    height: 50px;
    padding: 0 15px 0 43px;
}

.input-wrapper input:focus,
.input-wrapper select:focus,
.phone-wrapper input:focus,
.textarea-wrapper textarea:focus {
    border-color: var(--color-secundario);
    background: #fff;
    box-shadow: 0 0 0 3px color-mix(in srgb,
            var(--color-secundario) 10%,
            transparent);
}

.input-wrapper input::placeholder,
.phone-wrapper input::placeholder,
.textarea-wrapper textarea::placeholder {
    color: #a0aec0;
}


/* =========================================================
   SELECT
========================================================= */

.select-wrapper select {
    appearance: none;
    cursor: pointer;
}

.select-arrow {
    position: absolute;
    z-index: 2;
    top: 50%;
    right: 15px;
    left: auto;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}


/* =========================================================
   TELÉFONO
========================================================= */

.phone-wrapper {
    display: flex;
    height: 50px;
}

.phone-prefix {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 13px;
    border: 1px solid #e2e8f0;
    border-right: 0;
    border-radius: 11px 0 0 11px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 800;
}

.phone-wrapper input {
    border-radius: 0 11px 11px 0;
}


/* =========================================================
   TEXTAREA
========================================================= */

.textarea-wrapper textarea {
    min-height: 130px;
    padding: 15px;
    resize: vertical;
    line-height: 1.6;
}

.textarea-icon {
    position: absolute;
    right: 14px;
    top: 14px;
    color: #cbd5e1;
}


/* =========================================================
   RECAPTCHA
========================================================= */

.recaptcha-container {
    display: flex;
    justify-content: center;
    padding: 4px 0;
}


/* =========================================================
   HONEYPOT
========================================================= */

.honeypot {
    position: absolute !important;
    left: -9999px !important;
    width: 1px !important;
    height: 1px !important;
    overflow: hidden !important;
}


/* =========================================================
   BOTÓN
========================================================= */

.contact-submit {
    width: 100%;
    min-height: 54px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 7px 6px 22px;
    border: 0;
    border-radius: 12px;
    background: linear-gradient(135deg,
            var(--color-secundario),
            var(--color-primario));
    color: #fff;
    cursor: pointer;
    font-size: 13px;
    font-weight: 800;
    box-shadow: 0 12px 30px color-mix(in srgb,
            var(--color-primario) 22%,
            transparent);
    transition: .3s ease;
}

.contact-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 35px color-mix(in srgb,
            var(--color-primario) 28%,
            transparent);
}

.submit-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: rgba(255, 255, 255, .15);
    font-size: 17px;
}


/* =========================================================
   SECURITY
========================================================= */

.form-security {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 6px;
    margin-top: 18px;
    color: #94a3b8;
    font-size: 10px;
    text-align: center;
}

.form-security i {
    color: #64748b;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:1000px) {

    .contact-grid {
        grid-template-columns: 1fr;
        gap: 55px;
    }

    .contact-info {
        max-width: 700px;
        margin: auto;
        text-align: center;
    }

    .contact-description {
        margin-left: auto;
        margin-right: auto;
    }

    .contact-benefits {
        align-items: flex-start;
        display: inline-flex;
        text-align: left;
    }

    .contact-whatsapp {
        text-align: left;
    }

}


@media(max-width:650px) {

    .contact-premium {
        padding: 80px 0;
    }

    .contact-info h2 {
        font-size: 38px;
        letter-spacing: -1px;
    }

    .contact-description {
        font-size: 15px;
    }

    .contact-form-card {
        padding: 25px 18px;
        border-radius: 20px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .form-field.full {
        grid-column: auto;
    }

    .form-header h3 {
        font-size: 19px;
    }

    .contact-whatsapp {
        flex-wrap: wrap;
    }

    .whatsapp-content {
        min-width: calc(100% - 75px);
    }

    .whatsapp-button {
        width: 100%;
        justify-content: center;
    }

}


@media(max-width:400px) {

    .contact-info h2 {
        font-size: 33px;
    }

    .form-header {
        align-items: flex-start;
    }

    .form-step {
        flex-shrink: 0;
    }

}
</style>