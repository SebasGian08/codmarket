<section
    class="blog_section sec_ptb_50 clearfix {{ ($config['blog_tipo'] ?? 'opcion_1') === 'opcion_2' ? 'blog_editorial_section' : '' }}">
    <div class="container">

        @php
        $blogTipo = $config['blog_tipo'] ?? 'opcion_1';
        @endphp

        @if($blogTipo === 'opcion_2')

        <div class="blog_editorial_header">
            @if(($config['seccion_blog_mostrar_encabezado'] ?? 1) == 1)
            <div class="section_heading_title blog_editorial_section_title">
                <small>{{ $config['seccion_blog_titulo'] ?? 'ÚLTIMAS NOVEDADES' }}</small>
                <span></span>
            </div>
            @endif

            <a class="blog_editorial_link" href="{{ route('blog.index') }}">
                Ir a novedades
                <i class="fal fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        @if($blogs->isNotEmpty())
        <div class="blog_editorial_grid">
            @php
            $blogDestacado = $blogs->first();
            @endphp

            <article class="blog_editorial_card blog_editorial_featured">
                <a href="{{ route('blog.show', $blogDestacado->slug) }}" class="blog_editorial_image">
                    <img src="{{ imagenOrDefault($blogDestacado->image) }}" alt="{{ $blogDestacado->title }}">
                    <span class="blog_editorial_details">
                        @if($blogDestacado->category)
                        <span class="blog_editorial_category">{{ $blogDestacado->category->name }}</span>
                        @endif
                        <span class="blog_editorial_title">{{ $blogDestacado->title }}</span>
                        <time datetime="{{ $blogDestacado->created_at->toDateString() }}">
                            {{ $blogDestacado->created_at->translatedFormat('d \d\e F Y') }}
                        </time>
                    </span>
                </a>
            </article>

            <div class="blog_editorial_secondary">
                @foreach($blogs->skip(1)->take(4) as $blog)
                <article class="blog_editorial_card">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="blog_editorial_image">
                        <img src="{{ imagenOrDefault($blog->image) }}" alt="{{ $blog->title }}">
                        <span class="blog_editorial_details">
                            @if($blog->category)
                            <span class="blog_editorial_category">{{ $blog->category->name }}</span>
                            @endif
                            <span class="blog_editorial_title">{{ $blog->title }}</span>
                            <time datetime="{{ $blog->created_at->toDateString() }}">
                                {{ $blog->created_at->translatedFormat('d \d\e F Y') }}
                            </time>
                        </span>
                    </a>
                </article>
                @endforeach
            </div>
        </div>
        @endif

        @else

        @if(($config['seccion_blog_mostrar_encabezado'] ?? 1) == 1)
        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_blog_titulo'] ?? 'ÚLTIMAS NOVEDADES' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_blog_descripcion'] ?? 'Explora nuestras novedades') !!}
            </p>
        </div>
        @endif

        <!-- SWIPER -->
        <div class="swiper blogSwiper">

            <div class="swiper-wrapper">

                @foreach($blogs as $blog)
                <div class="swiper-slide">

                    <article class="blog_card">

                        <a href="{{ route('blog.show', $blog->slug) }}" class="blog_image">
                            <img src="{{ imagenOrDefault($blog->image) }}" alt="{{ $blog->title }}">
                            <span class="blog_date">
                                {{ $blog->created_at->format('d M Y') }}
                            </span>
                        </a>

                        <div class="blog_content">

                            @if($blog->category)
                            <a href="{{ route('blog.index', ['category' => $blog->category->id_blogs_categories]) }}"
                                class="blog_category">
                                {{ $blog->category->name }}
                            </a>
                            @endif

                            <h3 class="blog_title">
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    {{ $blog->title }}
                                </a>
                            </h3>

                            <p class="blog_excerpt">
                                {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($blog->excerpt ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 140) }}
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

        @endif

    </div>
</section>

@if($blogTipo !== 'opcion_2')
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
@endif