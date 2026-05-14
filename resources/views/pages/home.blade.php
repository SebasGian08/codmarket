@extends('layouts.appweb')

@section('title', $empresa->nombre ?? 'Mi Empresa')

@section('content')

{{-- Hero / Banner --}}
@include('sections.banner')


{{-- categorias --}}
@include('sections.categorias')

{{-- Productos --}}
@include('sections.products ')


@include('sections.steps')

{{-- Suscripcion --}}
@include('sections.suscripcion')

@include('sections.blog')

@endsection