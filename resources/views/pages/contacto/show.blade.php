@extends('layouts.appweb')

@section('title', $empresa->nombre ?? 'Mi Empresa')

@section('content')

    @include('sections.contact')

    @php
        $mostrarSteps = $config['home_mostrar_steps'] ?? 1;
    @endphp

    @if($mostrarSteps)
        @include('sections.steps')
    @endif
    
@endsection