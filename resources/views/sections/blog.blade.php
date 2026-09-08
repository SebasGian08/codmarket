<section class="blog-premium">


<div class="blog-container">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}
    <div class="blog-heading">

        <div class="blog-heading-content">

            <span class="blog-eyebrow">
                <i class="bi bi-journal-text"></i>
                {{ $config['seccion_blog_titulo'] ?? 'Últimas novedades' }}
            </span>

            <h2>
                Ideas, novedades y
                <span>contenido para ti</span>
            </h2>

            <div class="blog-description">
                {!! limpiarTextoEditor(
                    $config['seccion_blog_descripcion']
                    ?? 'Explora nuestras novedades, consejos y contenidos.'
                ) !!}
            </div>

        </div>

        <div class="blog-heading-action">
            <a href="{{ route('blog.index') }}" class="blog-view-all">
                Ver todos
                <i class="bi bi-arrow-up-right"></i>
            </a>
        </div>

    </div>


    {{-- =====================================================
         BLOG SLIDER
    ====================================================== --}}
    <div class="blog-slider-wrapper">

        <div class="swiper blogSwiper">

            <div class="swiper-wrapper">

                @foreach($blogs as $blog)

                    <div class="swiper-slide">

                        <article class="blog-card">

                            {{-- IMAGEN --}}
                            <a
                                href="{{ route('blog.show', $blog->slug) }}"
                                class="blog-card-image"
                            >

                                <img
                                    src="{{ imagenOrDefault($blog->image) }}"
                                    alt="{{ $blog->title }}"
                                    loading="lazy"
                                >

                                <div class="blog-image-overlay"></div>

                                <div class="blog-date">
                                    <span>
                                        {{ $blog->created_at->format('d') }}
                                    </span>

                                    <small>
                                        {{ strtoupper($blog->created_at->format('M')) }}
                                    </small>
                                </div>

                                <div class="blog-image-arrow">
                                    <i class="bi bi-arrow-up-right"></i>
                                </div>

                            </a>


                            {{-- CONTENIDO --}}
                            <div class="blog-card-content">

                                @if($blog->category)

                                    <a href="#" class="blog-category">
                                        <i class="bi bi-tag"></i>
                                        {{ $blog->category->name }}
                                    </a>

                                @endif

                                <h3 class="blog-title">

                                    <a href="{{ route('blog.show', $blog->slug) }}">
                                        {{ $blog->title }}
                                    </a>

                                </h3>

                                @if($blog->excerpt)

                                    <p class="blog-excerpt">
                                        {{ \Illuminate\Support\Str::limit($blog->excerpt, 135) }}
                                    </p>

                                @endif

                                <div class="blog-card-footer">

                                    <a
                                        href="{{ route('blog.show', $blog->slug) }}"
                                        class="blog-read-more"
                                    >
                                        Leer artículo
                                        <i class="bi bi-arrow-right"></i>
                                    </a>

                                    <span class="blog-reading">
                                        <i class="bi bi-clock"></i>
                                        Lectura
                                    </span>

                                </div>

                            </div>

                        </article>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- NAVEGACIÓN --}}
        <div class="blog-navigation">

            <button
                type="button"
                class="blog-nav blog-prev"
                aria-label="Artículo anterior"
            >
                <i class="bi bi-arrow-left"></i>
            </button>

            <button
                type="button"
                class="blog-nav blog-next"
                aria-label="Artículo siguiente"
            >
                <i class="bi bi-arrow-right"></i>
            </button>

        </div>

    </div>

</div>


</section>

<style>

/* =========================================================
   BLOG PREMIUM
========================================================= */

.blog-premium{
    position: relative;
    padding: 120px 0;
    background: #f8fafc;
    overflow: hidden;
}

.blog-premium::before{
    content: "";
    position: absolute;
    width: 500px;
    height: 500px;
    top: -250px;
    right: -200px;
    border-radius: 50%;
    background: var(--color-secundario);
    opacity: .035;
}

.blog-container{
    width: min(1380px, calc(100% - 40px));
    margin: 0 auto;
}


/* =========================================================
   HEADER
========================================================= */

.blog-heading{
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 40px;
    margin-bottom: 55px;
}

.blog-heading-content{
    max-width: 780px;
}

