<section class="blog_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <h2>{{ $config['seccion_blog_titulo'] ?? 'Últimos artículos' }}</h2>
                <p class="text-muted">{{ $config['seccion_blog_descripcion'] ?? 'Explora nuestras novedades' }}</p>
            </div>
        </div>

        <!-- SWIPER -->
        <div class="swiper blogSwiper">

            <div class="swiper-wrapper">

                @foreach($blogs as $blog)
                <div class="swiper-slide">

                    <article class="blog_card">

                        <a href="{{ route('blog.show', $blog->slug) }}" class="blog_image">
                            <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                            <span class="blog_date">
                                {{ $blog->created_at->format('d M Y') }}
                            </span>
                        </a>

                        <div class="blog_content">

                            @if($blog->category)
                            <a href="#" class="blog_category">
                                {{ $blog->category->name }}
                            </a>
                            @endif

                            <h3 class="blog_title">
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    {{ $blog->title }}
                                </a>
                            </h3>

                            <p class="blog_excerpt">
                                {{ \Illuminate\Support\Str::limit($blog->excerpt, 140) }}
                            </p>

                            <a class="blog_btn" href="{{ route('blog.show', $blog->slug) }}">
                                Leer más →
                            </a>

                        </div>

                    </article>

                </div>
                @endforeach

            </div>

            <!-- FLECHAS -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

        </div>

    </div>
</section>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
var swiper = new Swiper(".blogSwiper", {
    slidesPerView: 2,
    spaceBetween: 25,
    speed: 700,
    loop: true,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        0: {
            slidesPerView: 1
        },
        768: {
            slidesPerView: 2
        },
        1200: {
            slidesPerView: 3
        }
    }
});
</script>
