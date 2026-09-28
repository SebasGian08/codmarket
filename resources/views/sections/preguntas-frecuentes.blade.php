@if($preguntas->count() > 0)
<div class="faq-section">
    <div class="faq-container">

        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span class="faq-badge">{{ $config['seccion_preguntas_titulo'] ?? 'PREGUNTAS FRECUENTES' }}</span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_preguntas_descripcion'] ?? 'Resolvemos las dudas más comunes sobre nuestros servicios.') !!}
            </p>
        </div>

        <div class="faq-list">
            @foreach($preguntas as $item)
            <div class="faq-item {{ $loop->first ? 'active' : '' }}">
                <button class="faq-question" onclick="toggleItem(this)" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                    <span>{{ $item->pregunta }}</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>

                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        {!! limpiarTextoEditor($item->respuesta) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

<script>
function toggleItem(button) {
    const item = button.parentElement;
    const isActive = item.classList.contains("active");
    
    // Cierra opcionalmente otros items si deseas acordeón único (eliminar si prefieres múltiples abiertos)
    document.querySelectorAll('.faq-item.active').forEach(openItem => {
        if (openItem !== item) {
            openItem.classList.remove('active');
            openItem.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
        }
    });

    item.classList.toggle("active");
    button.setAttribute('aria-expanded', !isActive);
}
</script>
@endif