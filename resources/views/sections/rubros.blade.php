@if(isset($rubros) && $rubros->count())

<div class="rubros_section sec_ptb_50 clearfix">
    <div class="container">

        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>RUBROS</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                Explora nuestras categorías principales
            </p>
        </div>

        <div class="row g-3 justify-content-center">

            @foreach($rubros as $rubro)

            @php
                $imagenRubro = !empty($rubro->imagen)
                    ? asset($rubro->imagen)
                    : asset('assets/images/default.png');
            @endphp

            <div class="col-lg-3 col-md-4 col-6">

                <a href="" class="rubro_card_premium">

                    <div class="rubro_img">

                         <img src="{{ $imagenRubro }}" alt="{{ $rubro->nombre }}">

                        <div class="rubro_gradient"></div>

                        <div class="rubro_badge">
                            {{ $rubro->nombre }}
                        </div>

                        <div class="rubro_desc">
                            {{ Str::limit($rubro->descripcion ?? 'Explora nuestros productos en esta categoría', 70) }}
                        </div>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

    </div>
</div>

@endif
