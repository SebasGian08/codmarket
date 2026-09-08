@php
    $esAdmin = request()->is('admin') || request()->is('admin/*');
    $status  = $status ?? 500;
    $titulo  = $titulo  ?? 'Ocurrió un error';
    $mensaje = $mensaje ?? 'Lo sentimos, algo no salió como esperábamos. Inténtalo de nuevo más tarde.';
@endphp

@if($esAdmin)
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $status }} - Panel Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f5f7fb;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #252b3b;
        }
        .error-card {
            text-align: center;
            padding: 50px 40px;
            max-width: 520px;
            width: 92%;
        }
        .error-code {
            font-size: 110px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #177dff 0%, #2a3f8f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -4px;
        }
        .error-title {
            font-size: 26px;
            font-weight: 700;
            margin: 18px 0 10px;
        }
        .error-msg {
            font-size: 15px;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .btn-home {
            display: inline-block;
            background: #177dff;
            color: #fff;
            padding: 12px 34px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 6px 16px rgba(23, 125, 255, 0.3);
            transition: all .2s ease;
        }
        .btn-home:hover {
            background: #0f6aE8;
            transform: translateY(-2px);
        }
        .error-footer {
            margin-top: 40px;
            font-size: 13px;
            color: #adb5bd;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">{{ $status }}</div>
        <h1 class="error-title">{{ $titulo }}</h1>
        <p class="error-msg">{{ $mensaje }}</p>
        <a class="btn-home" href="{{ url('/') }}">Volver al inicio</a>
        <p class="error-footer">© {{ date('Y') }} - Panel de administración</p>
    </div>
</body>
</html>
@else
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $status }} - {{ $titulo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 20%, rgba(33, 195, 108, 0.10), transparent 45%),
                        radial-gradient(circle at 80% 80%, rgba(14, 165, 233, 0.10), transparent 45%),
                        #ffffff;
            color: #222;
            padding: 20px;
        }
        .error-card {
            text-align: center;
            max-width: 560px;
            width: 100%;
        }
        .error-code {
            font-size: 120px;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(135deg, #21c36c, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -4px;
        }
        .error-title {
            font-size: 28px;
            font-weight: 700;
            margin: 20px 0 12px;
            color: #2e2e2e;
        }
        .error-msg {
            font-size: 16px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 32px;
        }
        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #21c36c, #1ba25a);
            color: #fff;
            padding: 14px 38px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 8px 20px rgba(33, 195, 108, 0.35);
            transition: all .25s ease;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 26px rgba(33, 195, 108, 0.45);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">{{ $status }}</div>
        <h1 class="error-title">{{ $titulo }}</h1>
        <p class="error-msg">{{ $mensaje }}</p>
        <a class="btn-home" href="{{ url('/') }}">Volver al inicio</a>
    </div>
</body>
</html>
@endif