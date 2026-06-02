@extends('layouts.appweb')

@section('title', 'Nosotros')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<section class="premium-hero"
    style="background: url('{{ asset($empresa->portada_empresarial ?? '') }}') center/cover no-repeat;">
    <div class="premium-hero-content">
        <span class="badge-top">Nuestra Esencia</span>
        <h1>Nuestra Historia</h1>
        <p>El compromiso, la innovación y el propósito que nos impulsa a conectar contigo cada día.</p>
    </div>
</section>

<main class="premium-container">

    <section class="about-grid">
        <div class="about-text">
            <span class="section-subtitle">Conócenos</span>
            <h2>Sobre Nosotros</h2>
            <p>
                {{ $empresa->descripcion_empresarial ?? 'En nuestra tienda virtual nos apasiona ofrecer soluciones que transformen tu día a día. Nos enfocamos en la curación de productos con los más altos estándares de calidad y una experiencia de compra fluida, segura y garantizada de extremo a extremo.' }}
            </p>
        </div>

        <div class="about-image-container">
            <div class="about-image-wrapper">
                @if(!empty($empresa->imagen_empresarial))
                <img src="{{ asset($empresa->imagen_empresarial) }}" alt="Infraestructura corporativa">
                @else
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
                    alt="Equipo de trabajo">
                @endif
            </div>
        </div>
    </section>

    <section class="stats-bar">
        <div class="stat-item">
            <h3>5+</h3>
            <p>Años en el Mercado</p>
        </div>
        <div class="stat-item">
            <h3>50K+</h3>
            <p>Clientes Satisfechos</p>
        </div>
        <div class="stat-item">
            <h3>100%</h3>
            <p>Envíos Garantizados</p>
        </div>
    </section>

    <section>
        <div class="mvv-premium-title">
            <h2>Nuestros Pilares</h2>
            <p style="color: var(--text-muted);">Los valores que definen el norte de nuestro negocio</p>
        </div>

        <div class="mvv-premium-grid">

            <div class="mvv-premium-card mision-card">
                <div class="icon-box">
                    <i class="bi bi-bullseye"></i>
                </div>
                <h3>Misión</h3>
                <p>{{ $empresa->mision_empresarial ?? 'Conectar a las personas con productos excepcionales a través de una plataforma ágil, segura y un servicio humano inigualable.' }}
                </p>
            </div>

            <div class="mvv-premium-card vision-card">
                <div class="icon-box">
                    <i class="bi bi-rocket-takeoff"></i>
                </div>
                <h3>Visión</h3>
                <p>{{ $empresa->vision_empresarial ?? 'Consolidarnos como el ecosistema de comercio electrónico más confiable y preferido, expandiendo fronteras tecnológicas.' }}
                </p>
            </div>

            <div class="mvv-premium-card valores-card">
                <div class="icon-box">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3>Valores</h3>
                <p>{{ $empresa->valores_empresariales ?? 'Transparencia absoluta, innovación constante, puntualidad impecable y una obsesión genuina por el cliente.' }}
                </p>
            </div>

        </div>
    </section>

</main>

@endsection