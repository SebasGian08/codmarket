<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.partials.head')
</head>
<body>
    <!-- PRELOADER (estilo web) -->
    <div id="adminPreloader">
        <div class="loader-wrapper">
            <div class="loader-logo">
                <img src="{{ asset($config['empresa_logo_loading'] ?? 'assets/images/logo.png') }}" alt="Cargando...">
            </div>

            <div class="loader-bar">
                <div class="loader-progress"></div>
            </div>

            <div class="loader-text">Cargando ...</div>
        </div>
    </div>

    <style>
        #adminPreloader {
            position: fixed;
            inset: 0;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            opacity: 1;
            transition: opacity .4s ease;
        }

        #adminPreloader.loader-hidden {
            opacity: 0;
            pointer-events: none;
        }

        #adminPreloader .loader-wrapper {
            text-align: center;
            width: 220px;
        }

        #adminPreloader .loader-logo img {
            width: 90px;
            margin-bottom: 18px;
            animation: adminFloatLogo 1.8s ease-in-out infinite;
        }

        #adminPreloader .loader-bar {
            width: 100%;
            height: 4px;
            background: #eee;
            border-radius: 50px;
            overflow: hidden;
            position: relative;
        }

        #adminPreloader .loader-progress {
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg,
                {{ $config['tema_color_primario'] ?? '#21c36c' }},
                {{ $config['tema_color_secundario'] ?? '#0ea5e9' }});
            border-radius: 50px;
            animation: adminLoading 1.2s infinite ease-in-out;
        }

        #adminPreloader .loader-text {
            margin-top: 12px;
            font-size: 13px;
            color: #666;
            letter-spacing: .5px;
        }

        html[data-theme="dark"] #adminPreloader {
            background: var(--ka-surface, #273243);
        }

        html[data-theme="dark"] #adminPreloader .loader-text {
            color: var(--ka-text-muted, #9aa4b2);
        }

        html[data-theme="dark"] #adminPreloader .loader-bar {
            background: #3a4658;
        }

        @keyframes adminLoading {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(60%); }
            100% { transform: translateX(200%); }
        }

        @keyframes adminFloatLogo {
            0%, 100% { transform: translateY(0); opacity: 1; }
            50% { transform: translateY(-6px); opacity: .8; }
        }
    </style>

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

        function ocultar() {
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