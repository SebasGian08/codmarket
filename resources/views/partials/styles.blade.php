<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $config['seo_title'] ?? 'Mi tienda online' }}</title>
<meta name="description" content="{{ $config['seo_description'] ?? '' }}">
<meta name="keywords" content="{{ $config['seo_keywords'] ?? '' }}">
<meta name="author" content="{{ $config['seo_author'] ?? 'Sistema' }}">
<meta property="og:site_name" content="{{ $config['seo_author'] ?? 'Mi tienda online' }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $config['seo_title'] ?? '' }}">
<meta name="twitter:description" content="{{ $config['seo_description'] ?? '' }}">
<meta name="twitter:image" content="{{ asset($config['seo_image'] ?? 'assets/images/og-image.jpg') }}">

<meta name="robots" content="{{ $config['seo_robots'] ?? 'index, follow' }}">
<meta name="theme-color" content="{{ $config['tema_color_primario'] ?? '#21c36c' }}">
<link rel="canonical" href="{{ url()->current() }}">

<!-- Open Graph -->
<meta property="og:title" content="{{ $config['seo_title'] ?? '' }}">
<meta property="og:description" content="{{ $config['seo_description'] ?? '' }}">
<meta property="og:image" content="{{ asset($config['seo_image'] ?? 'assets/images/og-image.jpg') }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">

<!-- Favicon -->
<link rel="shortcut icon" href="{{ asset($config['empresa_favicon'] ?? 'assets/images/logo/a.png') }}">
<link rel="icon" href="{{ asset($config['empresa_favicon'] ?? 'assets/images/favicon.png') }}">

<!-- Framework -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Iconos -->
<link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">

<!-- Animaciones -->
<link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

<!-- Plugins -->
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/slick-theme.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.css') }}">

<!-- Estilos -->
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}"> -->

<link rel="stylesheet" href="{{ asset('assets/css/principal.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/banner.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/personalizado.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/preguntas-frecuentes.css') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
:root {
    --color-fondo: {{ $config['tema_color_fondo'] ?? '#ffffff' }};
    --color-primario: {{ $config['tema_color_primario'] ?? '#21c36c' }};
    --color-secundario: {{ $config['tema_color_secundario'] ?? '#0ea5e9' }};
    --color-texto: {{ $config['tema_color_texto'] ?? '#333333' }};
    --header_color: {{ $config['header_color'] ?? '#2e2e2e' }};
}
</style>
<script>
window.addEventListener("load", function() {
    const preloader = document.getElementById("preloader");

    preloader.style.opacity = "0";
    preloader.style.transition = "0.5s ease";

    setTimeout(() => {
        preloader.style.display = "none";
    }, 500);
});
</script>
<div id="preloader">
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