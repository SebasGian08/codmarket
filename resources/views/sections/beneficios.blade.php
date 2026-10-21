@php
    $beneficios = collect($empresa->empresa_beneficios ?? [])
        ->take(4)
        ->filter(fn ($beneficio) => !empty($beneficio['titulo'])
            || !empty($beneficio['descripcion'])
            || !empty($beneficio['imagen']))
        ->values();
@endphp

@if($beneficios->isNotEmpty())
<section class="empresa-beneficios-section">
    <div class="container">
        @foreach($beneficios as $beneficio)
        <article class="empresa-beneficio-item">
            <div class="empresa-beneficio-content">
                @if(!empty($beneficio['titulo']))
                <h2>{{ $beneficio['titulo'] }}</h2>
                @endif

                @if(!empty($beneficio['descripcion']))
                <p>{{ $beneficio['descripcion'] }}</p>
                @endif

                @if(!empty($beneficio['enlace']))
                <a class="empresa-beneficio-link" href="{{ $beneficio['enlace'] }}">
                    {{ $beneficio['texto_boton'] ?: 'Conócenos' }}
                </a>
                @endif
            </div>

            @if(!empty($beneficio['imagen']))
            <div class="empresa-beneficio-image">
                <img src="{{ asset($beneficio['imagen']) }}" alt="{{ $beneficio['titulo'] ?? 'Beneficio de la empresa' }}">
            </div>
            @endif
        </article>
        @endforeach
    </div>
</section>
@endif