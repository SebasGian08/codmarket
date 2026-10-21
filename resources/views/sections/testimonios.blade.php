@if($trabajosRealizados->count())

@php
    $casosExitoTipo = $config['casos_exito_tipo'] ?? 'opcion_1';
@endphp

<section class="sec_ptb_50 clearfix {{ $casosExitoTipo === 'opcion_1' ? 'success_cases_testimonial' : '' }}">

    <div class="container maxw_1460">

        @if(($config['seccion_testimonios_mostrar_encabezado'] ?? 1) == 1)
        @if($casosExitoTipo === 'opcion_1')
        <div class="casos_testimonial_header">
            <div class="section_heading_title casos_testimonial_heading">
                <span></span>
                <small>{{ $config['seccion_testimonios_titulo'] ?? 'CASOS DE ÉXITO' }}</small>
                <span></span>
            </div>
        </div>
        @else
        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_testimonios_titulo'] ?? 'CASOS DE ÉXITO' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_testimonios_descripcion'] ?? 'Algunos de nuestros proyectos realizados.') !!}
            </p>
        </div>
        @endif
        @endif

        @if($casosExitoTipo === 'opcion_1')

            <div class="casos_testimonial_slider">
                @foreach($trabajosRealizados as $trabajo)
                <div class="casos_testimonial_slide">
                    <article class="casos_testimonial_card">
                        <span class="casos_testimonial_quote" aria-hidden="true">&rdquo;</span>
                        <h3>{{ $trabajo->titulo }}</h3>
                        @if($trabajo->descripcion)
                        <p>{{ limpiarTextoPlano($trabajo->descripcion, 180) }}</p>
                        @endif
                        @if($trabajo->cliente)
                        <strong>{{ $trabajo->cliente }}</strong>
                        @endif
                    </article>
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
                                {{ limpiarTextoPlano($trabajo->descripcion, 100) }}
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
