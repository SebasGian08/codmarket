@extends('layouts.appweb')

@section('title', $empresa->nombre ?? 'Mi Empresa')

@section('content')

@php
    $mostrarCategorias = $config['home_mostrar_categorias'] ?? 1;
    $mostrarBlogs = $config['home_mostrar_blogs'] ?? 1;
    $mostrarMarcas = $config['home_mostrar_marcas'] ?? 1;
@endphp


{{-- Hero / Banner --}}
@include('sections.banner')


@if($mostrarCategorias == 1)
    @include('sections.categorias')
@endif

@if($mostrarMarcas == 1)
    @include('sections.marcas')
@endif

{{-- Productos --}}
@include('sections.products ')


@include('sections.steps')

{{-- Suscripcion --}}
@include('sections.suscripcion')

@if($mostrarBlogs == 1)
    @include('sections.blog')
@endif

@endsection