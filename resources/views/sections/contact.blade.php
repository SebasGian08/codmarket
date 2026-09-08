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
                    �Tienes una idea?
                    <span>Hag�smolo realidad.</span>
                </h2>

                <p class="contact-description">
                    Com�ntanos lo que necesitas y nuestro equipo se pondrá
                    en contacto contigo para brindarte una solución
                    personalizada.
                </p>

                <div class="contact-benefits">

                    <div class="contact-benefit">
                        <div class="contact-benefit-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>

                        <div>
                            <strong>Respuesta r�pida</strong>
                            <span>Te contactaremos lo antes posible.</span>
                        </div>
                    </div>

                    <div class="contact-benefit">
                        <div class="contact-benefit-icon">
                            <i class="bi bi-person-check-fill"></i>
                        </div>

                        <div>
                            <strong>Atenci�n personalizada</strong>
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
                        <span>�Prefieres hablar directamente?</span>
                        <strong>Escr�benos por WhatsApp</strong>
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
                        <h3>Cu�ntanos sobre tu proyecto</h3>
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
                            </div>

                        </div>


                        {{-- TEL�‰FONO --}}
                        <div class="form-field">

                            <label>
                                Tel�fono
                                <span>*</span>
                            </label>

                            <div class="phone-wrapper">

                                <div class="phone-prefix">
                                    <span>Per�</span>
                                    +51
                                </div>

                                <input type="tel" name="telefono" placeholder="987654321" maxlength="9"
                                    pattern="[0-9]{9}" inputmode="numeric" autocomplete="tel" required>

                            </div>

                        </div>


                        {{-- EMAIL --}}
                        <div class="form-field full">

                            <label>
                                Correo electr�nico
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


                        {{-- BOT�“N --}}
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
                    Tus datos est�n protegidos y no serán compartidos con terceros.
                </div>

            </div>

        </div>

    </div>


</section>

{{-- CSS movido a assets/css/contacto.css --}}