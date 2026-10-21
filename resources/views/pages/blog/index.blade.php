@extends('layouts.appweb')

@section('title', 'Novedades | ' . ($empresa->nombre ?? 'Mi Empresa'))

@section('content')

<main class="blog_archive_page">
    <section class="blog_archive_hero">
        <div class="container">
            <div class="blog_archive_hero_content">
                <span class="blog_archive_eyebrow">{{ $empresa->nombre ?? 'Novedades' }}</span>
                <h1>¡Conoce nuestras novedades{{ $empresa->nombre ? ' ' . $empresa->nombre : '' }}!</h1>
                <p>Encuentra artículos con contenido actualizado y novedades.</p>
            </div>
            <span class="blog_archive_hero_shape blog_archive_hero_shape_one" aria-hidden="true"></span>
            <span class="blog_archive_hero_shape blog_archive_hero_shape_two" aria-hidden="true"></span>
        </div>
    </section>

    <section class="blog_archive_content">
        <div class="container">
            <nav class="blog_archive_categories" aria-label="Filtrar novedades por categoría">
                <a class="{{ request()->filled('category') ? '' : 'active' }}" href="{{ route('blog.index') }}">
                    Todos
                </a>
                @foreach($blogCategories as $category)
                <a class="{{ (string) request('category') === (string) $category->id_blogs_categories ? 'active' : '' }}"
                    href="{{ route('blog.index', array_merge(request()->only('q'), ['category' => $category->id_blogs_categories]) ) }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </nav>

            @if($blogs->isNotEmpty())
            <div class="blog_archive_grid">
                @foreach($blogs as $blog)
                <article class="blog_archive_card">
                    <a class="blog_archive_card_image" href="{{ route('blog.show', $blog->slug) }}">
                        <img src="{{ imagenOrDefault($blog->image) }}" alt="{{ $blog->title }}" loading="lazy">
                        <span class="blog_archive_card_overlay"></span>
                        <span class="blog_archive_card_details">
                            @if($blog->category)
                            <span class="blog_archive_card_category">{{ $blog->category->name }}</span>
                            @endif
                            <span class="blog_archive_card_title">{{ $blog->title }}</span>
                            <time datetime="{{ $blog->created_at->toDateString() }}">
                                {{ $blog->created_at->translatedFormat('d \d\e F Y') }}
                            </time>
                        </span>
                    </a>
                </article>
                @endforeach
            </div>

            @if($blogs->hasPages())
            <nav class="blog_archive_pagination" aria-label="Paginación de novedades">
                {{ $blogs->links('pagination::bootstrap-4') }}
            </nav>
            @endif
            @else
            <div class="blog_archive_empty">
                <h2>No hay novedades para mostrar</h2>
                <p>Prueba con otra categoría o vuelve a ver todos los artículos.</p>
                @if(request()->filled('category') || request()->filled('q'))
                <a href="{{ route('blog.index') }}">Ver todas las novedades</a>
                @endif
            </div>
            @endif
        </div>
    </section>
</main>

@endsection
