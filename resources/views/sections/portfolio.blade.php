<section class="case-two">

    <div class="inner-container">
        <div class="sec-title_three centered">
            <div class="sec-title_three-title" style="color: white;">PORTAFOLIO DE TRABAJOS</div>
            <h2 class="sec-title_three-heading" style="color: white;">
                Últimos proyectos realizados por nuestro equipo de <br>Diseño y Desarrollo.
            </h2>
        </div>

        <div class="row clearfix">

            @php
                $proyectos = [
                    ['url' => 'https://ingemafereirl.com/', 'img' => 'project-12.webp'],
                    ['url' => 'https://acnetworkperu.com/', 'img' => 'project-10.webp'],
                    ['url' => 'https://metal-spool.com/', 'img' => 'project-11.webp'],
                    ['url' => 'https://trazzosystilos.com/', 'img' => 'project-9.webp'],
                    ['url' => 'https://implantesdentalestacna.com/', 'img' => 'project-13.webp'],
                    ['url' => 'https://www.detcotech.com/', 'img' => 'project-14.webp'],
                    ['url' => '#', 'img' => 'project-15.webp'],
                    ['url' => 'https://trofeosygrabadosjmv.com/', 'img' => 'project-16.webp'],
                    ['url' => 'https://www.tejasypasteleros.com/', 'img' => 'project-17.webp'],
                    ['url' => 'http://www.albaseguridadprivada.com/', 'img' => 'project-24.webp'],
                    ['url' => 'http://www.lawticca.com/', 'img' => 'project-25.webp'],
                    ['url' => 'http://www.sunquy.pe/', 'img' => 'project-26.webp'],
                ];
            @endphp

            @foreach($proyectos as $proyecto)
                <div class="service-block_six col-lg-3 col-md-6 col-sm-6">
                    <div class="case-block-two">
                        <div class="case-block_two-inner">
                            <div class="image-container">
                                <a href="{{ $proyecto['url'] }}" target="_blank">
                                    <img src="{{ asset('assets/images/gallery/' . $proyecto['img']) }}"
                                         alt="proyecto"
                                         class="zoom-image" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <!-- Button Box -->
    <div class="projects-one_button-box text-center">
        <a class="btn-style-one theme-btn btn-item"
           href="{{ url('portafolio') }}"
           style="background-color: black !important">
            <div class="btn-wrap">
                <span class="text-one">Ver más proyectos</span>
                <span class="text-two">Ver más proyectos</span>
            </div>
        </a>
    </div>

</section>