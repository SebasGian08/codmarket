@extends('layouts.appweb')

@section('title', 'Portafolio - $empresa->nombre ?? 'Mi Empresa')

@section('content')

<section class="idx-proy-section">
    <div class="idx-wrap">

        <!-- HEADER -->
        <div class="sec-title_two centered">
            <div class="sec-title_two-title">~ Portafolio ~</div>
            <h2 class="sec-title_three-heading" style="color: #fff;">
                Proyectos desarrollados para <span>clientes y empresas</span>
            </h2>
        </div>

        <!-- GRID -->
        <div class="idx-proy-grid">

            @forelse($portafolios as $portafolio)

            <a href="{{ $portafolio->url_demo ?? '#' }}" class="idx-proy-card">

                <div class="idx-proy-img">
                    @if($portafolio->imagen)
                        <img src="{{ asset('storage/'.$portafolio->imagen) }}"
                             alt="{{ $portafolio->titulo }}"
                             loading="lazy">
                    @else
                        <div class="idx-proy-noimg">
                            <i class="fas fa-code"></i>
                        </div>
                    @endif
                </div>

                <div class="idx-proy-body">

                    <span class="idx-proy-cat">
                        {{ ucfirst($portafolio->tipo) }}
                    </span>

                    <div class="idx-proy-title">
                        {{ $portafolio->titulo }}
                    </div>

                    @if($portafolio->cliente)
                    <div class="idx-proy-client">
                        <i class="fas fa-building"></i>
                        {{ $portafolio->cliente }}
                    </div>
                    @endif

                    <div class="idx-proy-desc">
                        {{ Str::limit($portafolio->descripcion, 120) }}
                    </div>

                    <span class="idx-proy-link">
                        Ver proyecto <i class="fas fa-arrow-right"></i>
                    </span>

                </div>

            </a>

            @empty

            <div style="color:white; text-align:center; width:100%;">
                No hay portafolios registrados aún.
            </div>

            @endforelse

        </div>

        <!-- PAGINACIÓN -->
        @if($portafolios->hasPages())
        <div class="pagination-wrapper" style="margin-top: 40px; text-align:center;">
            {{ $portafolios->links() }}
        </div>
        @endif

    </div>
</section>

@endsection