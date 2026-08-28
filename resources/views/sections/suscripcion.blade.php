<section class="newsletter_section ecommerce_newsletter sec_ptb_50 clearfix">
    <div class="container">
        <div class="form_wrap text-center">

            <form method="POST" action="{{ route('subscribe.store') }}" class="newsletter_form">
                @csrf

                <!-- Honeypot -->
                <input type="text" name="website" style="display:none">

                <div class="section_heading_title mb_30">
                    <span></span>
                    <small style="color: rgba(255,255,255,0.85); font-size: 14px; letter-spacing: 3px;">{{ $config['seccion_suscripcion_titulo'] ?? 'BENEFICIOS EXCLUSIVOS' }}</small>
                    <span></span>
                </div>

                <h2 style="color: #fff; font-weight: 600; font-size: 28px; max-width: 600px; margin: 0 auto 30px; line-height: 1.4;">
                    {{ $config['seccion_suscripcion_descripcion'] ?? 'Hasta 35% de descuento y ofertas exclusivas en tu correo.' }}
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

