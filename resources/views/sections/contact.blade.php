<section class="contact-one">
    <div class="auto-container">

        <div class="sec-title_three text-center">
            <div class="sec-title_three-title">
                Contacto
            </div>

            <h2 class="sec-title_three-heading">
                Contáctanos para más información
            </h2>
        </div>

        <div class="contact-wrapper">

            <div class="contact-form-box">
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf

                    <div class="row">

                        <div class="col-lg-6 form-group">
                            <label>Nombres <span class="required">*</span></label>
                            <input type="text" name="nombre" class="form-control"
                                placeholder="Ingrese Nombres Completos" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Apellidos <span class="required">*</span></label>
                            <input type="text" name="apellidos" class="form-control"
                                placeholder="Ingrese Apellidos Completos" required>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Servicio <span class="required">*</span></label>
                            <select name="servicio" class="form-select" required>
                                <option value="">Seleccione un servicio</option>
                                @foreach($services as $service)
                                <option value="{{ $service->id_service }}">
                                    {{ $service->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>Teléfono <span class="required">*</span></label>

                            <div class="phone-group">
                                <span class="phone-prefix">+51</span>
                                <input type="text" name="telefono" placeholder="987654321" maxlength="9" required>
                            </div>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com"
                                maxlength="120" required>
                        </div>

                        <div class="col-lg-12 form-group">
                            <label>Mensaje <span class="required">*</span></label>
                            <textarea name="message" class="form-control" rows="5"
                                placeholder="Escribe tu mensaje aquí..." required></textarea>
                        </div>

                        <div class="honeypot">
                            <input type="text" name="website">
                        </div>

                        <div class="col-lg-12">
                            <div class="recaptcha">
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                            </div>
                        </div>

                        <div class="col-lg-12 text-center">
                            <button class="btn-send">
                                <span>
                                    <i class="fas fa-paper-plane"></i> Enviar
                                </span>
                            </button>
                        </div>

                        <!-- <div class="whatsapp-box">
                            <span class="whatsapp-text">¿Prefieres hablar ahora?</span>

                            <a href="https://wa.link/1bzihi" target="_blank" class="whatsapp-btn">
                                <i class="fab fa-whatsapp"></i>
                                Abrir WhatsApp
                            </a>
                        </div>
 -->
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>