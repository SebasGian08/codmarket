<section class="about-hero" style="background-image: url('{{ asset($empresa->portada_empresarial ?? '') }}');">
    <div class="about-hero-overlay"></div>

    <div class="about-hero-container">
        <div class="about-hero-content">
            <span class="about-label">
                <span></span>
                QUIÉNES SOMOS
            </span>

            <h1>
                Sobre <strong>Nosotros</strong>
            </h1>

            <div class="about-line"></div>

            <p>
                {!! $empresa->descripcion ??
                    'Somos una empresa comprometida con brindar soluciones profesionales de alta calidad, enfocadas en generar valor y resultados sostenibles para nuestros clientes.' !!}
            </p>

            <a href="{{ route('nosotros') }}" class="about-btn">
                Conoce más
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="about-hero-visual">
            <div class="visual-circle visual-circle-1"></div>
            <div class="visual-circle visual-circle-2"></div>

            <div class="visual-image">
                @if(!empty($empresa->imagen_empresarial))
                    <img src="{{ asset($empresa->imagen_empresarial) }}" alt="Equipo profesional">
                @else
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
                        alt="Equipo profesional">
                @endif
            </div>

            <div class="experience-badge">
                <strong>{{ $empresa->indicador_1_valor ?? '+10' }}</strong>
                <span>{!! nl2br(e($empresa->indicador_1_titulo ?? 'Años de experiencia')) !!}</span>
            </div>
        </div>
    </div>
</section>
