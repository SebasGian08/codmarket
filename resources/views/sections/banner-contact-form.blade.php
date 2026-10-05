<div class="banner-contact-panel" aria-label="Formulario de contacto" @unless($firstBannerHasContent) hidden @endunless>
    <div class="banner-contact-card">
        <div class="banner-contact-heading">
            <span>Hablemos de tu proyecto</span>
            <h2>Solicita información</h2>
        </div>

        @if(session('success'))
        <div class="banner-contact-message success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="banner-contact-message error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}">
            @csrf
            <div class="form-grid banner-contact-grid">
                <div class="form-field">
                    <label for="banner-nombre">Nombres <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-nombre" type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombres completos" maxlength="100" autocomplete="given-name" required>
                    </div>
                </div>

                <div class="form-field">
                    <label for="banner-apellidos">Apellidos <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-apellidos" type="text" name="apellidos" value="{{ old('apellidos') }}" placeholder="Apellidos completos" maxlength="100" autocomplete="family-name" required>
                    </div>
                </div>

                <div class="form-field">
                    <label for="banner-servicio">Servicio <span>*</span></label>
                    <div class="input-wrapper select-wrapper">
                        <select id="banner-servicio" name="servicio" required>
                            <option value="">Seleccione un servicio</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id_service }}" {{ old('servicio') == $service->id_service ? 'selected' : '' }}>{{ $service->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-field">
                    <label for="banner-telefono">Teléfono <span>*</span></label>
                    <div class="phone-wrapper">
                        <div class="phone-prefix">+51</div>
                        <input id="banner-telefono" type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="987654321" maxlength="9" pattern="[0-9]{9}" inputmode="numeric" autocomplete="tel" required>
                    </div>
                </div>

                <div class="form-field full">
                    <label for="banner-email">Correo electrónico <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-email" type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" maxlength="120" autocomplete="email" required>
                    </div>
                </div>

                <div class="form-field full">
                    <label for="banner-message">Mensaje <span>*</span></label>
                    <div class="textarea-wrapper">
                        <textarea id="banner-message" name="message" rows="2" maxlength="1000" placeholder="Cuéntanos brevemente qué necesitas..." required>{{ old('message') }}</textarea>
                    </div>
                </div>

                <div class="honeypot">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="form-field full banner-contact-captcha">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>

                <div class="form-field full">
                    <button type="submit" class="contact-submit">
                        <span>Enviar consulta</span>
                        <span class="submit-icon" aria-hidden="true">&#8594;</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
