@extends('layouts.appweb')

@section('title', $empresa->nombre ?? 'Mi Empresa')

@section('content')

@php
    $mostrarCategorias = $config['home_mostrar_categorias'] ?? 1;
    $mostrarBlogs = $config['home_mostrar_blogs'] ?? 1;
    $mostrarMarcas = $config['home_mostrar_marcas'] ?? 1;
    $mostrarServicios = $config['home_mostrar_servicios'] ?? 1;
    $mostrarPreguntas = $config['home_mostrar_preguntas'] ?? 1;
    $mostrarSuscripcion = $config['home_mostrar_suscripcion'] ?? 1;
    $mostrarSteps = $config['home_mostrar_steps'] ?? 1;
    $mostrarProductos = $config['home_mostrar_productos'] ?? 1;
    $mostrarBanner = $config['home_mostrar_banner'] ?? 1;
    $mostrarPromociones = $config['home_mostrar_promociones'] ?? 1;
    $mostrarProductosDestacados = $config['home_mostrar_productos_destacados'] ?? 1;
    $mostrarRubros = $config['home_mostrar_rubros'] ?? 1;
    $mostrarClientes = $config['home_mostrar_clientes'] ?? 0;
@endphp


<div class="scroll-reveal">{{-- Banner: fade scale suave --}}
    @include('sections.banner')
</div>

@if($mostrarRubros == 1)
    <div class="scroll-reveal reveal-scale">{{-- Rubros: escala --}}
        @include('sections.rubros')
    </div>
@endif

@if($mostrarCategorias == 1)
    <div class="scroll-reveal">{{-- Categorías: fade up --}}
        @include('sections.categorias')
    </div>
@endif

@if($mostrarMarcas == 1)
    <div class="scroll-reveal reveal-left">{{-- Marcas: deslizar desde izquierda --}}
        @include('sections.marcas')
    </div>
@endif

@if($mostrarClientes == 1)
    <div class="scroll-reveal reveal-right">{{-- Clientes: carrusel --}}
        @include('sections.clientes')
    </div>
@endif

@if($mostrarProductos == 1)
    <div class="scroll-reveal">{{-- Productos: fade up --}}
        @include('sections.products')
    </div>
@endif

@if($mostrarPromociones == 1)
    <div class="scroll-reveal reveal-scale">{{-- Promociones: escala --}}
        @include('sections.promociones')
    </div>
@endif

@if($mostrarProductosDestacados == 1)
    <div class="scroll-reveal reveal-left">{{-- Productos destacados: izquierda --}}
        @include('sections.products_destacados')
    </div>
@endif

@if($mostrarServicios == 1)
    <div class="scroll-reveal">{{-- Servicios: fade up --}}
        @include('sections.services')
    </div>
@endif

@if($mostrarSteps == 1)
    <div class="scroll-reveal reveal-right">{{-- Steps: derecha --}}
        @include('sections.steps')
    </div>
@endif

<div class="scroll-reveal">{{-- CTA Ayuda --}}
    @include('sections.cta-ayuda')
</div>

@if($mostrarSuscripcion == 1)
    <div class="scroll-reveal">{{-- Suscripción: fade up --}}
        @include('sections.suscripcion')
    </div>
@endif

<div class="scroll-reveal reveal-left">{{-- Testimonios: izquierda --}}
    @include('sections.testimonios')
</div>

@if($mostrarPreguntas == 1)
    <div class="scroll-reveal reveal-right">{{-- Preguntas: derecha --}}
        @include('sections.preguntas-frecuentes')
    </div>
@endif

@if($mostrarBlogs == 1)
    <div class="scroll-reveal">{{-- Blog: fade up --}}
        @include('sections.blog')
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reveals = document.querySelectorAll('.scroll-reveal');
    if (!reveals.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('reveal-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px'
    });

    reveals.forEach(el => observer.observe(el));
});
</script>
@endpush