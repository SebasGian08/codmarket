@php
$bannerTipo = $config['banner_tipo'] ?? 'opcion_1';
$firstBanner = $banners->first();
$firstBannerHasContent = $firstBanner && !$firstBanner->solo_imagen;
$hasContentBanner = $banners->contains(function ($banner) {
    return !$banner->solo_imagen;
});
@endphp

@if($bannerTipo === 'opcion_1')
<section class="hero_banner_slider {{ $hasContentBanner ? 'has-banner-contact' : '' }}">
    <div class="container hero_container">
        <button class="slider_btn prev">&#10094;</button>
        <button class="slider_btn next">&#10095;</button>

        <div class="slider_wrapper">

            @foreach($banners as $key => $banner)

            @if($banner->solo_imagen)

            <div class="slider_item solo-banner {{ $key == 0 ? 'active' : '' }}">

                <picture>
                    <source media="(max-width: 768px)" srcset="{{ url($banner->imagen_mobile ?: 'assets/images/tienda_virtual/1080x1350px.png') }}">

                    <img src="{{ url($banner->imagen ?: 'assets/images/tienda_virtual/2076x757px.png') }}" class="banner_full_img">
                </picture>

            </div>

            @else

            <div class="slider_item con-contenido {{ $key == 0 ? 'active' : '' }}"
                style="--banner-desktop-image: url('{{ url($banner->imagen ?: 'assets/images/tienda_virtual/2076x757px.png') }}'); --banner-mobile-image: url('{{ url($banner->imagen_mobile ?: $banner->imagen ?: 'assets/images/tienda_virtual/2076x757px.png') }}'); border-radius:20px;">

                <div class="row align-items-center hero_card flex-column flex-lg-row">

                    <div class="col-12 content_box">

                        @if($banner->subtitulo)
                        <span class="badge_text">{{ $banner->subtitulo }}</span>
                        @endif

                        @if($banner->titulo)
                        <h1 class="title">{!! nl2br(e($banner->titulo)) !!}</h1>
                        @endif

                        @if($banner->descripcion)
                        <p class="subtitle">{!! $banner->descripcion !!}</p>
                        @endif

                        @if($banner->enlace)
                        <a href="{{ $banner->enlace }}" class="btn-principal">
                            {{ $banner->texto_boton ?? 'Ver más' }}
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        @endif

                    </div>

                </div>
            </div>

            @endif

            @endforeach

        </div>

        <div class="slider_indicators" aria-label="Indicadores del banner"></div>

        @if($hasContentBanner)
        @include('sections.banner-contact-form')
        @endif

    </div>

</section>
@else
<section class="hero_banner_full {{ $hasContentBanner ? 'has-banner-contact' : '' }}">

    <button class="slider_btn prev">&#10094;</button>
    <button class="slider_btn next">&#10095;</button>

    <div class="slider_wrapper_full">

        @foreach($banners as $key => $banner)

        @if($banner->solo_imagen)

        <div class="slider_item solo-banner {{ $key == 0 ? 'active' : '' }}">

            <picture>
                <source media="(max-width: 768px)" srcset="{{ url($banner->imagen_mobile ?: 'assets/images/tienda_virtual/1080x1350px.png') }}">

                <img src="{{ url($banner->imagen ?: 'assets/images/tienda_virtual/2076x757px.png') }}" class="banner_full_img">
            </picture>

        </div>

        @else

        <div class="slider_item con-contenido {{ $key == 0 ? 'active' : '' }}"
            style="--banner-desktop-image: url('{{ url($banner->imagen ?: 'assets/images/tienda_virtual/2076x757px.png') }}'); --banner-mobile-image: url('{{ url($banner->imagen_mobile ?: $banner->imagen ?: 'assets/images/tienda_virtual/2076x757px.png') }}');">

            <div class="hero_full_content">

                <div class="content_box">

                    @if($banner->subtitulo)
                    <span class="badge_text">{{ $banner->subtitulo }}</span>
                    @endif

                    @if($banner->titulo)
                    <h1 class="title" color>{!! nl2br(e($banner->titulo)) !!}</h1>
                    @endif

                    @if($banner->descripcion)
                    <p class="subtitle">{!! $banner->descripcion !!}</p>
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
                        @if(!empty($banner->imagen_referencial))
                        <img src="{{ url($banner->imagen_referencial) }}" class="hero_img">
                        @endif
                    </picture>

                </div>

            </div>

        </div>

        @endif

        @endforeach

    </div>

    <div class="slider_indicators" aria-label="Indicadores del banner"></div>

    @if($hasContentBanner)
    @include('sections.banner-contact-form')
    @endif

</section>
@endif
<script>
document.addEventListener("DOMContentLoaded", function() {

    const sliderSection = document.querySelector(".hero_banner_slider, .hero_banner_full");

    if (!sliderSection) return;

    const slides = sliderSection.querySelectorAll(".slider_item");
    const nextBtn = sliderSection.querySelector(".next");
    const prevBtn = sliderSection.querySelector(".prev");
    const indicators = sliderSection.querySelector(".slider_indicators");
    const contactPanel = sliderSection.querySelector(".banner-contact-panel");

    if (slides.length === 0) return;

    let index = 0;
    const total = slides.length;
    let autoSlide;

    if (indicators) {
        slides.forEach((slide, i) => {
            const dot = document.createElement("button");
            dot.type = "button";
            dot.className = "slider_indicator";
            dot.setAttribute("aria-label", `Ir al banner ${i + 1} de ${total}`);
            dot.addEventListener("click", () => {
                index = i;
                showSlide(index);
            });
            indicators.appendChild(dot);
        });
    }

    function showSlide(i) {
        slides.forEach(slide => {
            slide.classList.remove("active");
        });

        slides[i].classList.add("active");

        if (contactPanel) {
            const showContact = slides[i].classList.contains("con-contenido");
            contactPanel.hidden = !showContact;
            sliderSection.classList.toggle("has-banner-contact-active", showContact);
        }

        if (indicators) {
            indicators.querySelectorAll(".slider_indicator").forEach((dot, dotIndex) => {
                const isActive = dotIndex === i;
                dot.classList.toggle("active", isActive);
                dot.setAttribute("aria-current", isActive ? "true" : "false");
            });
        }
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

    showSlide(index);

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