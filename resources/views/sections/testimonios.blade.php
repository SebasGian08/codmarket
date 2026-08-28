@if($trabajosRealizados->count())

<section class="success_cases_section sec_ptb_50 clearfix">

    <div class="container maxw_1460">

        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_testimonios_titulo'] ?? 'CASOS DE ÉXITO' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {{ $config['seccion_testimonios_descripcion'] ?? 'Algunos de nuestros proyectos realizados.' }}
            </p>
        </div>

        @php
            $casosExitoTipo = $config['casos_exito_tipo'] ?? 'opcion_1';
        @endphp

        @if($casosExitoTipo === 'opcion_1')

            <div class="row align-items-center mb-4">

                <div class="col-lg-10"></div>

                <div class="col-lg-2">

                    <div class="carousel_nav align_right">

                        <button type="button" class="left_arrow_casos">
                            <i class="fal fa-arrow-left"></i>
                        </button>

                        <button type="button" class="right_arrow_casos">
                            <i class="fal fa-arrow-right"></i>
                        </button>

                    </div>

                </div>

            </div>

            <div class="casos_slider row clearfix"
                data-slick='{
                    "dots": false,
                    "arrows": true,
                    "infinite": true,
                    "autoplay": true,
                    "autoplaySpeed": 4000,
                    "slidesToShow": 3,
                    "slidesToScroll": 1,
                    "prevArrow": ".left_arrow_casos",
                    "nextArrow": ".right_arrow_casos",
                    "responsive": [
                        {
                            "breakpoint": 992,
                            "settings": {
                                "slidesToShow": 2
                            }
                        },
                        {
                            "breakpoint": 576,
                            "settings": {
                                "slidesToShow": 1
                            }
                        }
                    ]
                }'>

                @foreach($trabajosRealizados as $trabajo)

                <div class="item px-2">

                    <div class="success_case_card">

                        <div class="success_case_image">
                            <img src="{{ asset($trabajo->imagen) }}"
                                alt="{{ $trabajo->titulo }}">
                        </div>

                        <div class="success_case_content">

                            @if(!empty($trabajo->cliente))
                                <span class="case_client">
                                    {{ $trabajo->cliente }}
                                </span>
                            @endif

                            <h4>{{ $trabajo->titulo }}</h4>

                            <p>
                                {{ \Illuminate\Support\Str::limit(strip_tags($trabajo->descripcion), 100) }}
                            </p>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        @else

            <div class="row">

                @foreach($trabajosRealizados as $trabajo)

                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="success_case_card">

                        <div class="success_case_image">
                            <img src="{{ asset($trabajo->imagen) }}"
                                alt="{{ $trabajo->titulo }}">
                        </div>

                        <div class="success_case_content">

                            @if(!empty($trabajo->cliente))
                                <span class="case_client">
                                    {{ $trabajo->cliente }}
                                </span>
                            @endif

                            <h4>{{ $trabajo->titulo }}</h4>

                            <p>
                                {{ \Illuminate\Support\Str::limit(strip_tags($trabajo->descripcion), 100) }}
                            </p>

                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        @endif

    </div>

</section>

@endif
