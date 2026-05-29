<div class="services_section sec_ptb_100 clearfix">
    <div class="container maxw_1430">

        <div class="section_title text-center mb-5">
            <h2>Nuestros Servicios</h2>
            <p>
                Soluciones profesionales para potenciar tu empresa
            </p>
            <div class="title_line"></div>
        </div>

        <div class="row">

            @foreach($services as $item)

            <div class="col-lg-4 col-md-6 col-sm-6 mb-4">

                <div class="service_card text-center">

                    <!-- EFECTO BRILLO -->
                    <div class="glow_effect"></div>

                    <!-- IMAGEN -->
                    <div class="service_image">
                        <img 
                            src="{{ asset($item->portada) }}" 
                            alt="{{ $item->nombre }}"
                        >
                    </div>

                    <!-- CONTENIDO -->
                    <div class="service_content">

                        <h3>
                            {{ $item->nombre }}
                        </h3>

                        <p>
                            {{ Str::limit(strip_tags($item->descripcion), 100) }}
                        </p>

                        <a 
                            href="{{ route('services.show', $item->slug) }}" 
                            class="service_btn"
                        >
                            Ver más
                            <i class="fas fa-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>
</div>