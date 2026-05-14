@extends('layouts.appweb')

@section('title', 'Nosotros')

@section('content')

<!-- HERO -->
<section class="nosotros-hero"
    style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
    url('{{ asset($empresa->portada_empresarial ?? '') }}') center/cover no-repeat;">

    <div style="padding:120px 20px; text-align:center; color:#fff;">
        <h1>Nosotros</h1>
        <p>Conoce nuestra historia, propósito y lo que nos impulsa cada día</p>
    </div>

</section>

<!-- QUIÉNES SOMOS -->
<section class="nosotros-section">

    <div class="nosotros-grid">

        <div class="nosotros-text">
            <h2>¿Quiénes somos?</h2>

            <p>
                {{ $empresa->descripcion_empresarial }}
            </p>

        </div>

        <div>
            <img src="{{ asset($empresa->imagen_empresarial) }}"
                 alt="Nosotros"
                 style="width:100%; border-radius:15px;">
        </div>

    </div>

    <!-- MVV -->
    <div class="mvv">

        <div class="card-mvv">
            <h3>Misión</h3>
            <p>{{ $empresa->mision_empresarial }}</p>
        </div>

        <div class="card-mvv">
            <h3>Visión</h3>
            <p>{{ $empresa->vision_empresarial }}</p>
        </div>

        <div class="card-mvv">
            <h3>Valores</h3>
            <p>{{ $empresa->valores_empresariales }}</p>
        </div>

    </div>

</section>

@endsection