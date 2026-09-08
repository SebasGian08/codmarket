@extends('layouts.appweb')

@section('title', $service->nombre . ' - ' . ($empresa->nombre ?? 'Mi Empresa'))

@section('content')

<section class="service-hero"
    style="background-image: url('{{ asset($service->portada ?: 'assets/images/tienda_virtual/1200x600px.png') }}');">


    <div class="service-hero-overlay"></div>

    <div class="service-hero-content">

        <div class="service-hero-text">

            <span class="service-eyebrow">
                <i class="bi bi-stars"></i>
                Soluciones profesionales
            </span>

            <h1>
                {{ $service->descripcion_portada ?: $service->nombre }}
            </h1>

            @if($service->descripcion_breve_portada)
            <p>
                {{ $service->descripcion_breve_portada }}
            </p>
            @endif

            <div class="service-hero-actions">
                <a href="#servicio" class="service-btn-primary">
                    Conocer el servicio
                    <i class="bi bi-arrow-down"></i>
                </a>

                <a href="#contacto" class="service-btn-secondary">
                    Solicitar informaciÃ³n
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>

        </div>

        <div class="service-hero-visual">

            <div class="hero-image-glow"></div>

            <div class="hero-image-card">
                <img src="{{ asset($service->imagen_portada ?: 'assets/images/tienda_virtual/1080x1080px.png') }}"
                    alt="{{ $service->nombre }}">
            </div>

        </div>

    </div>

    <div class="hero-scroll">
        <span>Descubre mÃ¡s</span>
        <i class="bi bi-chevron-down"></i>
    </div>


</section>

{{-- =========================================================
SERVICIO
========================================================= --}}

<section id="servicio" class="service-detail-section">


    <div class="service-container">

        <div class="service-detail-grid">

            <div class="service-detail-content">

                <span class="section-eyebrow">
                    <i class="bi bi-check2-circle"></i>
                    Calidad y confianza
                </span>

                <h2>
                    Una soluciÃ³n diseÃ±ada para
                    <span>hacer crecer tu negocio</span>
                </h2>

                <div class="service-description">
                    {!! strip_tags($service->content, '<p><strong><br>
                            <ul>
                                <li>') !!}
                </div>

                <div class="service-mini-features">

                    <div>
                        <i class="bi bi-shield-check"></i>
                        <span>Soluciones confiables</span>
                    </div>

                    <div>
                        <i class="bi bi-person-check"></i>
                        <span>AtenciÃ³n personalizada</span>
                    </div>

                    <div>
                        <i class="bi bi-award"></i>
                        <span>Experiencia profesional</span>
                    </div>

                </div>

            </div>

            <div class="service-detail-image">

                <div class="image-decoration image-decoration-one"></div>
                <div class="image-decoration image-decoration-two"></div>

                <div class="service-image-frame">

                    <img src="{{ asset($service->imagen_referencial ?: 'assets/images/tienda_virtual/1080x1080px.png') }}"
                        alt="{{ $service->nombre }}" loading="lazy">

                    <div class="image-floating-card">
                        <i class="bi bi-patch-check-fill"></i>
                        <div>
                            <strong>Servicio profesional</strong>
                            <small>Calidad que genera resultados</small>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>


</section>

{{-- =========================================================
BENEFICIOS
========================================================= --}}
@if($service->benefits && $service->benefits->count())

<section class="benefits-section">


    <div class="service-container">

        <div class="section-heading-center">

            <span class="section-eyebrow">
                <i class="bi bi-grid-1x2"></i>
                Todo lo que necesitas
            </span>

            <h2>
                Beneficios que hacen la
                <span>diferencia</span>
            </h2>

            <p>
                ObtÃ©n una soluciÃ³n completa pensada para ofrecerte
                mejores resultados y una experiencia profesional.
            </p>

        </div>

        <div class="benefits-grid">

            @foreach($service->benefits as $index => $benefit)

            <div class="benefit-card">

                <div class="benefit-number">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </div>

                <div class="benefit-icon">
                    <i class="{{ $benefit->icono }}"></i>
                </div>

                <div class="benefit-content">

                    <h3>
                        {{ $benefit->titulo }}
                    </h3>

                    <p>
                        {{ $benefit->descripcion }}
                    </p>

                </div>

                <div class="benefit-arrow">
                    <i class="bi bi-arrow-up-right"></i>
                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endif

{{-- =========================================================
PLANES
========================================================= --}}
@if($service->plans && $service->plans->count())

<section class="plans-section">

    <div class="plans-background"></div>

    <div class="service-container">

        <div class="section-heading-center light">

            <span class="section-eyebrow">
                <i class="bi bi-stars"></i>
                Planes exclusivos
            </span>

            <h2>
                Elige el plan ideal para
                <span>tu negocio</span>
            </h2>

            <p>
                Selecciona la alternativa que mejor se adapte
                a tus objetivos y necesidades.
            </p>

        </div>

        <div class="plans-grid">

            @foreach($service->plans as $plan)

            <div class="plan-card {{ $plan->destacado ? 'plan-featured' : '' }}">

                @if($plan->destacado)

                <div class="plan-badge">
                    <i class="bi bi-star-fill"></i>
                    MÃ¡s recomendado
                </div>

                @endif

                <div class="plan-header">

                    <span class="plan-label">
                        {{ $plan->destacado ? 'Nuestra recomendaciÃ³n' : 'Plan' }}
                    </span>

                    <h3>
                        {{ $plan->nombre }}
                    </h3>

                    @if($plan->descripcion)
                    <p>
                        {{ $plan->descripcion }}
                    </p>
                    @endif

                </div>

                <div class="plan-price">

                    <small>S/</small>
                    {{ number_format($plan->precio, 2) }}

                </div>

                @if($plan->features && $plan->features->count())

                <div class="plan-features">

                    @foreach($plan->features as $feature)

                    <div class="plan-feature">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ $feature->descripcion }}</span>
                    </div>

                    @endforeach

                </div>

                @endif

                <a href="#contacto" class="plan-button">
                    Solicitar este plan
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

            @endforeach

        </div>

    </div>


