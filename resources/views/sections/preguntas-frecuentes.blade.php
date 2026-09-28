@if($preguntas->count() > 0)
<div class="faq-section">
    <div class="faq-container">

        <div class="faq-header">
            <span class="faq-badge">{{ $config['seccion_preguntas_titulo'] ?? 'PREGUNTAS' }}</span>
            <h2>Dudas Frecuentes</h2>
            <p>{!! limpiarTextoEditor($config['seccion_preguntas_descripcion'] ?? 'Resolvemos las dudas más comunes sobre nuestros servicios.') !!}</p>
        </div>

        <div class="faq-list">
            @foreach($preguntas as $item)
            <div class="faq-item {{ $loop->first ? 'active' : '' }}">
                <button class="faq-question" onclick="toggleItem(this)" type="button">
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
</div>

<script>
function toggleItem(button) {
    const item = button.parentElement;
    item.classList.toggle("active");
}
</script>
@endif