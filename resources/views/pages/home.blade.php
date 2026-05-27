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
@endphp


@include('sections.banner')

@if($mostrarCategorias == 1)
    @include('sections.categorias')
@endif

@if($mostrarMarcas == 1)
    @include('sections.marcas')
@endif

@if($mostrarProductos == 1)
    @include('sections.products')
@endif

@if($mostrarPromociones == 1)
    @include('sections.promociones')
@endif

@if($mostrarProductosDestacados == 1)
    @include('sections.products_destacados')
@endif

@if($mostrarServicios == 1)
    @include('sections.services')
@endif

@if($mostrarSteps == 1)
    @include('sections.steps')
@endif

@if($mostrarSuscripcion == 1)
    @include('sections.suscripcion')
@endif

@if($mostrarPreguntas == 1)
    @include('sections.preguntas-frecuentes')
@endif

@if($mostrarBlogs == 1)
    @include('sections.blog')
@endif

@endsection