</section>

@endif

{{-- =========================================================
PORTAFOLIO
========================================================= --}}
@if($portafolios->count())

<section id="proyectos" class="portfolio-section">


    <div class="service-container">

        <div class="section-heading-center light">

            <span class="section-eyebrow">
                <i class="bi bi-briefcase"></i>
                Casos de Ã©xito
            </span>

            <h2>
                Proyectos desarrollados para
                <span>marcas y empresas</span>
            </h2>

            <p>
                Conoce algunos de los proyectos que hemos desarrollado
                y las soluciones que hemos creado para nuestros clientes.
            </p>

        </div>

        <div class="portfolio-grid">

            @foreach($portafolios as $portafolio)

            <a href="{{ $portafolio->url_demo ?? '#' }}" class="portfolio-card"
                target="{{ $portafolio->url_demo ? '_blank' : '_self' }}"
                rel="{{ $portafolio->url_demo ? 'noopener noreferrer' : '' }}">

                <div class="portfolio-image">

                    @if($portafolio->imagen)

                    <img src="{{ asset($portafolio->imagen) }}" alt="{{ $portafolio->titulo }}" loading="lazy">

                    @else

                    <div class="portfolio-no-image">
                        <i class="bi bi-code-slash"></i>
                    </div>

                    @endif

                    <div class="portfolio-overlay">
                        <span>
                            Ver proyecto
                            <i class="bi bi-arrow-up-right"></i>
                        </span>
                    </div>

                </div>

                <div class="portfolio-body">

                    <span class="portfolio-category">
                        {{ ucfirst($portafolio->tipo) }}
                    </span>

                    <h3>
                        {{ $portafolio->titulo }}
                    </h3>

                    @if($portafolio->cliente)

                    <div class="portfolio-client">
                        <i class="bi bi-building"></i>
                        {{ $portafolio->cliente }}
                    </div>

                    @endif

                    <p>
                        {{ limpiarTextoPlano($portafolio->descripcion, 120) }}
                    </p>

                    <span class="portfolio-link">
                        Ver proyecto
                        <i class="bi bi-arrow-right"></i>
                    </span>

                </div>

            </a>

            @endforeach

        </div>

    </div>


</section>

@endif

{{-- =========================================================
CONTACTO
========================================================= --}}
@include('sections.contact')

{{-- CSS movido a assets/css/servicios-show.css --}}

{{-- Bootstrap Icons --}}

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@endsection
