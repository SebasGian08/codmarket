@php
    $bannerTipo = $config['banner_tipo'] ?? 'opcion_1';
@endphp

@if($bannerTipo === 'opcion_1')
<section class="hero_banner_slider">
    <div class="container hero_container">
        <button class="slider_btn prev">&#10094;</button>
        <button class="slider_btn next">&#10095;</button>

        <div class="slider_wrapper">

            @foreach($banners as $key => $banner)

            @if($banner->solo_imagen)

            <div class="slider_item solo-banner {{ $key == 0 ? 'active' : '' }}">

                <picture>
                    @if($banner->imagen_mobile)
                    <source media="(max-width: 768px)" srcset="{{ url($banner->imagen_mobile) }}">
                    @endif

                    <img src="{{ url($banner->imagen) }}" class="banner_full_img">
                </picture>

            </div>

            @else

            <div class="slider_item con-contenido {{ $key == 0 ? 'active' : '' }}"
                style="background-image: url('{{ url($banner->imagen) }}'); border-radius:20px;">

                <div class="row align-items-center hero_card flex-column flex-lg-row">

                    <div class="col-lg-6 content_box">

                        @if($banner->subtitulo)
                        <span class="badge_text">{{ $banner->subtitulo }}</span>
                        @endif

                        @if($banner->titulo)
                        <h1 class="title">{!! nl2br(e($banner->titulo)) !!}</h1>
                        @endif

                        @if($banner->descripcion)
                        <p class="subtitle">{{ $banner->descripcion }}</p>
                        @endif

                        @if($banner->enlace)
                        <a href="{{ $banner->enlace }}" class="btn-principal">
                            {{ $banner->texto_boton ?? 'Ver más' }}
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        @endif

                    </div>

                    <div class="col-lg-6 image_box">

                        <picture>

                            @if($banner->imagen_mobile)
                            <source media="(max-width: 768px)" srcset="{{ url($banner->imagen_mobile) }}">
                            @endif

                            {{-- DESKTOP --}}
                            @if($banner->imagen_referencial)
                            <img src="{{ url($banner->imagen_referencial) }}" class="img-fluid hero_img">
                            @else
                            <img src="{{ url($banner->imagen) }}" class="img-fluid hero_img">
                            @endif

                        </picture>

                    </div>

                </div>
            </div>

            @endif

            @endforeach

        </div>

    </div>

</section>
@else
<section class="hero_banner_full">

    <button class="slider_btn prev">&#10094;</button>
    <button class="slider_btn next">&#10095;</button>

    <div class="slider_wrapper_full">

        @foreach($banners as $key => $banner)

        @if($banner->solo_imagen)

        <div class="slider_item solo-banner {{ $key == 0 ? 'active' : '' }}">

            <picture>
                @if($banner->imagen_mobile)
                <source media="(max-width: 768px)" srcset="{{ url($banner->imagen_mobile) }}">
                @endif

                <img src="{{ url($banner->imagen) }}" class="banner_full_img">
            </picture>

        </div>

        @else

        <div class="slider_item con-contenido {{ $key == 0 ? 'active' : '' }}"
            style="background-image: url('{{ url($banner->imagen) }}');">

            <div class="hero_full_content">

                <div class="content_box">

                    @if($banner->subtitulo)
                    <span class="badge_text">{{ $banner->subtitulo }}</span>
                    @endif

                    @if($banner->titulo)
                    <h1 class="title" color>{!! nl2br(e($banner->titulo)) !!}</h1>
                    @endif

                    @if($banner->descripcion)
                    <p class="subtitle">{{ $banner->descripcion }}</p>
                    @endif

                    @if($banner->enlace)
                    <a href="{{ $banner->enlace }}" class="btn-principal">
                        {{ $banner->texto_boton ?? 'Ver más' }}
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif

                </div>

                <div class="image_box">

                    <picture>
                        @if($banner->imagen_mobile)
                        <source media="(max-width: 768px)" srcset="{{ url($banner->imagen_mobile) }}">
                        @endif

                        @if($banner->imagen_referencial)
                        <img src="{{ url($banner->imagen_referencial) }}" class="hero_img">
                        @else
                        <img src="{{ url($banner->imagen) }}" class="hero_img">
                        @endif
                    </picture>

                </div>

            </div>

        </div>

        @endif

        @endforeach

    </div>

</section>
@endif
<script>
document.addEventListener("DOMContentLoaded", function () {

    const sliderSection = document.querySelector(".hero_banner_slider, .hero_banner_full");

    if (!sliderSection) return;

    const slides = sliderSection.querySelectorAll(".slider_item");
    const nextBtn = sliderSection.querySelector(".next");
    const prevBtn = sliderSection.querySelector(".prev");

    if (slides.length === 0) return;

    let index = 0;
    const total = slides.length;
    let autoSlide;

    function showSlide(i) {
        slides.forEach(slide => {
            slide.classList.remove("active");
        });

        slides[i].classList.add("active");
    }

    function nextSlide() {
        index = (index + 1) % total;
        showSlide(index);
    }

    function prevSlide() {
        index = (index - 1 + total) % total;
        showSlide(index);
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", nextSlide);
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", prevSlide);
    }

    function startAutoSlide() {
        autoSlide = setInterval(nextSlide, 10000);
    }

    function stopAutoSlide() {
        clearInterval(autoSlide);
    }

    startAutoSlide();

    sliderSection.addEventListener("mouseenter", stopAutoSlide);

    sliderSection.addEventListener("mouseleave", startAutoSlide);

});
</script>