(function () {
    var panel = document.querySelector('[data-cookie-consent]');
    if (!panel) return;

    var storageKey = 'site-cookie-consent-v1';
    var analyticsId = panel.dataset.googleAnalyticsId;
    var tagManagerId = panel.dataset.googleTagManagerId;
    var settings = panel.querySelector('[data-cookie-settings]');
    var trackingToggle = panel.querySelector('[data-cookie-tracking]');
    var status = panel.querySelector('[data-cookie-status]');
    var currentConsent = null;
    var trackingLoaded = false;

    window.dataLayer = window.dataLayer || [];
    window.gtag = window.gtag || function () {
        window.dataLayer.push(arguments);
    };

    function updateGoogleConsent(allowed) {
        window.gtag('consent', 'update', {
            analytics_storage: allowed ? 'granted' : 'denied',
            ad_storage: allowed ? 'granted' : 'denied',
            ad_user_data: allowed ? 'granted' : 'denied',
            ad_personalization: allowed ? 'granted' : 'denied'
        });
    }

    window.gtag('consent', 'default', {
        analytics_storage: 'denied',
        ad_storage: 'denied',
        ad_user_data: 'denied',
        ad_personalization: 'denied',
        functionality_storage: 'granted',
        security_storage: 'granted'
    });

    function loadTracking() {
        if (trackingLoaded) return;
        trackingLoaded = true;

        if (analyticsId) {
            window.gtag('js', new Date());
            window.gtag('config', analyticsId);

            var analyticsScript = document.createElement('script');
            analyticsScript.async = true;
            analyticsScript.src = 'https://www.googletagmanager.com/gtag/js?id='
                + encodeURIComponent(analyticsId);
            document.head.appendChild(analyticsScript);
        }

        if (tagManagerId) {
            window.dataLayer.push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });

            var tagManagerScript = document.createElement('script');
            tagManagerScript.async = true;
            tagManagerScript.src = 'https://www.googletagmanager.com/gtm.js?id='
                + encodeURIComponent(tagManagerId);
            document.head.appendChild(tagManagerScript);
        }
    }

    function applyConsent(consent) {
        currentConsent = consent;
        updateGoogleConsent(consent.analyticsAndMarketing);

        if (consent.analyticsAndMarketing) {
            loadTracking();
        }
    }

    function setPanelVisible(visible, showSettings) {
        panel.hidden = !visible;
        settings.hidden = !showSettings;
        panel.querySelector('[data-cookie-configure]')
            .setAttribute('aria-expanded', showSettings ? 'true' : 'false');
    }

    function saveConsent(analyticsAndMarketing) {
        var consent = {
            version: 1,
            analyticsAndMarketing: analyticsAndMarketing,
            savedAt: new Date().toISOString()
        };

        try {
            window.localStorage.setItem(storageKey, JSON.stringify(consent));
        } catch (error) {
            status.textContent = 'No se pudieron guardar tus preferencias. Revisa la configuración del navegador e inténtalo de nuevo.';
            console.error('No se pudieron guardar las preferencias de cookies.', error);
            return;
        }

        status.textContent = '';
        applyConsent(consent);
        setPanelVisible(false, false);
    }

    function readConsent() {
        try {
            var saved = window.localStorage.getItem(storageKey);
            if (!saved) return null;

            var consent = JSON.parse(saved);
            if (consent && consent.version === 1
                && typeof consent.analyticsAndMarketing === 'boolean') {
                return consent;
            }
        } catch (error) {
            console.error('No se pudieron leer las preferencias de cookies.', error);
        }

        return null;
    }

    var savedConsent = readConsent();
    if (savedConsent) {
        trackingToggle.checked = savedConsent.analyticsAndMarketing;
        applyConsent(savedConsent);
        setPanelVisible(false, false);
    }

    panel.querySelector('[data-cookie-accept]').addEventListener('click', function () {
        trackingToggle.checked = true;
        saveConsent(true);
    });

    panel.querySelector('[data-cookie-reject]').addEventListener('click', function () {
        trackingToggle.checked = false;
        saveConsent(false);
    });

    panel.querySelector('[data-cookie-configure]').addEventListener('click', function () {
        settings.hidden = !settings.hidden;
        this.setAttribute('aria-expanded', settings.hidden ? 'false' : 'true');
    });

    panel.querySelector('[data-cookie-save]').addEventListener('click', function () {
        saveConsent(trackingToggle.checked);
    });

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-cookie-settings-open]');
        if (!trigger) return;

        event.preventDefault();
        trackingToggle.checked = Boolean(currentConsent && currentConsent.analyticsAndMarketing);
        status.textContent = '';
        setPanelVisible(true, true);
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        trackingToggle.focus();
    });
})();
