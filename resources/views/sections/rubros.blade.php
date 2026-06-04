@if(isset($rubros) && $rubros->count())

<div class="rubros_section sec_ptb_100 clearfix">
    <div class="container maxw_1430">

        <div class="section_title text-center mb-5">
            <h2>Nuestros Rubros</h2>
            <p>Explora nuestras categorías principales</p>
            <div class="title_line"></div>
        </div>

        <div class="row g-4 justify-content-center">

            @foreach($rubros as $rubro)

            <div class="col-lg-3 col-md-4 col-sm-6">

                <a href="" class="rubro_card_premium">

                    <div class="rubro_img">

                        <img src="{{ asset($rubro->imagen ?? 'assets/images/default.png') }}"
                            alt="{{ $rubro->nombre }}">

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
