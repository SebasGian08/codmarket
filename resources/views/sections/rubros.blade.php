@if(isset($rubros) && $rubros->count())

<div class="rubros_section sec_ptb_50 clearfix">
    <div class="container maxw_1430">

        <div class="section_title text-center mb-5">
            <h2>Nuestros Rubros</h2>
            <p>Explora nuestras categorías principales</p>
            <div class="title_line"></div>
        </div>

        <div class="row g-4 justify-content-center">

            @foreach($rubros as $rubro)

            @php
                $imagenRubro = !empty($rubro->imagen)
                    ? asset($rubro->imagen)
                    : asset('assets/images/default.png');
            @endphp

            <div class="col-lg-3 col-md-4 col-6 mt-2">

                <a href="" class="rubro_card_premium">

                    <div class="rubro_img">

                         <img src="{{ $imagenRubro }}" alt="{{ $rubro->nombre }}">

                        <div class="rubro_gradient"></div>

                        <div class="rubro_badge">
                            {{ $rubro->nombre }}
                        </div>

                        {{-- DESCRIPCIÓN PEQUEÑA --}}
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
