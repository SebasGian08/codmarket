@extends('layouts.appweb')

@section('title', $service->nombre . ' - ' . ($empresa->nombre ?? 'Mi Empresa'))

@section('content')

{{-- =========================================================
HERO
========================================================= --}}

<section class="service-hero"
    style="background-image: url('{{ asset($service->portada ?: 'assets/images/tienda_virtual/1200x600px.png') }}');">

```
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
                Solicitar información
                <i class="bi bi-arrow-up-right"></i>
            </a>
        </div>

    </div>

    <div class="service-hero-visual">

        <div class="hero-image-glow"></div>

        <div class="hero-image-card">
            <img
                src="{{ asset($service->imagen_portada ?: 'assets/images/tienda_virtual/1080x1080px.png') }}"
                alt="{{ $service->nombre }}"
            >
        </div>

    </div>

</div>

<div class="hero-scroll">
    <span>Descubre más</span>
    <i class="bi bi-chevron-down"></i>
</div>
```

</section>

{{-- =========================================================
SERVICIO
========================================================= --}}

<section id="servicio" class="service-detail-section">

```
<div class="service-container">

    <div class="service-detail-grid">

        <div class="service-detail-content">

            <span class="section-eyebrow">
                <i class="bi bi-check2-circle"></i>
                Calidad y confianza
            </span>

            <h2>
                Una solución diseñada para
                <span>hacer crecer tu negocio</span>
            </h2>

            <div class="service-description">
                {!! strip_tags($service->content, '<p><strong><br><ul><li>') !!}
            </div>

            <div class="service-mini-features">

                <div>
                    <i class="bi bi-shield-check"></i>
                    <span>Soluciones confiables</span>
                </div>

                <div>
                    <i class="bi bi-person-check"></i>
                    <span>Atención personalizada</span>
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

                <img
                    src="{{ asset($service->imagen_referencial ?: 'assets/images/tienda_virtual/1080x1080px.png') }}"
                    alt="{{ $service->nombre }}"
                    loading="lazy"
                >

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
```

</section>

{{-- =========================================================
BENEFICIOS
========================================================= --}}
@if($service->benefits && $service->benefits->count())

<section class="benefits-section">

```
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
            Obtén una solución completa pensada para ofrecerte
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
```

</section>

@endif

{{-- =========================================================
PLANES
========================================================= --}}
@if($service->plans && $service->plans->count())

<section class="plans-section">

```
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
                        Más recomendado
                    </div>

                @endif

                <div class="plan-header">

                    <span class="plan-label">
                        {{ $plan->destacado ? 'Nuestra recomendación' : 'Plan' }}
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
```

</section>

@endif

{{-- =========================================================
PORTAFOLIO
========================================================= --}}
@if($portafolios->count())

<section id="proyectos" class="portfolio-section">

```
<div class="service-container">

    <div class="section-heading-center light">

        <span class="section-eyebrow">
            <i class="bi bi-briefcase"></i>
            Casos de éxito
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

            <a
                href="{{ $portafolio->url_demo ?? '#' }}"
                class="portfolio-card"
                target="{{ $portafolio->url_demo ? '_blank' : '_self' }}"
                rel="{{ $portafolio->url_demo ? 'noopener noreferrer' : '' }}"
            >

                <div class="portfolio-image">

                    @if($portafolio->imagen)

                        <img
                            src="{{ asset($portafolio->imagen) }}"
                            alt="{{ $portafolio->titulo }}"
                            loading="lazy"
                        >

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
```

</section>

@endif

{{-- =========================================================
CONTACTO
========================================================= --}}
@include('sections.contact')

<style>

/* =========================================================
   VARIABLES
========================================================= */

:root{
    --service-primary: var(--color-primario);
    --service-secondary: var(--color-secundario);
    --service-dark: #0b1220;
    --service-dark-2: #111b2e;
    --service-text: #172033;
    --service-muted: #64748b;
    --service-light: #f8fafc;
}


/* =========================================================
   GENERAL
========================================================= */

.service-container{
    width: min(1380px, calc(100% - 40px));
    margin: 0 auto;
}

