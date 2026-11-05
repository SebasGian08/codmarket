<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.partials.head')
</head>
<body>
    @php
        $loadingType = $config['sistema_loading_tipo'] ?? 'opcion_1';
    @endphp
    <div id="adminPreloader" class="loader-mode-{{ $loadingType }}"
        data-loader-primary="{{ $config['tema_color_primario'] ?? '#21c36c' }}"
        data-loader-secondary="{{ $config['tema_color_secundario'] ?? '#0ea5e9' }}">
        <div class="loader-wrapper">
            @if($loadingType !== 'opcion_2')
            <div class="loader-logo">
                <img src="{{ asset($config['empresa_logo_loading'] ?? 'assets/images/logo-principal.png') }}" alt="Cargando...">
            </div>
            @endif

            <div class="loader-bar">
                <div class="loader-progress"></div>
            </div>

            <div class="loader-text">Cargando ...</div>
            @if($loadingType === 'opcion_3')
            <div class="loader-percentage" aria-live="polite">0%</div>
            @endif
        </div>
    </div>

    <div class="wrapper sidebar_minimize">
        @include('admin.partials.sidebar')
        
        <div class="main-panel">
            <div class="main-header">
                @include('admin.partials.navbar')
            </div>

            <div class="container">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>

            @include('admin.partials.footer')
        </div>
    </div>

    @include('admin.partials.scripts')

    <script>
    (function() {
        var el = document.getElementById('adminPreloader');
        if (!el) return;

        el.style.setProperty('--loader-primary', el.dataset.loaderPrimary);
        el.style.setProperty('--loader-secondary', el.dataset.loaderSecondary);

        window.adminLoader = {
            show: function(msg) {
                var txt = el.querySelector('.loader-text');
                if (txt && msg) txt.textContent = msg;
                el.classList.remove('loader-hidden');
            },
            hide: function() {
                el.classList.add('loader-hidden');
            }
        };

        var progress = el.querySelector('.loader-progress');
        var percentage = el.querySelector('.loader-percentage');
        var progressValue = 0;
        var progressInterval;

        if (el.classList.contains('loader-mode-opcion_3') && progress && percentage) {
            progressInterval = window.setInterval(function() {
                progressValue = Math.min(progressValue + 1, 90);
                progress.style.width = progressValue + '%';
                percentage.textContent = progressValue + '%';
            }, 100);
        }

        function ocultar() {
            if (progressInterval) window.clearInterval(progressInterval);
            if (progress && percentage && el.classList.contains('loader-mode-opcion_3')) {
                progress.style.width = '100%';
                percentage.textContent = '100%';
            }
            window.adminLoader.hide();
        }

        window.addEventListener('load', function() {
            setTimeout(ocultar, 400);
        });

        // Respaldo: oculta aunque no se dispare 'load' (p. ej. recursos lentos)
        setTimeout(ocultar, 4000);
    })();
    </script>
</body>
</html>