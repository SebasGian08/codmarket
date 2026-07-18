<div class="barnd_section sec_ptb_50 clearfix">
    <div class="container">

        <!-- TEXTO / TÍTULO -->
        <div class="section_heading text-center mb-5">

            <div class="section_heading_title">
                <span></span>
                <small>MARCAS</small>
                <span></span>
            </div>

            <p class="section_heading_description">
                Conoce las marcas que forman parte de nuestro catálogo y descubre productos de calidad respaldados por
                los mejores fabricantes.
            </p>

        </div>

        <div class="barnd_carousel clearfix">

            @foreach($marcas as $marca)
            <div class="item">
                <a class="brand_item" target="_blank">
                    <img src="{{ asset($marca->logo) }}" alt="{{ $marca->nombre }}">
                </a>
            </div>
            @endforeach

        </div>
    </div>
</div>