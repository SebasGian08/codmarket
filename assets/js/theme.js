(function () {
    var toggles = document.querySelectorAll('.theme_toggle_btn');
    var root = document.documentElement;

    if (toggles.length === 0) {
        return;
    }

    function syncToggle() {
        var isDark = root.getAttribute('data-theme') === 'dark';
        var label = isDark ? 'Activar modo claro' : 'Activar modo oscuro';

        toggles.forEach(function (toggle) {
            toggle.setAttribute('aria-pressed', String(isDark));
            toggle.setAttribute('aria-label', label);
            toggle.setAttribute('title', label);
        });
    }

    function setTheme(theme, persist) {
        root.setAttribute('data-theme', theme);
        syncToggle();

        if (!persist) {
            return;
        }

        try {
            localStorage.setItem('site-theme', theme);
        } catch (error) {
            console.warn('No se pudo guardar la preferencia de tema.', error);
        }
    }

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            setTheme(nextTheme, true);
        });
    });

    window.addEventListener('storage', function (event) {
        if (event.key === 'site-theme' && (event.newValue === 'dark' || event.newValue === 'light')) {
            setTheme(event.newValue, false);
        }
    });

    syncToggle();
})();
