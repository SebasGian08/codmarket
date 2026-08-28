<div class="barnd_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="marcas_row">

            <!-- IZQUIERDA: título + descripción -->
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

            <!-- DERECHA: logos marquee -->
            <div class="marcas_marquee">
                <div class="marcas_track">
                    @foreach($marcas as $marca)
                    <div class="marcas_item">
                        <img src="{{ imagenOrDefault($marca->logo) }}" alt="{{ $marca->nombre }}">
                    </div>
                    @endforeach
                    @foreach($marcas as $marca)
                    <div class="marcas_item">
                        <img src="{{ imagenOrDefault($marca->logo) }}" alt="{{ $marca->nombre }}">
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</div>