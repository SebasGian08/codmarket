<div class="services_section sec_ptb_100 clearfix">
    <div class="container maxw_1430">

        <div class="section_title text-center mb-5">
            <h2>Nuestros Servicios</h2>
            <p>Soluciones profesionales para tu empresa</p>
            <div class="title_line"></div>
        </div>

        <div class="row">

            @foreach($services as $item)
            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                
                <div class="service_card text-center">

                    <div class="service_image">
                        <img 
                            src="{{ asset('storage/' . $item->imagen) }}" 
                            alt="{{ $item->titulo }}"
                        >
                    </div>

                    <div class="service_content">

                        <small>
                            {{ $item->subtitulo ?? 'Servicio Profesional' }}
                        </small>

                        <h3>
                            {{ $item->titulo }}
                        </h3>

                        <p>
                            {{ Str::limit(strip_tags($item->descripcion), 100) }}
                        </p>

                        <a 
                            href="{{ route('services.show', $item->slug) }}" 
                            class="service_btn"
                        >
                            Ver más
                        </a>

                    </div>

                </div>

            </div>
            @endforeach

        </div>

    </div>
</div>