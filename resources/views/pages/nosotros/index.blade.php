@extends('layouts.appweb')

@section('title', 'Nosotros')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/sobre-nosotros.css') }}">

{{-- =========================================================
     HERO / SOBRE NOSOTROS
========================================================= --}}
<section class="about-hero"
    style="background-image: url('{{ asset($empresa->portada_empresarial ?? '') }}');">

    <div class="about-hero-overlay"></div>

    <div class="about-hero-container">

        <div class="about-hero-content">

            <span class="about-label">
                <span></span>
                QUIÉNES SOMOS
            </span>

            <h1>
                Sobre <strong>Nosotros</strong>
            </h1>

            <div class="about-line"></div>

            <p>
                Somos una empresa comprometida con brindar soluciones
                profesionales, confiables y especializadas, orientadas al
                crecimiento y desarrollo de nuestros clientes.
            </p>

            <a href="#nuestra-esencia" class="about-btn">
                Conoce más
                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

        {{-- Elemento visual --}}
        <div class="about-hero-visual">

            <div class="visual-circle visual-circle-1"></div>
            <div class="visual-circle visual-circle-2"></div>

            <div class="visual-image">

                @if(!empty($empresa->imagen_empresarial))

                    <img src="{{ asset($empresa->imagen_empresarial) }}"
                         alt="Equipo profesional">

                @else

                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
                         alt="Equipo profesional">

                @endif

            </div>

            <div class="experience-badge">
                <strong>+10</strong>
                <span>Años de<br>experiencia</span>
            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     SOBRE NOSOTROS
========================================================= --}}
<section class="about-section" id="nuestra-esencia">

    <div class="about-container">

        <div class="about-content">

            <span class="section-tag">
                CONÓCENOS
            </span>

            <h2>
                Comprometidos con la
                <span>excelencia</span>
            </h2>

            <div class="section-decoration"></div>

            <div class="about-description">
                {!! $empresa->descripcion_empresarial ??
                'Somos una empresa comprometida con brindar soluciones profesionales de alta calidad, enfocadas en generar valor y resultados sostenibles para nuestros clientes.' !!}
            </div>

        </div>

        <div class="about-side">

            <div class="about-side-card">

                <i class="bi bi-award"></i>

                <div>
                    <strong>Experiencia</strong>
                    <span>Profesionales especializados</span>
                </div>

            </div>

            <div class="about-side-card">

                <i class="bi bi-shield-check"></i>

                <div>
                    <strong>Compromiso</strong>
                    <span>Soluciones confiables y seguras</span>
                </div>

            </div>

            <div class="about-side-card">

                <i class="bi bi-graph-up-arrow"></i>

                <div>
                    <strong>Resultados</strong>
                    <span>Orientados a la mejora continua</span>
                </div>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
     INDICADORES
========================================================= --}}
<section class="about-stats">

    <div class="stats-container">

        <div class="stat">

            <span class="stat-number">+10</span>

            <span class="stat-label">
                Años de experiencia
            </span>

        </div>

        <div class="stat">

            <span class="stat-number">100%</span>

            <span class="stat-label">
                Compromiso profesional
            </span>

        </div>

        <div class="stat">

            <span class="stat-number">360°</span>

            <span class="stat-label">
                Soluciones integrales
            </span>

        </div>

        <div class="stat">

            <span class="stat-number">ISO</span>

            <span class="stat-label">
                Estándares internacionales
            </span>

        </div>

    </div>

</section>


{{-- =========================================================
     MISIÓN / VISIÓN / VALORES
========================================================= --}}
<section class="mvv-section">

    <div class="mvv-container">

        <div class="mvv-heading">

            <span class="section-tag">
                NUESTRA ESENCIA
            </span>

            <h2>
                Los pilares que
                <span>nos definen</span>
            </h2>

            <p>
                Nuestro trabajo se construye sobre principios que nos
                permiten ofrecer un servicio profesional, responsable
                y orientado a resultados.
            </p>

        </div>


        <div class="mvv-grid">

            {{-- MISIÓN --}}
            <article class="mvv-card">

                <div class="mvv-number">
                    01
                </div>

                <div class="mvv-icon">
                    <i class="bi bi-bullseye"></i>
                </div>

                <h3>
                    Misión
                </h3>

                <p>
                    {!! $empresa->mision_empresarial ??
                    'Brindar soluciones especializadas y eficientes que contribuyan al desarrollo y crecimiento sostenible de nuestros clientes.' !!}
                </p>

            </article>


            {{-- VISIÓN --}}
            <article class="mvv-card">

                <div class="mvv-number">
                    02
                </div>

                <div class="mvv-icon">
                    <i class="bi bi-eye"></i>
                </div>

                <h3>
                    Visión
                </h3>

                <p>
                    {!! $empresa->vision_empresarial ??
                    'Ser reconocidos como una empresa referente por nuestra excelencia profesional, innovación y compromiso con nuestros clientes.' !!}
                </p>

            </article>


            {{-- VALORES --}}
            <article class="mvv-card">

                <div class="mvv-number">
                    03
                </div>

                <div class="mvv-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <h3>
                    Valores
                </h3>

                <p>
                    {!! $empresa->valores_empresariales ??
                    'Integridad, responsabilidad, excelencia, compromiso, innovación y orientación al cliente.' !!}
                </p>

            </article>

        </div>

    </div>

</section>


{{-- =========================================================
     CTA
========================================================= --}}
<section class="about-cta"
        style="background-image: linear-gradient(rgba(10,15,20,.9), rgba(10,15,20,.9)), url('{{ asset($empresa->portada_empresarial ?? '') }}'); background-size: cover; background-position: center;">

    <div class="about-cta-overlay"></div>

    <div class="about-cta-content">

        <span class="section-tag">
            TRABAJEMOS JUNTOS
        </span>

        <h2>
            ¿Necesitas una solución
            <span>especializada?</span>
        </h2>

        <p>
            Nuestro equipo está preparado para ayudarte.
        </p>

        <a href="#contacto" class="about-btn">
            Contáctanos
            <i class="bi bi-arrow-right"></i>
        </a>

    </div>

</section>


@endsection