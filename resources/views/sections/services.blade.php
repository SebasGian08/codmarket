<div class="services_section sec_ptb_100 clearfix">
    <div class="container maxw_1430">

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

        <div class="row">

            @foreach($services as $item)

            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">

                <div class="service_card">

                    <!-- PORTADA ARRIBA -->
                    <div class="service_cover">
                        <img src="{{ asset($item->portada ?: 'assets/images/tienda_virtual/1200x600px.png') }}"
                            alt="{{ $item->nombre }}" loading="lazy"
                            onerror="this.onerror=null;this.src='{{ asset('assets/images/tienda_virtual/1200x600px.png') }}'">
                        <div class="service_cover_overlay"></div>
                    </div>

                    <!-- CONTENIDO -->
                    <div class="service_content">

                        <h3>
                            {{ $item->nombre }}
                        </h3>

                        <p>
                            {{ limpiarTextoPlano($item->descripcion, 110) }}
                        </p>

                        <a href="{{ route('services.show', $item->slug) }}" class="service_btn">
                            Conocer más
                            <i class="fas fa-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>
</div>