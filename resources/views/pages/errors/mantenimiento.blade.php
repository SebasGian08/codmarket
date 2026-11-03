@extends('layouts.appweb')

@section('title', 'Mantenimiento - ' . ($empresa->nombre ?? 'Mi Empresa'))

@section('content')

<section style="background: #414141; min-height:100vh; display:flex; align-items:center; justify-content:center; color:#fff; text-align:center; padding:40px;">
    <div>

        <h1 style="font-size:42px; font-weight:700;color:white;">
            {{ $empresa->nombre ?? 'Mi Empresa' }}
        </h1>

        <h2 id="typing-text" style="margin-top:30px; font-size:28px; min-height:40px; color:white;"></h2>

        <p style="margin-top:20px; font-size:18px; opacity:0.8;">
            {{ strip_tags($config['mensaje_mantenimiento'] ?? 'Estamos realizando tareas de mantenimiento. Vuelve a visitarnos en unos minutos.') }}
        </p>

    </div>
</section>

<script>
    const texts = [
        "🚧 Estamos trabajando...",
        "⚙️ Mejorando la plataforma...",
        "✨ Pronto tendrás una mejor experiencia...",
        "💡 Diseñando algo para darte un excelente servicio..."
    ];

    let i = 0;
    let j = 0;
    let currentText = "";
    let isDeleting = false;
    const speed = 80;
    const element = document.getElementById("typing-text");

    function type() {
        currentText = texts[i];

        if (isDeleting) {
            j--;
            element.innerHTML = currentText.substring(0, j);
        } else {
            j++;
            element.innerHTML = currentText.substring(0, j);
        }

        if (!isDeleting && j === currentText.length) {
            isDeleting = true;
            setTimeout(type, 1500);
            return;
        }

        if (isDeleting && j === 0) {
            isDeleting = false;
            i = (i + 1) % texts.length;
        }

        setTimeout(type, speed);
    }

    type();
</script>

@endsection