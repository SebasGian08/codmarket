@extends('layouts.appweb')

@section('title', $service->nombre . ' - ' . ($empresa->nombre ?? 'Mi Empresa'))

@section('content')

<div class="swiper bannerSwiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide hero-slide"
            style="background-image:url('{{ asset($service->portada) }}'); background-size:cover; background-position:center;">
            <div class="hero-container">
                <div class="hero-text">
                    <h1 class="titulo-principal sec-title_three-heading">
                        {{ $service->descripcion_portada }}
                    </h1>
                    <p>
                        {{ $service->descripcion_breve_portada }}
                    </p>
                    <!-- <button class="theme-btn btn-style-seven">
                        Saber más
                    </button> -->
                </div>
                <div class="hero-image">
                    @if($service->imagen_portada)
                    {{-- Eliminado 'storage/' --}}
                    <img src="{{ asset($service->imagen_portada) }}" alt="{{ $service->nombre }}">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<section class="testimonial-three">
    <div class="auto-container">
        <div class="row clearfix">

            <div class="testimonial-three_carousel-column col-lg-6 col-md-12 col-sm-12">
                <div class="testimonial-three_carousel-inner">
                    <div class="sec-title">
                        <div class="sec-title_title">Calidad y confianza garantizada</div>
                        <h2 class="sec-title_heading">
                            Nuestro <span>Servicio</span>
                        </h2>
                        <div class="sec-title_text">
                            {!! strip_tags($service->content, '<p><strong><br>
                                    <ul>
                                        <li>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-three_image-column col-lg-6 col-md-12 col-sm-12">
                <div class="testimonial-three_image-inner">
                    <div class="testimonial-three_image">
                        <img src="{{ asset($service->imagen_referencial) }}" alt="image" />
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@if($service->benefits && $service->benefits->count())

<section class="incluido-section">
    <div class="section-wrapper">

        <div class="sec-title_three text-center" style="margin-bottom: 50px;">
            <div class="sec-title_three-title"
                style="color: #0dcaf0; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; font-size: 14px; margin-bottom: 10px;">
                Descubre lo que obtienes</div>
            <h2 class="sec-title_three-heading" style="font-size: 36px; color: #1a202c; font-weight: 800;">
                Una experiencia completa lista para <span style="color: #0dcaf0;">impulsar tu proyecto</span>
            </h2>
        </div>

        <div class="incluido-grid">

            @foreach($service->benefits as $benefit)

            <div class="incluido-item">

                <div class="incluido-icon">
                    <i class="{{ $benefit->icono }}"></i>
                </div>

                <div class="incluido-text">
                    <h4>{{ $benefit->titulo }}</h4>
                    <p>{{ $benefit->descripcion }}</p>
                </div>

            </div>

            @endforeach

        </div>

    </div>
</section>

@endif

@if($service->plans && $service->plans->count())

<section class="pricing-one">
    <div class="pricing-one_pattern-layer"
        style="background-image:url({{ asset('assets/images/background/pattern-23.png') }})"></div>

    <div class="pricing-one_pattern-two" data-parallax='{"y" : -80}'
        style="background-image:url({{ asset('assets/images/background/pattern-24.png') }})"></div>

    <div class="auto-container">

        <div class="sec-title_two centered">
            <div class="sec-title_two-title">~ Planes Exclusivos ~</div>
            <h2 class="sec-title_two-heading">
                Nuestros <span>increíbles</span> Paquetes <br>de Páginas Web
            </h2>
        </div>

        <div class="row clearfix">

            @foreach($service->plans as $plan)

            <div class="price-block col-lg-4 col-md-6 col-sm-12">

                <div class="inner-box">

                    @if($plan->destacado)
                    <div class="recomend">Más Vendido</div>
                    @endif

                    <div class="title-box">
                        <h5>{{ $plan->nombre }}</h5>
                        <div class="text">{{ $plan->descripcion }}</div>
                    </div>

                    <div class="price">
                        S/{{ $plan->precio }}
                    </div>

                    @if($plan->features && $plan->features->count())
                    <div class="lower-box">
                        <ul class="price-list">

                            @foreach($plan->features as $feature)
                            <li>{{ $feature->descripcion }}</li>
                            @endforeach

                        </ul>
                    </div>
                    @endif

                </div>

            </div>

            @endforeach

        </div>

    </div>
</section>

@endif

@if($portafolios->count())

<section id="proyectos" class="idx-proy-section">
    <div class="idx-wrap">

        <div class="sec-title_two centered">
            <div class="sec-title_two-title">~ Casos de éxito ~</div>
            <h2 class="sec-title_three-heading" style="color: #fff;">
                Proyectos desarrollados para <span>marcas y empresas</span>
            </h2>
        </div>

        <div class="idx-proy-grid">

            @foreach($portafolios as $portafolio)

            <a href="{{ $portafolio->url_demo ?? '#' }}" class="idx-proy-card">

                <div class="idx-proy-img">
                    @if($portafolio->imagen)
                    {{-- Eliminado 'storage/' --}}
                    <img src="{{ asset($portafolio->imagen) }}" alt="{{ $portafolio->titulo }}" loading="lazy">
                    @else
                    <div class="idx-proy-noimg">
                        <i class="fas fa-code"></i>
                    </div>
                    @endif
                </div>

                <div class="idx-proy-body">

                    <span class="idx-proy-cat">
                        {{ ucfirst($portafolio->tipo) }}
                    </span>

                    <div class="idx-proy-title">
                        {{ $portafolio->titulo }}
                    </div>

                    @if($portafolio->cliente)
                    <div class="idx-proy-client">
                        <i class="fas fa-building"></i>
                        {{ $portafolio->cliente }}
                    </div>
                    @endif

                    <div class="idx-proy-desc">
                        {{ Str::limit($portafolio->descripcion, 120) }}
                    </div>

                    <span class="idx-proy-link">
                        Ver proyecto <i class="fas fa-arrow-right"></i>
                    </span>

                </div>

            </a>

            @endforeach

        </div>

        <div class="idx-btn-center">
            <a href="#" class="idx-btn">
                Ver todos los proyectos <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>
</section>



@endif
<style>
    /* =========================================================
   HERO BANNER PREMIUM
========================================================= */

.hero-slide{
    position: relative;
    min-height: 95vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    z-index: 1;
}

.hero-slide::before{
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(
        90deg,
        rgba(0,0,0,0.82) 0%,
        rgba(0,0,0,0.70) 40%,
        rgba(0,0,0,0.25) 100%
    );
    z-index: -1;
}

.hero-container{
    width: 100%;
    max-width: 1380px;
    margin: auto;
    padding: 120px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 60px;
}

.hero-text{
    width: 55%;
    color: #fff;
    animation: fadeUp 1s ease;
}

.hero-text h1{
    font-size: 64px;
    line-height: 1.1;
    font-weight: 900;
    margin-bottom: 25px;
    color: #fff;
}

.hero-text p{
    font-size: 19px;
    line-height: 1.9;
    color: rgba(255,255,255,0.85);
    margin-bottom: 35px;
    max-width: 650px;
}

.hero-btns{
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
}

.hero-btn-primary{
    background: linear-gradient(135deg,#00c6ff,#0072ff);
    color: #fff;
    padding: 16px 34px;
    border-radius: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: .35s ease;
    box-shadow: 0 10px 30px rgba(0,114,255,.35);
}

.hero-btn-primary:hover{
    transform: translateY(-4px);
    color: #fff;
}

.hero-btn-outline{
    border: 1px solid rgba(255,255,255,0.25);
    padding: 16px 34px;
    border-radius: 14px;
    color: #fff;
    text-decoration: none;
    transition: .35s ease;
    backdrop-filter: blur(8px);
}

.hero-btn-outline:hover{
    background: rgba(255,255,255,0.08);
    color: #fff;
}

.hero-image{
    width: 45%;
    text-align: center;
    position: relative;
    animation: floatY 4s ease-in-out infinite;
}

.hero-image img{
    width: 100%;
    max-width: 520px;
    object-fit: contain;
    filter: drop-shadow(0 25px 50px rgba(0,0,0,.45));
}

/* EFECTOS */
@keyframes fadeUp{
    from{
        opacity: 0;
        transform: translateY(40px);
    }
    to{
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes floatY{
    0%{
        transform: translateY(0px);
    }
    50%{
        transform: translateY(-14px);
    }
    100%{
        transform: translateY(0px);
    }
}

/* SWIPER */
.bannerSwiper{
    position: relative;
}

.bannerSwiper .swiper-pagination{
    bottom: 35px !important;
}

.bannerSwiper .swiper-pagination-bullet{
    width: 12px;
    height: 12px;
    background: rgba(255,255,255,.45);
    opacity: 1;
}

.bannerSwiper .swiper-pagination-bullet-active{
    width: 35px;
    border-radius: 50px;
    background: #00c6ff;
}

/* =========================================================
   SECTION SERVICIO
========================================================= */

.testimonial-three{
    padding: 120px 0;
    background: #f8fafc;
}

.testimonial-three_image{
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(0,0,0,.08);
}

.testimonial-three_image img{
    width: 100%;
    border-radius: 24px;
    transition: .5s ease;
}

.testimonial-three_image:hover img{
    transform: scale(1.05);
}

.sec-title_heading{
    font-size: 48px;
    font-weight: 900;
    line-height: 1.2;
}

.sec-title_heading span{
    color: #00c6ff;
}

.sec-title_text{
    font-size: 17px;
    line-height: 1.9;
    color: #64748b;
}

/* =========================================================
   BENEFICIOS
========================================================= */

.incluido-section{
    padding: 120px 0;
    background: #fff;
}

.section-wrapper{
    width: 100%;
    max-width: 1320px;
    margin: auto;
    padding: 0 20px;
}

.incluido-grid{
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 30px;
}

.incluido-item{
    background: #fff;
    border-radius: 22px;
    padding: 35px;
    transition: .35s ease;
    border: 1px solid #eef2f7;
    box-shadow: 0 10px 30px rgba(0,0,0,.04);
}

.incluido-item:hover{
    transform: translateY(-10px);
    box-shadow: 0 18px 40px rgba(0,0,0,.08);
}

.incluido-icon{
    width: 75px;
    height: 75px;
    border-radius: 20px;
    background: linear-gradient(135deg,#00c6ff,#0072ff);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
}

.incluido-icon i{
    color: #fff;
    font-size: 28px;
}

.incluido-text h4{
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 15px;
}

.incluido-text p{
    color: #64748b;
    line-height: 1.8;
}

/* =========================================================
   PLANES
========================================================= */

.price-block .inner-box{
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: .35s ease;
    background: #fff;
    box-shadow: 0 10px 35px rgba(0,0,0,.05);
}

.price-block .inner-box:hover{
    transform: translateY(-10px);
}

.price{
    font-size: 52px;
    font-weight: 900;
    color: #0072ff;
    padding: 20px 0;
}

.price-list li{
    padding: 14px 0;
    border-bottom: 1px solid #eef2f7;
    color: #64748b;
}

/* =========================================================
   PORTAFOLIO
========================================================= */

.idx-proy-section{
    background: #0f172a;
    padding: 120px 0;
}

.idx-wrap{
    max-width: 1350px;
    margin: auto;
    padding: 0 20px;
}

.idx-proy-grid{
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 30px;
    margin-top: 60px;
}

.idx-proy-card{
    background: #111c34;
    border-radius: 24px;
    overflow: hidden;
    text-decoration: none;
    transition: .4s ease;
    border: 1px solid rgba(255,255,255,.05);
}

.idx-proy-card:hover{
    transform: translateY(-12px);
    border-color: rgba(0,198,255,.45);
}

.idx-proy-img{
    height: 260px;
    overflow: hidden;
}

.idx-proy-img img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: .5s ease;
}

.idx-proy-card:hover img{
    transform: scale(1.08);
}

.idx-proy-body{
    padding: 28px;
}

.idx-proy-cat{
    display: inline-block;
    padding: 8px 15px;
    border-radius: 50px;
    background: rgba(0,198,255,.12);
    color: #00c6ff;
    font-size: 13px;
    margin-bottom: 18px;
}

.idx-proy-title{
    color: #fff;
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 12px;
}

.idx-proy-client,
.idx-proy-desc{
    color: rgba(255,255,255,.7);
}

.idx-proy-link{
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-top: 22px;
    color: #00c6ff;
    font-weight: 700;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:991px){

    .hero-container{
        flex-direction: column;
        text-align: center;
    }

    .hero-text,
    .hero-image{
        width: 100%;
    }

    .hero-text h1{
        font-size: 46px;
    }

    .hero-text p{
        margin: auto auto 30px;
    }

    .hero-btns{
        justify-content: center;
    }

    .incluido-grid,
    .idx-proy-grid{
        grid-template-columns: repeat(2,1fr);
    }
}

@media(max-width:768px){

    .hero-slide{
        min-height: auto;
        padding: 90px 0;
    }

    .hero-container{
        padding: 50px 20px;
    }

    .hero-text h1{
        font-size: 34px;
    }

    .hero-text p{
        font-size: 16px;
    }

    .sec-title_heading{
        font-size: 34px;
    }

    .incluido-grid,
    .idx-proy-grid{
        grid-template-columns: 1fr;
    }

    .price{
        font-size: 42px;
    }
}
</style>
@include('sections.contact')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
new Swiper('.bannerSwiper', {
    loop: true,
    speed: 900,
    effect: 'fade',
    fadeEffect: {
        crossFade: true
    },
    autoplay: {
        delay: 5000,
        disableOnInteraction: false
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
});
</script>
@endsection