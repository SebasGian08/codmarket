@if(isset($rubros) && $rubros->count())

<div class="rubros_section sec_ptb_50 clearfix">
    <div class="container maxw_1430">

        <div class="section_title text-center mb-5">
            <h2>Nuestros Rubros</h2>
            <p>Explora nuestras categorías principales</p>
            <div class="title_line"></div>
        </div>

        <div class="row">

            @foreach($rubros as $rubro)

            @php
                $imagenRubro = !empty($rubro->imagen)
                    ? asset($rubro->imagen)
                    : asset('assets/images/default.png');
            @endphp

            <div class="col-lg-3 col-md-4 col-6 mb-4">

                <a href="#" class="category_grid_card">

                    <div class="category_grid_image">

                        <img src="{{ $imagenRubro }}" alt="{{ $rubro->nombre }}">

                    </div>

                    <div class="category_grid_content text-center">

                        <h5>{{ $rubro->nombre }}</h5>

                        <p class="small text-muted mb-0">
                            {{ \Illuminate\Support\Str::limit($rubro->descripcion ?? 'Explora esta categoría', 60) }}
                        </p>

                    </div>

                </a>

            </div>

            @endforeach

        </div>

    </div>
</div>

@endif