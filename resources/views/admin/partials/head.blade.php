<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>@yield('title', 'Panel Admin')</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">

<!-- Plugins -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/plugins.css') }}">
<link rel="stylesheet" href="{{ asset('admin/assets/css/plugins.min.css') }}">

<!-- Fonts -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('admin/assets/css/fonts.min.css') }}">

<!-- Demo (opcional, puedes quitarlo en producción) -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}">

<!-- Estilo principal (el más importante) -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/kaiadmin.min.css') }}">

<!-- Modo oscuro -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/dark-mode.css') }}">

<!-- Estilos personalizados -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/admin-custom.css') }}">

<!-- Estilos de vistas admin (ventas, productos, etc.) -->
<link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/images/favicon.png') }}">

<script>
    (function() {
        try {
            if (localStorage.getItem('admin-theme') === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        } catch (e) {}
    })();
</script>