.blog-eyebrow{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    color: var(--color-secundario);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.blog-eyebrow i{
    font-size: 15px;
}

.blog-heading h2{
    margin: 0 0 18px;
    color: #111827;
    font-size: clamp(38px, 4vw, 56px);
    line-height: 1.08;
    font-weight: 900;
    letter-spacing: -1.5px;
}

.blog-heading h2 span{
    color: var(--color-secundario);
}

.blog-description{
    max-width: 650px;
    color: #64748b;
    font-size: 16px;
    line-height: 1.8;
}

.blog-description p{
    margin: 0;
}


/* =========================================================
   VER TODOS
========================================================= */

.blog-view-all{
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    border: 1px solid #e2e8f0;
    border-radius: 11px;
    background: #fff;
    color: #1e293b;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    box-shadow: 0 8px 25px rgba(15,23,42,.04);
    transition: .3s ease;
}

.blog-view-all i{
    color: var(--color-secundario);
}

.blog-view-all:hover{
    transform: translateY(-3px);
    border-color: var(--color-secundario);
    color: var(--color-secundario);
}


/* =========================================================
   SLIDER
========================================================= */

.blog-slider-wrapper{
    position: relative;
}

.blogSwiper{
    width: 100%;
    overflow: hidden;
    padding: 5px 5px 35px;
    margin: -5px -5px -35px;
}

.blogSwiper .swiper-wrapper{
    align-items: stretch;
}

.blogSwiper .swiper-slide{
    height: auto;
    display: flex;
}


/* =========================================================
   CARD
========================================================= */

.blog-card{
    width: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid #e8edf3;
    border-radius: 22px;
    background: #fff;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(15,23,42,.045);
    transition: .4s ease;
}

.blog-card:hover{
    transform: translateY(-9px);
    border-color: transparent;
    box-shadow: 0 25px 60px rgba(15,23,42,.11);
}


/* =========================================================
   IMAGEN
========================================================= */

.blog-card-image{
    position: relative;
    display: block;
    height: 255px;
    overflow: hidden;
    text-decoration: none;
    background: #e2e8f0;
}

.blog-card-image img{
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: .65s ease;
}

.blog-card:hover .blog-card-image img{
    transform: scale(1.07);
}

.blog-image-overlay{
    position: absolute;
    inset: 0;
    background: linear-gradient(
        180deg,
        rgba(0,0,0,.02) 35%,
        rgba(0,0,0,.5) 100%
    );
}


/* =========================================================
   FECHA
========================================================= */

.blog-date{
    position: absolute;
    top: 18px;
    left: 18px;
    min-width: 55px;
    padding: 8px 10px;
    border-radius: 11px;
    background: rgba(255,255,255,.94);
    color: #111827;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,.12);
    backdrop-filter: blur(8px);
}

.blog-date span{
    display: block;
    font-size: 19px;
    line-height: 1;
    font-weight: 900;
}

.blog-date small{
    display: block;
    margin-top: 4px;
    color: var(--color-secundario);
    font-size: 8px;
    font-weight: 900;
    letter-spacing: .7px;
}


/* =========================================================
   FLECHA IMAGEN
========================================================= */

.blog-image-arrow{
    position: absolute;
    right: 18px;
    bottom: 18px;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--color-secundario);
    color: #fff;
    opacity: 0;
    transform: translateY(8px);
    transition: .35s ease;
}

.blog-card:hover .blog-image-arrow{
    opacity: 1;
    transform: translateY(0);
}


/* =========================================================
   CONTENIDO
========================================================= */

.blog-card-content{
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 25px 26px 23px;
}

.blog-category{
    display: inline-flex;
    align-items: center;
    align-self: flex-start;
    gap: 6px;
    margin-bottom: 13px;
    color: var(--color-secundario);
    font-size: 10px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .8px;
    text-decoration: none;
}

.blog-category i{
    font-size: 11px;
}

.blog-title{
    margin: 0 0 12px;
    font-size: 21px;
    line-height: 1.3;
    font-weight: 850;
}

.blog-title a{
    color: #172033;
    text-decoration: none;
    transition: .25s ease;
}

.blog-title a:hover{
    color: var(--color-secundario);
}

.blog-excerpt{
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 66px;
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.7;
}


/* =========================================================
   FOOTER CARD
========================================================= */

.blog-card-footer{
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-top: 20px;
    margin-top: auto;
}

.blog-read-more{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #172033;
    text-decoration: none;
    font-size: 12px;
    font-weight: 900;
    transition: .25s ease;
}

.blog-read-more i{
    color: var(--color-secundario);
    transition: .25s ease;
}

.blog-read-more:hover{
    color: var(--color-secundario);
}

.blog-read-more:hover i{
    transform: translateX(4px);
}

.blog-reading{
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #94a3b8;
    font-size: 10px;
}


/* =========================================================
   NAVEGACIÓN
========================================================= */

.blog-navigation{
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 45px;
}

.blog-nav{
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e2e8f0;
    border-radius: 50%;
    background: #fff;
    color: #475569;
    cursor: pointer;
    transition: .3s ease;
}

.blog-nav:hover{
    border-color: var(--color-secundario);
    background: var(--color-secundario);
    color: #fff;
    transform: translateY(-2px);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width:767px){

    .blog-premium{
        padding: 80px 0;
    }

    .blog-container{
        width: min(100% - 30px, 1380px);
    }

    .blog-heading{
        display: block;
        margin-bottom: 40px;
    }

    .blog-heading h2{
        font-size: 36px;
    }

    .blog-description{
        font-size: 14px;
    }

    .blog-heading-action{
        margin-top: 25px;
    }

    .blog-view-all{
        width: 100%;
        justify-content: center;
    }

    .blog-card-image{
        height: 230px;
    }

    .blog-card-content{
        padding: 22px;
    }

    .blog-title{
        font-size: 20px;
    }

}

@media(max-width:400px){

    .blog-heading h2{
        font-size: 31px;
    }

}

</style>

<script>

document.addEventListener('DOMContentLoaded', function () {

    if (typeof Swiper === 'undefined') {
        return;
    }

    new Swiper('.blogSwiper', {

        slidesPerView: 1,

        spaceBetween: 22,

        speed: 700,

        loop: {{ $blogs->count() > 3 ? 'true' : 'false' }},

        autoplay: {
            delay: 4500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },

        navigation: {
            nextEl: '.blog-next',
            prevEl: '.blog-prev'
        },

        breakpoints: {

            0: {
                slidesPerView: 1,
                spaceBetween: 18
            },

            600: {
                slidesPerView: 2,
                spaceBetween: 20
            },

            1000: {
                slidesPerView: 3,
                spaceBetween: 24
            }

        }

    });

});

</script>
