<div class="banner-contact-panel" aria-label="Formulario de contacto" @unless($firstBannerHasContent) hidden @endunless>
    <div class="banner-contact-card">
        <div class="banner-contact-heading">
            <span>Formulario de contacto</span>
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
                <div class="form-field full">
                    <label for="banner-documento-tipo">Tipo de documento <span>*</span></label>
                    <div class="input-wrapper select-wrapper">
                        <select id="banner-documento-tipo" name="id_documento_tipo" class="service-select" required>
                            <option value="">Seleccione</option>
                            @foreach($documentosTipos ?? [] as $dt)
                            <option value="{{ $dt->id_documento_tipo }}" data-codigo="{{ $dt->codigo ?: $dt->nombre }}" data-longitud="{{ $dt->longitud }}" {{ old('id_documento_tipo') == $dt->id_documento_tipo ? 'selected' : '' }}>{{ $dt->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-field" data-doc="persona" style="display:none;">
                    <label for="banner-nombre">Nombres <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-nombre" type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombres completos" maxlength="100" autocomplete="given-name">
                    </div>
                </div>

                <div class="form-field" data-doc="persona" style="display:none;">
                    <label for="banner-apellidos">Apellidos <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-apellidos" type="text" name="apellidos" value="{{ old('apellidos') }}" placeholder="Apellidos completos" maxlength="100" autocomplete="family-name">
                    </div>
                </div>

                <div class="form-field" data-doc="empresa" style="display:none;">
                    <label for="banner-documento-numero">RUC <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-documento-numero" type="text" name="documento_numero" class="has-counter" value="{{ old('documento_numero') }}" placeholder="RUC" maxlength="11" inputmode="numeric" pattern="[0-9]{6,11}">
                        <span class="input-counter" data-counter-for="banner-documento-numero">0/11</span>
                    </div>
                </div>

                <div class="form-field" data-doc="empresa" style="display:none;">
                    <label for="banner-razon-social">Razón social <span>*</span></label>
                    <div class="input-wrapper">
                        <input id="banner-razon-social" type="text" name="razon_social" value="{{ old('razon_social') }}" placeholder="Nombre de la empresa" maxlength="200" autocomplete="organization">
                    </div>
                </div>

                <div class="form-field">
                    <label for="banner-servicio">Servicio <span>*</span></label>
                    <div class="input-wrapper select-wrapper">
                        <select id="banner-servicio" name="servicio" class="service-select" required>
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
                    <div class="banner-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
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

<script>
    (function () {
        function initBannerTipoDocumento() {
            const root = document.querySelector('.banner-contact-panel');
            if (!root) return;

            const docTipo = root.querySelector('#banner-documento-tipo');
            if (!docTipo || docTipo.dataset.bound === '1') return;
            docTipo.dataset.bound = '1';

            const camposPersona = root.querySelectorAll('[data-doc="persona"]');
            const camposEmpresa = root.querySelectorAll('[data-doc="empresa"]');
            const docNumero = root.querySelector('#banner-documento-numero');
            const docContador = root.querySelector('[data-counter-for="banner-documento-numero"]');

            function actualizarContador() {
                if (!docNumero || !docContador) return;
                const max = docNumero.maxLength > 0 ? docNumero.maxLength : 11;
                docContador.textContent = (docNumero.value || '').length + '/' + max;
            }

            function aplicarTipoDocumento() {
                const opt = docTipo.options[docTipo.selectedIndex];
                const valor = opt ? (opt.value || '') : '';
                const vacio = valor === '';
                const etiqueta = ((opt && (opt.getAttribute('data-codigo') || opt.textContent)) || '').trim().toUpperCase();
                const esEmpresa = etiqueta.indexOf('RUC') !== -1;

                camposPersona.forEach(function (el) {
                    const visible = !vacio && !esEmpresa;
                    el.style.display = visible ? '' : 'none';
                    el.querySelectorAll('input, select, textarea').forEach(function (i) { i.required = visible; });
                });

                camposEmpresa.forEach(function (el) {
                    const visible = !vacio && esEmpresa;
                    el.style.display = visible ? '' : 'none';
                    el.querySelectorAll('input, select, textarea').forEach(function (i) { i.required = visible; });
                });

                if (docNumero) {
                    const longitud = parseInt((opt && opt.getAttribute('data-longitud')) || '0', 10);
                    docNumero.maxLength = longitud > 0 ? longitud : 11;
                    docNumero.placeholder = esEmpresa ? 'RUC' : 'Número de documento';
                }

                actualizarContador();
            }

            if (docNumero) {
                docNumero.addEventListener('input', actualizarContador);
            }

            // nice-select notifica cambios con jQuery .trigger('change')
            if (window.jQuery) {
                window.jQuery(docTipo).off('change.tipoDoc').on('change.tipoDoc', aplicarTipoDocumento);
                window.jQuery(document).off('click.tipoDocBanner').on('click.tipoDocBanner', '.banner-contact-panel .nice-select .option', function () {
                    setTimeout(aplicarTipoDocumento, 0);
                });
            } else {
                docTipo.addEventListener('change', aplicarTipoDocumento);
            }

            aplicarTipoDocumento();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initBannerTipoDocumento);
        } else {
            initBannerTipoDocumento();
        }
    })();
</script>
