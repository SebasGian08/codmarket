@extends('layouts.appweb')

@section('title', 'Nosotros')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
<section class="about-cta">

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


<style>

/* =========================================================
   VARIABLES
========================================================= */

:root {
    --about-primary: #ff6b00;
    --about-primary-dark: #d95500;
    --about-dark: #111820;
    --about-dark-2: #18232d;
    --about-text: #344454;
    --about-muted: #718096;
    --about-light: #f6f7f8;
    --about-white: #ffffff;
}


/* =========================================================
   HERO
========================================================= */

.about-hero {
    position: relative;
    min-height: 720px;
    background-position: center;
    background-size: cover;
    display: flex;
    align-items: center;
    overflow: hidden;
}

.about-hero-overlay {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(
            90deg,
            rgba(8, 13, 18, .96) 0%,
            rgba(8, 13, 18, .88) 45%,
            rgba(8, 13, 18, .40) 100%
        );
}

.about-hero-container {
    position: relative;
    z-index: 2;

    width: min(1250px, 90%);
    margin: auto;

    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: 50px;
}


/* =========================================================
   HERO TEXT
========================================================= */

.about-hero-content {
    max-width: 650px;
}

.about-label {
    display: inline-flex;
    align-items: center;
    gap: 10px;

    color: var(--about-primary);

    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;

    border: 1px solid rgba(255, 107, 0, .4);
    border-radius: 30px;

    padding: 9px 17px;

    margin-bottom: 25px;
}

.about-label span {
    width: 7px;
    height: 7px;
    background: var(--about-primary);
    border-radius: 50%;
}

.about-hero h1 {
    color: white;
    font-family: Georgia, serif;

    font-size: clamp(55px, 6vw, 82px);
    line-height: 1;

    margin: 0;
}

.about-hero h1 strong {
    color: var(--about-primary);
    font-weight: 700;
}

.about-line {
    width: 70px;
    height: 4px;

    background: var(--about-primary);

    margin: 28px 0;
}

.about-hero-content p {
    max-width: 610px;

    color: rgba(255,255,255,.72);

    font-size: 18px;
    line-height: 1.8;

    margin-bottom: 35px;
}


/* =========================================================
   BUTTON
========================================================= */

.about-btn {
    display: inline-flex;
    align-items: center;
    gap: 14px;

    padding: 17px 28px;

    background: var(--about-primary);
    color: white;

    border-radius: 40px;

    font-weight: 700;
    text-decoration: none;

    transition: .3s ease;

    box-shadow: 0 12px 35px rgba(255,107,0,.25);
}

.about-btn i {
    font-size: 20px;
    transition: .3s ease;
}

.about-btn:hover {
    background: var(--about-primary-dark);
    color: white;
    transform: translateY(-3px);
}

.about-btn:hover i {
    transform: translateX(5px);
}


/* =========================================================
   HERO VISUAL
========================================================= */

.about-hero-visual {
    position: relative;

    min-height: 560px;

    display: flex;
    align-items: center;
    justify-content: center;
}

.visual-circle {
    position: absolute;

    border: 1px dashed rgba(255,107,0,.55);

    border-radius: 50%;
}

.visual-circle-1 {
    width: 480px;
    height: 480px;
}

.visual-circle-2 {
    width: 360px;
    height: 360px;
}

.visual-image {
    position: relative;
    z-index: 2;

    width: 370px;
    height: 470px;

    border-radius: 190px 190px 25px 25px;

    overflow: hidden;

    border: 6px solid rgba(255,255,255,.9);

    box-shadow: 0 30px 80px rgba(0,0,0,.35);
}

.visual-image img {
    width: 100%;
    height: 100%;

    object-fit: cover;
}

.experience-badge {
    position: absolute;

    right: 20px;
    bottom: 50px;

    z-index: 5;

    display: flex;
    align-items: center;
    gap: 12px;

    padding: 18px 25px;

    background: var(--about-primary);

    color: white;

    border-radius: 8px;

    box-shadow: 0 15px 40px rgba(0,0,0,.3);
}

.experience-badge strong {
    font-size: 38px;
    line-height: 1;
}

.experience-badge span {
    font-size: 13px;
    line-height: 1.3;
    font-weight: 600;
}


/* =========================================================
   ABOUT SECTION
========================================================= */

.about-section {
    padding: 110px 0;

    background: white;
}

.about-container {
    width: min(1150px, 90%);
    margin: auto;

    display: grid;
    grid-template-columns: 1.4fr .8fr;

    gap: 100px;
    align-items: center;
}

.section-tag {
    display: inline-block;

    color: var(--about-primary);

    font-size: 13px;
    font-weight: 800;

    letter-spacing: 2px;

    margin-bottom: 15px;
}

.about-content h2,
.mvv-heading h2 {
    color: var(--about-dark);

    font-family: Georgia, serif;

    font-size: clamp(40px, 4vw, 58px);

    line-height: 1.1;

    margin: 0;
}

.about-content h2 span,
.mvv-heading h2 span,
.about-cta h2 span {
    color: var(--about-primary);
}

.section-decoration {
    width: 60px;
    height: 4px;

    background: var(--about-primary);

    margin: 25px 0;
}

.about-description {
    color: var(--about-text);

    font-size: 17px;
    line-height: 1.9;
}

