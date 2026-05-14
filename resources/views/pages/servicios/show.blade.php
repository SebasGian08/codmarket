@extends('layouts.appweb')

@section('title', '{{ $service->nombre }} - $empresa->nombre ?? 'Mi Empresa')

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