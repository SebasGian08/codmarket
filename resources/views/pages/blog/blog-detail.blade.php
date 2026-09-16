@extends('layouts.appweb')

@section('title', $blog->title)

@section('content')

@php
$defaultBlogImage = asset('assets/images/tienda_virtual/default.png');
$blogImage = imagenOrDefault($blog->image);
@endphp

<section class="details_section blog_details sec_ptb_140 clearfix">
    <div class="container">

        <div class="blog_detail_intro">
            <a class="blog_back_link" href="{{ route('blog.index') }}">
                <i class="fal fa-long-arrow-left"></i>
                Volver al blog
            </a>
            <span class="blog_detail_kicker">Conocimiento para tu negocio</span>
        </div>

        <div class="row justify-content-lg-between blog_detail_layout">

            {{-- =====================================================
            CONTENIDO PRINCIPAL
        ====================================================== --}}
            <div class="col-lg-9 col-md-12">

                {{-- HERO / IMAGEN --}}
                <div class="details_image mb_30">
                    <img src="{{ $blogImage }}" alt="{{ $blog->title }}" loading="eager"
                        onerror="this.onerror=null;this.src='{{ $defaultBlogImage }}';">
                    <span class="blog_image_badge">Artículo</span>
                </div>

                {{-- INFORMACIÓN DEL ARTÍCULO --}}
                <div class="blog_article_meta mb_20">
                    <ul class="post_meta ul_li clearfix">

                        <li>
                            <i class="fal fa-calendar-alt"></i>
                            {{ $blog->created_at->format('d M Y') }}
                        </li>

                        <li>
                            <i class="fal fa-folder-open"></i>
                            {{ $blog->category->name ?? 'General' }}
                        </li>

                    </ul>
                </div>

                {{-- TÍTULO --}}
                <h1 class="item_title mb_25">
                    {{ $blog->title }}
                </h1>

                {{-- RESUMEN --}}
                @if($blog->excerpt)
                <p class="blog_excerpt mb_35">
                    {{ $blog->excerpt }}
                </p>
                @endif

                {{-- CONTENIDO --}}
                <div class="blog_content mb_40">
                    {!! strip_tags(
                    $blog->content,
                    '<p><strong><b><em><i><br>
                                        <ul>
                                            <ol>
                                                <li>
                                                    <h1>
                                                        <h2>
                                                            <h3>
                                                                <h4>
                                                                    <h5>
                                                                        <blockquote><a>'
                                                                                ) !!}
                </div>

                {{-- CATEGORÍA --}}
                @if($blog->category)
                <div class="blog_category mb_35">
                    <a class="category_link" href="{{ route('blog.index', ['category' => $blog->category->id]) }}">
                        {{ $blog->category->name }}
                    </a>
                </div>
                @endif

                {{-- =====================================================
                COMPARTIR
            ====================================================== --}}
                @php
                $shareUrl = urlencode(Request::url());
                $shareTitle = urlencode($blog->title);
                @endphp

                <div class="post_share_box">

                    <div class="share_content">

                        <div class="share_text">
                            <span class="share_label">
                                Compartir artículo
                            </span>

                            <h3>
                                Comparte este contenido
                            </h3>

                            <p>
                                Ayuda a que más personas conozcan esta información.
                            </p>
                        </div>

                        <ul class="circle_social_links">

                            {{-- FACEBOOK --}}
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                                    rel="noopener noreferrer" class="facebook" aria-label="Compartir en Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>

                            {{-- X --}}
                            <li>
                                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                                    target="_blank" rel="noopener noreferrer" class="twitter"
                                    aria-label="Compartir en X">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>

                            {{-- LINKEDIN --}}
                            <li>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
                                    target="_blank" rel="noopener noreferrer" class="linkedin"
                                    aria-label="Compartir en LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>

                            {{-- WHATSAPP --}}
                            <li>
                                <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank"
                                    rel="noopener noreferrer" class="whatsapp" aria-label="Compartir por WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

                {{-- =====================================================
                ARTÍCULOS RELACIONADOS
            ====================================================== --}}
                @if($relatedBlogs->count())

                <div class="related_post_carousel position-relative mb_100">

                    <div class="section_heading mb_35">
                        <span class="section_subtitle">
                            También te puede interesar
                        </span>

                        <h2 class="title_text">
                            Artículos relacionados
                        </h2>
                    </div>

                    <div class="slideshow3_slider"
                        data-slick='{"dots": false, "arrows": true, "slidesToShow": 2, "slidesToScroll": 1}'>

                        @foreach($relatedBlogs as $item)

                        <div class="item">

                            <article class="blog_grid">

                                <a class="blog_image" href="{{ route('blog.show', $item->slug) }}"
                                    aria-label="{{ $item->title }}">
                                    <img src="{{ imagenOrDefault($item->image) }}" alt="{{ $item->title }}" loading="lazy"
                                        onerror="this.onerror=null;this.src='{{ $defaultBlogImage }}';">
                                </a>

                                    <div class="blog_content">

                                    <ul class="post_meta ul_li clearfix">
                                        <li>
                                            {{ $item->created_at->format('d M Y') }}
                                        </li>

                                        @if($item->category)
                                        <li>
                                            <a href="{{ route('blog.index', ['category' => $item->category->id_blogs_categories]) }}">
                                                {{ $item->category->name }}
                                            </a>
                                        </li>
                                        @endif
                                    </ul>

                                    <h3 class="blog_title">
                                        <a href="{{ route('blog.show', $item->slug) }}">
                                            {{ $item->title }}
                                        </a>
                                    </h3>

                                    <a class="text_btn" href="{{ route('blog.show', $item->slug) }}">
                                        <span>Leer artículo</span>
                                        <i class="fal fa-long-arrow-right"></i>
                                    </a>

                                </div>

                            </article>

                        </div>

                        @endforeach

                    </div>

                </div>

                @endif

            </div>


            {{-- =====================================================
            SIDEBAR
        ====================================================== --}}
            <div class="col-lg-3 col-md-12">

                <aside class="sidebar_section clearfix">

                    {{-- BUSCADOR --}}
                    <div class="sb_widget sb_search">

                        <h3 class="sb_widget_title">
                            Buscar contenido
                        </h3>

                        <form method="GET" action="{{ route('blog.index') }}">
                            <div class="form_item mb-0">

                                <input type="search" name="q" placeholder="Buscar artículo..."
                                    value="{{ request('q') }}" aria-label="Buscar artículo">

                                <button type="submit" class="submit_btn" aria-label="Buscar">
                                    <i class="fal fa-search"></i>
                                </button>

                            </div>
                        </form>

                    </div>


                    {{-- ARTÍCULOS RECIENTES --}}
                    @if($recentBlogs->count())

                    <div class="sb_widget sb_recent_post">

                        <h3 class="sb_widget_title">
                            Artículos recientes
                        </h3>

                        @foreach($recentBlogs->take(4) as $item)

                        <article class="small_blog">

                            <a href="{{ route('blog.show', $item->slug) }}" class="item_image">
                                <img src="{{ imagenOrDefault($item->image) }}" alt="{{ $item->title }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ $defaultBlogImage }}';">
                            </a>

                            <div class="item_content">

                                <span class="post_date">
                                    {{ $item->created_at->format('d M Y') }}
                                </span>

                                <h3 class="item_title">
                                    <a href="{{ route('blog.show', $item->slug) }}">
                                        {{ Str::limit($item->title, 55) }}
                                    </a>
                                </h3>

                            </div>

                        </article>

                        @endforeach

                    </div>

                    @endif


                    {{-- CATEGORÍA --}}
                    @if($blog->category)

                    <div class="sb_widget sb_category">

                        <h3 class="sb_widget_title">
                            Categoría
                        </h3>

                        <ul class="ul_li_block clearfix">

                            <li>
                                <a href="{{ route('blog.index', ['category' => $blog->category->id]) }}">
                                    <span>
                                        {{ $blog->category->name }}
                                    </span>

                                    <i class="fal fa-long-arrow-right"></i>
                                </a>
                            </li>

                        </ul>

                    </div>

                    @endif


                    {{-- CTA CORPORATIVO --}}
                    <div class="sidebar_cta">

                        <span class="cta_label">
                            ¿Necesitas más información?
                        </span>

                        <h3>
                            Conversemos sobre tu necesidad
                        </h3>

                        <p>
                            Nuestro equipo puede ayudarte a encontrar
                            una solución adecuada para tu empresa.
                        </p>

                        <a href="#contact" class="btn_text">
                            Contáctanos
                            <i class="fal fa-long-arrow-right"></i>
                        </a>

                    </div>

                </aside>

            </div>

        </div>

    </div>


</section>

{{-- =====================================================
CONTACTO
====================================================== --}}

<div id="contact">
    @include('sections.contact')
</div>

@endsection
