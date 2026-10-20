<div class="sec_ptb_50 clearfix">
    <div class="container">

        <div class="marcas_row">

            <!-- IZQUIERDA: título + descripción -->
            @if(($config['seccion_marcas_mostrar_encabezado'] ?? 1) == 1)
            <div class="marcas_texto">
                <div class="section_heading_title">
                    <span></span>
                    <small>{{ $config['seccion_marcas_titulo'] ?? 'MARCAS' }}</small>
                    <span></span>
                </div>
                <p class="section_heading_description">
                    {!! limpiarTextoEditor($config['seccion_marcas_descripcion'] ?? 'Conoce las marcas que forman parte de nuestro catálogo y descubre productos de calidad respaldados por los mejores fabricantes.') !!}
                </p>
            </div>
            @endif

            <!-- Carrusel de marcas -->
            <div class="marcas_carousel_wrap">
                <button type="button" class="marcas_arrow marcas_prev" aria-label="Marca anterior">
                    <i class="fal fa-angle-left" aria-hidden="true"></i>
                </button>

                <div class="marcas_marquee">
                    <div class="marcas_carousel">
                        @foreach($marcas as $marca)
                        <div class="marcas_item">
                            <img src="{{ imagenOrDefault($marca->logo) }}" alt="{{ $marca->nombre }}">
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="marcas_arrow marcas_next" aria-label="Marca siguiente">
                    <i class="fal fa-angle-right" aria-hidden="true"></i>
                </button>
            </div>

        </div>

    </div>
</div>