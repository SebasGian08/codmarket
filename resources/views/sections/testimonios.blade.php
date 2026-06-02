@if($trabajosRealizados->count())

<section class="success_cases_section sec_ptb_50 clearfix">

    <div class="container maxw_1460">

        <div class="section_title text-center mb-5">
            <h4>Casos de Éxito</h4>
            <div class="title_line"></div>
            <p class="mt-3">
                Algunos de nuestros proyectos realizados.
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

<style>
.success_case_card{
    position:relative;
    overflow:hidden;
    border-radius:20px;
    height:420px;
    background:#000;
    box-shadow:0 15px 35px rgba(0,0,0,.08);
    transition:.4s;
}

.success_case_card:hover{
    transform:translateY(-10px);
    box-shadow:0 25px 50px rgba(0,0,0,.18);
}

.success_case_image{
    height:100%;
}

.success_case_image img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:all .7s ease;
}

.success_case_card:hover .success_case_image img{
    transform:scale(1.1);
}

.success_case_card::before{
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(
        to top,
        rgba(0,0,0,.95) 0%,
        rgba(0,0,0,.65) 35%,
        rgba(0,0,0,.15) 70%,
        transparent 100%
    );
    z-index:1;
}

.success_case_content{
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    padding:30px;
    z-index:2;
    color:#fff;
}

.case_client{
    display:inline-flex;
    align-items:center;
    padding:8px 16px;
    border-radius:50px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(10px);
    color:#fff;
    font-size:12px;
    font-weight:600;
    letter-spacing:.5px;
    margin-bottom:15px;
}

.success_case_content h4{
    color:#fff;
    font-size:24px;
    font-weight:700;
    margin-bottom:12px;
    line-height:1.3;
}

.success_case_content p{
    color:rgba(255,255,255,.85);
    font-size:14px;
    line-height:1.8;
    margin:0;
}

.carousel_nav button{
    width:50px;
    height:50px;
    border:none;
    border-radius:50%;
    background:#fff;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
    margin-left:10px;
}

.carousel_nav button:hover{
    background:#0d6efd;
    color:#fff;
}

@media (max-width: 768px){

    .success_case_card{
        height:350px;
    }

    .success_case_content{
        padding:20px;
    }

    .success_case_content h4{
        font-size:20px;
    }

}
</style>