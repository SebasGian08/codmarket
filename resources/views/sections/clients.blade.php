<section class="clients-one">
    <div class="auto-container">
        <div class="row clearfix">

            <!-- Title -->
            <div class="clients-one_title-column col-lg-4 col-md-12 col-sm-12">
                <div class="client-one_title">
                    ¡Únete a más de 200 clientes satisfechos!
                </div>
            </div>

            <!-- Carousel -->
            <div class="clients-one_carousel-column col-lg-8 col-md-12 col-sm-12">
                <ul class="sponsors-carousel-two owl-carousel owl-theme">

                    @for ($i = 5; $i <= 22; $i++)
                        <li class="slide-item">
                            <figure class="client-one_image-box">
                                <a href="#">
                                    <img src="{{ asset('assets/images/clients/' . $i . '.webp') }}" alt="logo">
                                </a>
                            </figure>
                        </li>
                    @endfor

                </ul>
            </div>

        </div>
    </div>
</section>