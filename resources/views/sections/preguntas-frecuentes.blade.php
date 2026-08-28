@if($preguntas->count() > 0)
<div class="faq-section">
    <div class="faq-container">

        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_preguntas_titulo'] ?? 'PREGUNTAS' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_preguntas_descripcion'] ?? 'Resolvemos las dudas más comunes sobre nuestros servicios.') !!}
            </p>
        </div>

        @foreach($preguntas as $item)
        <div class="faq-item {{ $loop->first ? 'active' : '' }}">
            <button class="faq-question" onclick="toggleItem(this)">
                <span>{{ $item->pregunta }}</span>
                <div class="faq-icon"></div>
            </button>

            <div class="faq-answer">
                <p>{!! limpiarTextoEditor($item->respuesta) !!}</p>
            </div>
        </div>
        @endforeach

    </div>
</div>

<script>
function toggleItem(button) {
    const item = button.parentElement;
    item.classList.toggle("active");
}
</script>
@endif