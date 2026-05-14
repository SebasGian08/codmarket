<!DOCTYPE html>
<html lang="es">
<head>
    @include('admin.partials.head')
</head>
<body>
    <div class="wrapper">
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
</body>
</html>