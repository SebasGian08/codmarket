<section class="cookie-consent" data-cookie-consent
    data-google-analytics-id="{{ $config['google_analytics_id'] ?? '' }}"
    data-google-tag-manager-id="{{ $config['google_tag_manager_id'] ?? '' }}"
    role="region" aria-label="Preferencias de cookies">
    <div class="cookie-consent__content">
        <div class="cookie-consent__copy">
            <h2>Usamos cookies</h2>
            <p>
                Podemos usar cookies opcionales para mejorar tu experiencia.
                <a href="{{ url('/politica-privacidad') }}">Más información</a>
            </p>
            <p class="cookie-consent__status" data-cookie-status role="status" aria-live="polite"></p>
        </div>

        <div class="cookie-consent__actions">
            <button type="button" class="cookie-consent__button cookie-consent__button--secondary"
                data-cookie-reject>Rechazar opcionales</button>
            <button type="button" class="cookie-consent__button cookie-consent__button--secondary"
                data-cookie-configure aria-expanded="false">Configurar</button>
            <button type="button" class="cookie-consent__button cookie-consent__button--primary"
                data-cookie-accept>Aceptar todas</button>
        </div>

        <div class="cookie-consent__settings" data-cookie-settings hidden>
            <label>
                <input type="checkbox" checked disabled>
                <span><strong>Necesarias</strong> — siempre activas para seguridad y funcionamiento.</span>
            </label>
            <label>
                <input type="checkbox" data-cookie-tracking>
                <span><strong>Analítica y marketing</strong> — Google Analytics y etiquetas configuradas en GTM.</span>
            </label>
            <button type="button" class="cookie-consent__button cookie-consent__button--primary"
                data-cookie-save>Guardar preferencias</button>
        </div>
    </div>
</section>
