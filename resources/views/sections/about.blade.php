@php
$tipoNosotros = $config['seccion_nosotros_tipo'] ?? 'opcion_1';
@endphp

@if($tipoNosotros === 'opcion_2')
<section class="about-hero about-hero--editorial">
    <div class="about-editorial-container">
        <div class="about-editorial-content">
            <span class="about-editorial-label">
                Sobre Nosotros
                <i class="bi bi-arrow-return-right" aria-hidden="true"></i>
            </span>

            <h1>
                Impulsa tu <strong>empresa</strong> con estrategia y tecnología
            </h1>

            <p class="about-editorial-description">
                {!! $empresa->descripcion_empresarial ?: ($empresa->descripcion ??
                    'Somos una empresa comprometida con brindar soluciones profesionales de alta calidad, enfocadas en generar valor y resultados sostenibles para nuestros clientes.') !!}
            </p>

            <div class="about-editorial-tabs" role="tablist" aria-label="Conoce nuestra empresa">
                <button type="button" class="about-editorial-tab is-active" id="aboutMissionTab"
                    role="tab" aria-selected="true" aria-controls="aboutMissionPanel" data-about-tab="mission">
                    Nuestra Misión
                </button>
                <button type="button" class="about-editorial-tab" id="aboutVisionTab"
                    role="tab" aria-selected="false" aria-controls="aboutVisionPanel" data-about-tab="vision">
                    Nuestra Visión
                </button>
            </div>

            <div class="about-editorial-panel is-active" id="aboutMissionPanel" role="tabpanel"
                aria-labelledby="aboutMissionTab" data-about-panel="mission">
                {!! $empresa->mision_empresarial ??
                    'Brindar soluciones especializadas y eficientes que contribuyan al desarrollo y crecimiento sostenible de nuestros clientes.' !!}
            </div>
            <div class="about-editorial-panel" id="aboutVisionPanel" role="tabpanel"
                aria-labelledby="aboutVisionTab" data-about-panel="vision" hidden>
                {!! $empresa->vision_empresarial ??
                    'Ser reconocidos como una empresa referente por nuestra excelencia profesional, innovación y compromiso con nuestros clientes.' !!}
            </div>

            <a href="{{ route('nosotros') }}" class="about-editorial-link">
                Conoce más <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="about-editorial-visual">
            <div class="about-editorial-ring" aria-hidden="true"></div>
            <div class="about-editorial-orbit" aria-hidden="true"></div>
            <div class="about-editorial-image">
                @if(!empty($empresa->imagen_empresarial))
                <img src="{{ asset($empresa->imagen_empresarial) }}" alt="Equipo de {{ $empresa->nombre ?? 'la empresa' }}">
                @else
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1200&auto=format&fit=crop"
                    alt="Equipo profesional">
                @endif
            </div>

            <div class="about-editorial-experience">
                <i class="bi bi-award" aria-hidden="true"></i>
                <strong>{{ $empresa->indicador_1_valor ?? '+7' }}</strong>
                <span>{!! nl2br(e($empresa->indicador_1_titulo ?? 'Años de experiencia')) !!}</span>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('click', function (event) {
    const tab = event.target.closest('.about-editorial-tab');
    if (!tab) return;

    const section = tab.closest('.about-hero--editorial');
    section.querySelectorAll('.about-editorial-tab').forEach(function (item) {
        const isActive = item === tab;
        item.classList.toggle('is-active', isActive);
        item.setAttribute('aria-selected', isActive ? 'true' : 'false');
        item.tabIndex = isActive ? 0 : -1;
    });

    section.querySelectorAll('.about-editorial-panel').forEach(function (panel) {
        const isActive = panel.dataset.aboutPanel === tab.dataset.aboutTab;
        panel.classList.toggle('is-active', isActive);
        panel.hidden = !isActive;
    });
});
</script>
@endpush
@else
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
                {!! $empresa->descripcion ?? 'Somos una empresa comprometida con brindar soluciones profesionales de alta calidad, enfocadas en generar valor y resultados sostenibles para nuestros clientes.' !!}
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
                <strong>{{ $empresa->indicador_1_valor ?? '+7' }}</strong>
                <span>{!! nl2br(e($empresa->indicador_1_titulo ?? 'Años de experiencia')) !!}</span>
            </div>
        </div>
    </div>
</section>
@endif
