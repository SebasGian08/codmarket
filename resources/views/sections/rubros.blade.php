@if(isset($rubros) && $rubros->count())

<div class="rubros_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="section_heading rubros_heading text-center mb_40">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_rubros_titulo'] ?? 'RUBROS' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description rubros_descripcion">
                {!! limpiarTextoEditor($config['seccion_rubros_descripcion'] ?? 'Descubre la variedad de rubros que tenemos para ofrecerte los mejores productos y servicios del mercado') !!}
            </p>
        </div>

        <div class="row justify-content-center">

            @foreach($rubros as $rubro)

            @php
                $imagenRubro = !empty($rubro->imagen)
                    ? asset($rubro->imagen)
                    : asset('assets/images/default.png');
            @endphp

            <div class="col-lg-4 col-md-4 col-6 mb-3">

                <a href="" class="rubro_card_premium" style="--i: {{ $loop->index }}">

                    <div class="rubro_img">

                         <img src="{{ $imagenRubro }}" alt="{{ $rubro->nombre }}">

                        <div class="rubro_gradient"></div>

                        <div class="rubro_desc">
                            {{ Str::limit($rubro->descripcion ?? 'Explora nuestros productos en esta categoría', 70) }}
                        </div>

                        <div class="rubro_footer">
                            <span class="rubro_badge">{{ $rubro->nombre }}</span>
                            <span class="rubro_ver_mas">
                                Ver más <i class="fal fa-arrow-right"></i>
                            </span>
                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

    </div>
</div>

@endif
