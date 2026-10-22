<section id="contacto" class="contact-brand-section">
    <div class="container">
        <div class="contact-brand-grid">
            <div class="contact-brand-location">
                <span class="contact-brand-eyebrow">Contáctanos</span>
                <h2>Estamos para <span>ayudarte</span></h2>
                <p class="contact-brand-intro">
                    Escríbenos para recibir información sobre las marcas de nuestro catálogo.
                    Nuestro equipo se pondrá en contacto contigo.
                </p>

                <div class="contact-brand-location-heading">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h3>Ubicación</h3>
                </div>

                @if($empresa->ubicacion ?? false)
                <div class="contact-brand-map">
                    <iframe
                        src="{{ $empresa->ubicacion }}"
                        title="Ubicación de {{ $empresa->nombre ?? 'la empresa' }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
                @else
                <div class="contact-brand-location-empty">
                    <i class="bi bi-map"></i>
                    <span>Configura la ubicación de Google Maps desde Empresa para mostrar el mapa aquí.</span>
                </div>
                @endif

                @if($empresa->direccion ?? false)
                <div class="contact-brand-address">
                    <i class="bi bi-pin-map"></i>
                    <div>{!! limpiarTextoEditor($empresa->direccion) !!}</div>
                </div>
                @endif

                <div class="contact-brand-company">
                    @if($empresa->telefono ?? false)
                    <a href="tel:{{ $empresa->telefono }}">
                        <i class="bi bi-telephone"></i>{{ $empresa->telefono }}
                    </a>
                    @endif
                    @if($empresa->correo ?? false)
                    <a href="mailto:{{ $empresa->correo }}">
                        <i class="bi bi-envelope"></i>{{ $empresa->correo }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="contact-brand-form-card">
                <!-- <span class="contact-brand-pill">Contact Us</span> -->
                <h2>¡Ponte en contacto!</h2>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <input type="hidden" name="tipo_consulta" value="marca">

                    <div class="contact-brand-form-grid">
                        <div class="contact-brand-field full">
                            <label for="id_documento_tipo">Tipo de documento <small class="text-danger">*</small></label>
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

                        <div class="contact-brand-field" data-doc="persona" style="display:none;">
                            <label>Nombres <small class="text-danger">*</small></label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" maxlength="100"
                                autocomplete="given-name" placeholder="Nombres completos">
                        </div>

                        <div class="contact-brand-field" data-doc="persona" style="display:none;">
                            <label>Apellidos <small class="text-danger">*</small></label>
                            <input type="text" name="apellidos" value="{{ old('apellidos') }}" maxlength="100"
                                autocomplete="family-name" placeholder="Apellidos completos">
                        </div>

                        <div class="contact-brand-field" data-doc="empresa" style="display:none;">
                            <label>RUC <small class="text-danger">*</small></label>
                            <div class="contact-brand-input-counter">
                                <input type="text" name="documento_numero" id="documento_numero"
                                    value="{{ old('documento_numero') }}" maxlength="11" inputmode="numeric"
                                    pattern="[0-9]{6,11}" placeholder="RUC">
                                <span data-counter-for="documento_numero">0/11</span>
                            </div>
                        </div>

                        <div class="contact-brand-field" data-doc="empresa" style="display:none;">
                            <label>Razón social <small class="text-danger">*</small></label>
                            <input type="text" name="razon_social" value="{{ old('razon_social') }}" maxlength="200"
                                autocomplete="organization" placeholder="Nombre de la empresa">
                        </div>

                        <div class="contact-brand-field full">
                            <label for="contact-brand-id-marca">Marca de interés <small class="text-danger">*</small></label>
                            <select id="contact-brand-id-marca" name="id_marca" class="service-select" required>
                                <option value="">Selecciona una marca</option>
                                @foreach($marcas as $marca)
                                <option value="{{ $marca->id_marca }}"
                                    {{ old('id_marca') == $marca->id_marca ? 'selected' : '' }}>
                                    {{ $marca->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="contact-brand-field">
                            <label for="contact-brand-telefono">Teléfono <small class="text-danger">*</small></label>
                            <input id="contact-brand-telefono" type="tel" name="telefono" value="{{ old('telefono') }}"
                                maxlength="20" autocomplete="tel" placeholder="Teléfono" required>
                        </div>

                        <div class="contact-brand-field">
                            <label for="contact-brand-email">Correo electrónico <small class="text-danger">*</small></label>
                            <input id="contact-brand-email" type="email" name="email" value="{{ old('email') }}"
                                maxlength="120" autocomplete="email" placeholder="correo@ejemplo.com" required>
                        </div>

                        <div class="contact-brand-field full">
                            <label for="contact-brand-message">Mensaje <small class="text-danger">*</small></label>
                            <textarea id="contact-brand-message" name="message" rows="4" maxlength="1000"
                                placeholder="Cuéntanos qué información necesitas..." required>{{ old('message') }}</textarea>
                        </div>

                        <div class="honeypot">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="contact-brand-field full">
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

                        <div class="contact-brand-field full">
                            <button type="submit" class="contact-brand-submit">Enviar consulta</button>
                        </div>
                    </div>
                </form>

                <p class="contact-brand-security">
                    <i class="bi bi-lock-fill"></i>
                    Tus datos están protegidos y no serán compartidos con terceros.
                </p>
            </div>
        </div>
    </div>
</section>
