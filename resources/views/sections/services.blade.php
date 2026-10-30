@php
$tipoServicios = $config['seccion_servicios_tipo'] ?? 'opcion_1';
@endphp

<div class="services_section sec_ptb_50 clearfix {{ $tipoServicios === 'opcion_2' ? 'services_section--expandable' : '' }}">

    {{-- FONDO DECORATIVO CORPORATIVO (BLOBS ORGÁNICOS) --}}
    <div class="services_decor" aria-hidden="true">
        <span class="services_blob services_blob--1"></span>
        <span class="services_blob services_blob--2"></span>
        <span class="services_blob services_blob--3"></span>
    </div>

    <div class="container maxw_1430">

        @if(($config['seccion_servicios_mostrar_encabezado'] ?? 1) == 1)
        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_servicios_titulo'] ?? 'SERVICIOS' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_servicios_descripcion'] ?? 'Soluciones profesionales para
                potenciar tu empresa') !!}
            </p>
        </div>
        @endif

        @if($tipoServicios === 'opcion_2')
        <div class="service-expand-list" role="list">
            @foreach($services as $item)
            <article class="service-expand-card" role="listitem"
                style="--service-expand-cover: url('{{ asset($item->portada ?: 'assets/images/tienda_virtual/default.png') }}');">
                <div class="service-expand-overlay"></div>

                <span class="service-expand-collapsed-title" aria-hidden="true">{{ $item->nombre }}</span>

                <div class="service-expand-content">
                    <div class="service-expand-copy">
                        <p>{{ limpiarTextoPlano($item->descripcion_breve_portada ?: $item->descripcion, 180) }}</p>
                        <a href="{{ route('services.show', $item->slug) }}" class="service-expand-link">
                            Conocer servicio <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <button type="button" class="service-expand-toggle"
                    aria-label="Expandir {{ $item->nombre }}" aria-expanded="false">
                    <i class="fal fa-arrow-right service-expand-open-icon" aria-hidden="true"></i>
                    <i class="fal fa-times service-expand-close-icon" aria-hidden="true"></i>
                </button>
            </article>
            @endforeach
        </div>
        @else
        <div class="row">
            @foreach($services as $item)
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="service_card">
                    <div class="service_cover">
                        <img src="{{ asset($item->portada ?: 'assets/images/tienda_virtual/default.png') }}"
                            alt="{{ $item->nombre }}" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('assets/images/tienda_virtual/default.png') }}'">
                        <div class="service_cover_overlay"></div>
                    </div>

                    <div class="service_content">
                        <h3>{{ $item->nombre }}</h3>
                        <p>{{ limpiarTextoPlano($item->descripcion, 110) }}</p>
                        <a href="{{ route('services.show', $item->slug) }}" class="service_btn">
                            Conocer más
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@if($tipoServicios === 'opcion_2')
@push('scripts')
<script>
document.addEventListener('click', function (event) {
    const toggle = event.target.closest('.service-expand-toggle');
    if (!toggle) {
        if (!event.target.closest('.service-expand-card')) {
            document.querySelectorAll('.service-expand-card.is-expanded').forEach(function (card) {
                card.classList.remove('is-expanded');
                const cardToggle = card.querySelector('.service-expand-toggle');
                cardToggle.setAttribute('aria-expanded', 'false');
                cardToggle.setAttribute(
                    'aria-label',
                    'Expandir ' + card.querySelector('.service-expand-collapsed-title').textContent.trim()
                );
            });
        }
        return;
    }

    const card = toggle.closest('.service-expand-card');
    const isExpanded = card.classList.toggle('is-expanded');
    toggle.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
    toggle.setAttribute('aria-label', (isExpanded ? 'Contraer ' : 'Expandir ')
        + card.querySelector('.service-expand-collapsed-title').textContent.trim());

    if (isExpanded) {
        card.parentElement.querySelectorAll('.service-expand-card.is-expanded').forEach(function (otherCard) {
            if (otherCard === card) return;
            otherCard.classList.remove('is-expanded');
            const otherToggle = otherCard.querySelector('.service-expand-toggle');
            otherToggle.setAttribute('aria-expanded', 'false');
            otherToggle.setAttribute(
                'aria-label',
                'Expandir ' + otherCard.querySelector('.service-expand-collapsed-title').textContent.trim()
            );
        });
    }
});
</script>
@endpush
@endif