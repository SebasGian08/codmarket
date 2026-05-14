<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.partials.head')
    <title>@yield('title')</title>
</head>
<body style="background: #f5f7fb;">

    @yield('content')

    @include('admin.partials.scripts')
</body>
</html>