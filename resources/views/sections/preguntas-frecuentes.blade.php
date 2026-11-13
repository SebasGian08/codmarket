@if($preguntas->count() > 0)
@php
$tipoPreguntas = $config['seccion_preguntas_tipo'] ?? 'opcion_1';
@endphp
<div class="faq-section {{ $tipoPreguntas === 'opcion_2' ? 'faq-section--columns' : '' }}">
    <div class="faq-container">

        @if(($config['seccion_preguntas_mostrar_encabezado'] ?? 1) == 1)
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
        @endif

        @if($tipoPreguntas === 'opcion_2')
        @php
        $preguntasPorColumna = $preguntas->split(2);
        $numeroPregunta = 0;
        @endphp
        <div class="faq-list faq-list--columns">
            @foreach($preguntasPorColumna as $columna)
            <div class="faq-column">
                @foreach($columna as $item)
                @php
                $numeroPregunta++;
                @endphp
                <div class="faq-item {{ $numeroPregunta === 1 ? 'active' : '' }}">
                    <button class="faq-question faq-question--numbered" onclick="toggleItem(this)" type="button">
                        <span class="faq-number">{{ 'P.' . str_pad($numeroPregunta, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="faq-question-text">{{ $item->pregunta }}</span>
                        <span class="faq-icon" aria-hidden="true"></span>
                    </button>

                    <div class="faq-answer">
                        <p>{!! limpiarTextoEditor($item->respuesta) !!}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @else
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
        @endif

    </div>
</div>

<script>
function toggleItem(button) {
    const item = button.parentElement;
    item.classList.toggle("active");
}
</script>
@endif