.about-description p {
    margin-bottom: 15px;
}


/* =========================================================
   SIDE CARDS
========================================================= */

.about-side {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.about-side-card {
    display: flex;
    align-items: center;
    gap: 18px;

    padding: 23px;

    background: var(--about-light);

    border-left: 4px solid var(--about-primary);

    border-radius: 5px;

    transition: .3s ease;
}

.about-side-card:hover {
    transform: translateX(7px);
    box-shadow: 0 12px 35px rgba(0,0,0,.07);
}

.about-side-card i {
    font-size: 30px;
    color: var(--about-primary);
}

.about-side-card strong {
    display: block;

    color: var(--about-dark);

    font-size: 17px;

    margin-bottom: 4px;
}

.about-side-card span {
    color: var(--about-muted);
    font-size: 14px;
}


/* =========================================================
   STATS
========================================================= */

.about-stats {
    background: var(--about-dark);
    padding: 60px 0;
}

.stats-container {
    width: min(1150px, 90%);
    margin: auto;

    display: grid;
    grid-template-columns: repeat(4, 1fr);
}

.stat {
    text-align: center;

    padding: 15px 25px;

    border-right: 1px solid rgba(255,255,255,.12);
}

.stat:last-child {
    border-right: none;
}

.stat-number {
    display: block;

    color: var(--about-primary);

    font-family: Georgia, serif;

    font-size: 48px;
    font-weight: 700;

    margin-bottom: 8px;
}

.stat-label {
    color: rgba(255,255,255,.65);

    font-size: 14px;
}


/* =========================================================
   MVV
========================================================= */

.mvv-section {
    padding: 110px 0;

    background: var(--about-light);
}

.mvv-container {
    width: min(1150px, 90%);
    margin: auto;
}

.mvv-heading {
    max-width: 700px;
    margin-bottom: 55px;
}

.mvv-heading p {
    color: var(--about-muted);

    font-size: 16px;
    line-height: 1.8;

    margin-top: 20px;
}

.mvv-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);

    gap: 25px;
}

.mvv-card {
    position: relative;

    background: white;

    padding: 45px 35px;

    border-radius: 8px;

    overflow: hidden;

    box-shadow: 0 15px 45px rgba(20,30,40,.07);

    transition: .35s ease;
}

.mvv-card::after {
    content: "";

    position: absolute;

    left: 0;
    bottom: 0;

    width: 100%;
    height: 4px;

    background: var(--about-primary);

    transform: scaleX(0);
    transform-origin: left;

    transition: .35s ease;
}

.mvv-card:hover {
    transform: translateY(-8px);
}

.mvv-card:hover::after {
    transform: scaleX(1);
}

.mvv-number {
    position: absolute;

    right: 25px;
    top: 20px;

    color: rgba(255,107,0,.12);

    font-size: 65px;
    font-weight: 800;
}

.mvv-icon {
    width: 65px;
    height: 65px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(255,107,0,.1);

    color: var(--about-primary);

    border-radius: 50%;

    font-size: 27px;

    margin-bottom: 25px;
}

.mvv-card h3 {
    color: var(--about-dark);

    font-size: 25px;

    margin-bottom: 15px;
}

.mvv-card p {
    color: var(--about-muted);

    line-height: 1.8;

    font-size: 15px;
}


/* =========================================================
   CTA
========================================================= */

.about-cta {
    position: relative;

    padding: 100px 20px;

    background:
        linear-gradient(
            rgba(10,15,20,.9),
            rgba(10,15,20,.9)
        ),
        url('{{ asset($empresa->portada_empresarial ?? '') }}')
        center/cover;

    text-align: center;
}

.about-cta-content {
    position: relative;
    z-index: 2;

    max-width: 750px;
    margin: auto;
}

.about-cta h2 {
    color: white;

    font-family: Georgia, serif;

    font-size: clamp(38px, 5vw, 60px);

    line-height: 1.1;

    margin-bottom: 20px;
}

.about-cta p {
    color: rgba(255,255,255,.7);

    font-size: 17px;

    margin-bottom: 30px;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .about-hero {
        min-height: auto;
        padding: 100px 0;
    }

    .about-hero-container {
        grid-template-columns: 1fr;
    }

    .about-hero-visual {
        min-height: 480px;
    }

    .about-container {
        grid-template-columns: 1fr;
        gap: 50px;
    }

    .mvv-grid {
        grid-template-columns: 1fr;
    }

    .stats-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }

    .stat:nth-child(2) {
        border-right: none;
    }

}


@media (max-width: 600px) {

    .about-hero h1 {
        font-size: 48px;
    }

    .about-hero-content p {
        font-size: 16px;
    }

    .about-hero-visual {
        min-height: 390px;
    }

    .visual-image {
        width: 270px;
        height: 350px;
    }

    .visual-circle-1 {
        width: 340px;
        height: 340px;
    }

    .visual-circle-2 {
        width: 270px;
        height: 270px;
    }

    .experience-badge {
        right: 0;
        bottom: 15px;
    }

    .stats-container {
        grid-template-columns: 1fr 1fr;
    }

    .stat {
        border-right: none;
    }

    .stat-number {
        font-size: 38px;
    }

}

</style>

@endsection