.section-eyebrow{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    color: var(--service-secondary);
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.section-heading-center{
    max-width: 800px;
    margin: 0 auto 65px;
    text-align: center;
}

.section-heading-center h2{
    margin: 0 0 20px;
    color: var(--service-text);
    font-size: clamp(36px, 4vw, 56px);
    line-height: 1.1;
    font-weight: 900;
    letter-spacing: -1.5px;
}

.section-heading-center h2 span{
    color: var(--service-secondary);
}

.section-heading-center p{
    max-width: 650px;
    margin: 0 auto;
    color: var(--service-muted);
    font-size: 17px;
    line-height: 1.8;
}

.section-heading-center.light h2{
    color: #fff;
}

.section-heading-center.light p{
    color: rgba(255,255,255,.65);
}


/* =========================================================
   HERO
========================================================= */

.service-hero{
    position: relative;
    min-height: 88vh;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    overflow: hidden;
}

.service-hero-overlay{
    position: absolute;
    inset: 0;
    background:
        radial-gradient(
            circle at 75% 50%,
            rgba(0,0,0,.05),
            rgba(0,0,0,.35)
        ),
        linear-gradient(
            90deg,
            rgba(5,10,20,.94) 0%,
            rgba(5,10,20,.84) 42%,
            rgba(5,10,20,.35) 100%
        );
}

.service-hero-content{
    position: relative;
    z-index: 2;
    width: min(1380px, calc(100% - 40px));
    margin: auto;
    padding: 120px 0;
    display: grid;
    grid-template-columns: 1.05fr .95fr;
    align-items: center;
    gap: 80px;
}

.service-hero-text{
    animation: heroText .9s ease both;
}

.service-eyebrow{
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 9px 16px;
    margin-bottom: 25px;
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 100px;
    background: rgba(255,255,255,.07);
    color: rgba(255,255,255,.9);
    backdrop-filter: blur(10px);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.3px;
}

.service-eyebrow i{
    color: var(--service-secondary);
}

.service-hero-text h1{
    max-width: 760px;
    margin: 0 0 25px;
    color: #fff;
    font-size: clamp(46px, 5.5vw, 78px);
    line-height: 1.02;
    font-weight: 900;
    letter-spacing: -2.5px;
}

.service-hero-text p{
    max-width: 680px;
    margin: 0 0 35px;
    color: rgba(255,255,255,.76);
    font-size: 19px;
    line-height: 1.8;
}

.service-hero-actions{
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.service-btn-primary,
.service-btn-secondary{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 15px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 800;
    transition: .3s ease;
}

.service-btn-primary{
    background: var(--service-secondary);
    color: #fff;
    box-shadow: 0 15px 35px rgba(0,0,0,.2);
}

.service-btn-primary:hover{
    transform: translateY(-3px);
    color: #fff;
}

.service-btn-secondary{
    color: #fff;
    border: 1px solid rgba(255,255,255,.2);
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(10px);
}

.service-btn-secondary:hover{
    background: rgba(255,255,255,.12);
    color: #fff;
}

.service-hero-visual{
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: heroImage 1.1s .15s ease both;
}

.hero-image-glow{
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    background: var(--service-secondary);
    opacity: .15;
    filter: blur(70px);
}

.hero-image-card{
    position: relative;
    width: min(100%, 540px);
    padding: 18px;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 30px;
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(12px);
    box-shadow: 0 35px 80px rgba(0,0,0,.3);
    transform: rotate(2deg);
    transition: .5s ease;
}

.hero-image-card:hover{
    transform: rotate(0) translateY(-8px);
}

.hero-image-card img{
    width: 100%;
    height: 500px;
    display: block;
    object-fit: cover;
    border-radius: 20px;
}

.hero-scroll{
    position: absolute;
    z-index: 3;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 7px;
    color: rgba(255,255,255,.55);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.hero-scroll i{
    color: var(--service-secondary);
    animation: scrollBounce 1.5s infinite;
}


/* =========================================================
   SERVICIO
========================================================= */

.service-detail-section{
    padding: 130px 0;
    background: var(--service-light);
}

.service-detail-grid{
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 100px;
}

.service-detail-content h2{
    max-width: 650px;
    margin: 0 0 28px;
    color: var(--service-text);
    font-size: clamp(38px, 4vw, 58px);
    line-height: 1.08;
    font-weight: 900;
    letter-spacing: -1.5px;
}

.service-detail-content h2 span{
    color: var(--service-secondary);
}

.service-description{
    max-width: 650px;
    padding: 0;
    color: var(--service-muted);
    font-size: 17px;
    line-height: 1.9;
}

.service-description p{
    margin-bottom: 16px;
}

.service-description strong{
    color: var(--service-text);
}

.service-description ul{
    padding-left: 20px;
}

.service-description li{
    margin-bottom: 8px;
}

.service-mini-features{
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 35px;
}

.service-mini-features div{
    display: flex;
    align-items: center;
    gap: 9px;
    color: var(--service-text);
    font-size: 13px;
    font-weight: 700;
}

.service-mini-features i{
    color: var(--service-secondary);
    font-size: 20px;
}

.service-detail-image{
    position: relative;
}

.service-image-frame{
    position: relative;
    z-index: 2;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: 0 30px 70px rgba(15,23,42,.16);
}

.service-image-frame img{
    width: 100%;
    height: 620px;
    display: block;
    object-fit: cover;
    transition: .6s ease;
}

.service-image-frame:hover img{
    transform: scale(1.04);
}

.image-decoration{
    position: absolute;
    border-radius: 50%;
    background: var(--service-secondary);
    opacity: .12;
}

.image-decoration-one{
    width: 150px;
    height: 150px;
    top: -60px;
    right: -60px;
}

.image-decoration-two{
    width: 100px;
    height: 100px;
    bottom: -40px;
    left: -40px;
}

.image-floating-card{
    position: absolute;
    z-index: 3;
    left: 25px;
    bottom: 25px;
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px 18px;
    border-radius: 14px;
    background: rgba(255,255,255,.94);
    box-shadow: 0 15px 40px rgba(0,0,0,.15);
    backdrop-filter: blur(10px);
}

.image-floating-card > i{
    color: var(--service-secondary);
    font-size: 28px;
}

.image-floating-card strong,
.image-floating-card small{
    display: block;
}

.image-floating-card strong{
    color: var(--service-text);
    font-size: 13px;
}

.image-floating-card small{
    margin-top: 3px;
    color: var(--service-muted);
    font-size: 11px;
}


/* =========================================================
   BENEFICIOS
========================================================= */

.benefits-section{
    padding: 130px 0;
    background: #fff;
}

.benefits-grid{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

.benefit-card{
    position: relative;
    min-height: 280px;
    padding: 35px;
    border: 1px solid #edf1f5;
    border-radius: 22px;
    background: #fff;
    overflow: hidden;
    transition: .4s ease;
}

.benefit-card:hover{
    transform: translateY(-8px);
    border-color: transparent;
    box-shadow: 0 25px 60px rgba(15,23,42,.1);
}

.benefit-number{
    position: absolute;
    top: 20px;
    right: 25px;
    color: #e8edf3;
    font-size: 55px;
    line-height: 1;
    font-weight: 900;
}

.benefit-icon{
    position: relative;
    width: 62px;
    height: 62px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    border-radius: 17px;
    background: linear-gradient(
        135deg,
        var(--service-secondary),
        var(--service-primary)
    );
    color: #fff;
    font-size: 24px;
    box-shadow: 0 12px 25px rgba(0,0,0,.1);
}

.benefit-content{
    position: relative;
}

.benefit-content h3{
    margin: 0 0 12px;
    color: var(--service-text);
    font-size: 21px;
    font-weight: 800;
}

.benefit-content p{
    margin: 0;
    color: var(--service-muted);
    line-height: 1.7;
    font-size: 14px;
}

.benefit-arrow{
    position: absolute;
    right: 25px;
    bottom: 25px;
    color: var(--service-secondary);
    opacity: 0;
    transform: translate(-5px, 5px);
    transition: .3s ease;
}

.benefit-card:hover .benefit-arrow{
    opacity: 1;
    transform: translate(0,0);
}


/* =========================================================
   PLANES
========================================================= */

.plans-section{
    position: relative;
    padding: 130px 0;
    background: var(--service-dark);
    overflow: hidden;
}

.plans-background{
    position: absolute;
    inset: 0;
    background:
        radial-gradient(
            circle at 10% 20%,
            color-mix(in srgb, var(--service-secondary) 15%, transparent),
            transparent 35%
        ),
        radial-gradient(
            circle at 90% 80%,
            color-mix(in srgb, var(--service-primary) 15%, transparent),
            transparent 35%
        );
    pointer-events: none;
}

.plans-grid{
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    align-items: stretch;
}

.plan-card{
    position: relative;
    padding: 38px;
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 25px;
    background: rgba(255,255,255,.055);
    backdrop-filter: blur(12px);
    transition: .4s ease;
}

.plan-card:hover{
    transform: translateY(-10px);
    border-color: rgba(255,255,255,.18);
}

.plan-featured{
    background: #fff;
    border: 2px solid var(--service-secondary);
    box-shadow: 0 25px 70px rgba(0,0,0,.25);
    transform: translateY(-15px);
}

.plan-featured:hover{
    transform: translateY(-22px);
}

.plan-badge{
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    border-radius: 50px;
    background: var(--service-secondary);
    color: #fff;
    white-space: nowrap;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
}

.plan-label{
    color: rgba(255,255,255,.45);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.2px;
}

.plan-featured .plan-label{
    color: var(--service-secondary);
}

.plan-header h3{
    margin: 10px 0;
    color: #fff;
    font-size: 30px;
    font-weight: 900;
}

.plan-featured .plan-header h3{
    color: var(--service-text);
}

.plan-header p{
    min-height: 45px;
    margin: 0;
    color: rgba(255,255,255,.55);
    font-size: 14px;
    line-height: 1.6;
}

.plan-featured .plan-header p{
    color: var(--service-muted);
}

.plan-price{
    margin: 30px 0;
    color: var(--service-secondary);
    font-size: 50px;
    font-weight: 900;
    letter-spacing: -2px;
}

.plan-price small{
    font-size: 18px;
    font-weight: 700;
}

.plan-features{
    padding-top: 25px;
    border-top: 1px solid rgba(255,255,255,.09);
}

.plan-featured .plan-features{
    border-color: #edf1f5;
}

.plan-feature{
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 0;
    color: rgba(255,255,255,.7);
    font-size: 14px;
    line-height: 1.5;
}

.plan-featured .plan-feature{
    color: var(--service-muted);
}

.plan-feature i{
    flex-shrink: 0;
    margin-top: 2px;
    color: var(--service-secondary);
}

.plan-button{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 30px;
    padding: 14px 20px;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 12px;
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    transition: .3s ease;
}

.plan-button:hover{
    background: var(--service-secondary);
    border-color: var(--service-secondary);
    color: #fff;
}

.plan-featured .plan-button{
    background: var(--service-secondary);
    border-color: var(--service-secondary);
}


/* =========================================================
   PORTAFOLIO
========================================================= */

.portfolio-section{
    padding: 130px 0;
    background: #0f172a;
}

.portfolio-grid{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.portfolio-card{
    display: block;
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 22px;
    background: #111c34;
    overflow: hidden;
    text-decoration: none;
    transition: .4s ease;
}

.portfolio-card:hover{
    transform: translateY(-10px);
    border-color: color-mix(
        in srgb,
        var(--service-secondary) 45%,
        transparent
    );
    box-shadow: 0 25px 60px rgba(0,0,0,.25);
}

.portfolio-image{
    position: relative;
    height: 280px;
    overflow: hidden;
}

.portfolio-image img{
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: .6s ease;
}

.portfolio-card:hover .portfolio-image img{
    transform: scale(1.07);
}

.portfolio-overlay{
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(5,10,20,.65);
    opacity: 0;
    transition: .4s ease;
}

.portfolio-card:hover .portfolio-overlay{
    opacity: 1;
}

.portfolio-overlay span{
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 18px;
    border-radius: 50px;
    background: var(--service-secondary);
    color: #fff;
    font-size: 12px;
    font-weight: 800;
}

.portfolio-no-image{
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(
        135deg,
        var(--service-primary),
        var(--service-secondary)
    );
    color: #fff;
    font-size: 50px;
}

.portfolio-body{
    padding: 27px;
}

.portfolio-category{
    display: inline-flex;
    padding: 6px 12px;
    margin-bottom: 15px;
    border-radius: 50px;
    background: color-mix(
        in srgb,
        var(--service-secondary) 12%,
        transparent
    );
    color: var(--service-secondary);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.portfolio-body h3{
    margin: 0 0 10px;
    color: #fff;
    font-size: 23px;
    font-weight: 800;
}

.portfolio-client{
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
    color: rgba(255,255,255,.5);
    font-size: 12px;
}

.portfolio-body p{
    min-height: 45px;
    margin: 0;
    color: rgba(255,255,255,.62);
    font-size: 14px;
    line-height: 1.7;
}

.portfolio-link{
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-top: 20px;
    color: var(--service-secondary);
    font-size: 13px;
    font-weight: 800;
}


/* =========================================================
   ANIMACIONES
========================================================= */

@keyframes heroText{
    from{
        opacity: 0;
        transform: translateY(35px);
    }

    to{
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes heroImage{
    from{
        opacity: 0;
        transform: translateX(35px);
    }

    to{
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scrollBounce{
    0%,100%{
        transform: translateY(0);
    }

    50%{
        transform: translateY(5px);
    }
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:1100px){

    .service-hero-content{
        grid-template-columns: 1fr;
        gap: 50px;
        text-align: center;
    }

    .service-hero-text p{
        margin-left: auto;
        margin-right: auto;
    }

    .service-hero-actions{
        justify-content: center;
    }

    .service-hero-visual{
        max-width: 600px;
        width: 100%;
        margin: auto;
    }

    .service-detail-grid{
        gap: 60px;
    }

    .benefits-grid,
    .plans-grid,
    .portfolio-grid{
        grid-template-columns: repeat(2, 1fr);
    }

    .plan-featured{
        transform: none;
    }

    .plan-featured:hover{
        transform: translateY(-10px);
    }
}


@media(max-width:768px){

    .service-container{
        width: min(100% - 30px, 1380px);
    }

    .service-hero{
        min-height: auto;
    }

    .service-hero-content{
        width: min(100% - 30px, 1380px);
        padding: 100px 0 80px;
    }

    .service-hero-text h1{
        font-size: 40px;
        letter-spacing: -1.5px;
    }

    .service-hero-text p{
        font-size: 16px;
        line-height: 1.7;
    }

    .service-hero-visual{
        display: none;
    }

    .hero-scroll{
        display: none;
    }

    .service-btn-primary,
    .service-btn-secondary{
        width: 100%;
    }

    .service-detail-section,
    .benefits-section,
    .plans-section,
    .portfolio-section{
        padding: 80px 0;
    }

    .service-detail-grid{
        grid-template-columns: 1fr;
        gap: 50px;
    }

    .service-detail-content h2{
        font-size: 36px;
    }

    .service-detail-image{
        order: -1;
    }

    .service-image-frame img{
        height: 400px;
    }

    .service-mini-features{
        flex-direction: column;
        gap: 13px;
    }

    .section-heading-center{
        margin-bottom: 45px;
    }

    .section-heading-center h2{
        font-size: 35px;
    }

    .benefits-grid,
    .plans-grid,
    .portfolio-grid{
        grid-template-columns: 1fr;
    }

    .benefit-card{
        min-height: auto;
    }

    .plan-card{
        padding: 30px 25px;
    }

    .portfolio-image{
        height: 240px;
    }

    .image-floating-card{
        left: 15px;
        right: 15px;
        bottom: 15px;
    }
}


@media(max-width:480px){

    .service-hero-text h1{
        font-size: 34px;
    }

    .service-eyebrow{
        font-size: 10px;
    }

    .section-heading-center h2{
        font-size: 30px;
    }

    .service-detail-content h2{
        font-size: 31px;
    }

    .service-image-frame img{
        height: 330px;
    }

    .plan-price{
        font-size: 43px;
    }

    .portfolio-body{
        padding: 22px;
    }

}

</style>

{{-- Bootstrap Icons --}}

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

@endsection
