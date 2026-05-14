<section class="newsletter_section ecommerce_newsletter sec_ptb_50 clearfix">
    <div class="container">
        <div class="form_wrap text-center">

            <form method="POST" action="{{ route('subscribe.store') }}" class="newsletter_form">
                @csrf

                <!-- Honeypot -->
                <input type="text" name="website" style="display:none">

                <span class="newsletter_subtitle">
                    BENEFICIOS EXCLUSIVOS
                </span>

                <h2>
                    Hasta 35% de descuento y ofertas exclusivas en tu correo.
                </h2>

                <div class="form_item">

                    <input 
                        type="email" 
                        name="email" 
                        placeholder="correo@ejemplo.com"
                        required
                    >

                    <button type="submit" class="bg_black">
                        Suscribirme
                    </button>

                </div>

                <!-- reCAPTCHA -->
                <div class="recaptcha_wrapper">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>

                <small>
                    No compartimos tu información con terceros.
                </small>

            </form>

        </div>
    </div>
</section>

