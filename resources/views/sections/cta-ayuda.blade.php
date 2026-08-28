@php
$telefono = preg_replace('/[^0-9]/', '', $empresa->telefono ?? '');
$mensaje = urlencode('Hola, quisiera recibir ayuda y más información sobre sus productos.');
@endphp

<section class="cta_ayuda_section clearfix">
    <div class="container">
        <div class="cta_ayuda_wrap">

            <div class="cta_ayuda_icon">
                <i class="fab fa-whatsapp"></i>
            </div>

            <div class="cta_ayuda_content">
                <h2>Nuestros expertos estarán encantados de ayudarte</h2>
                <p>Resuelve tus dudas, elige tu próximo producto o recibe asesoría personalizada.</p>
            </div>

            <a href="https://wa.me/{{ $telefono }}?text={{ $mensaje }}" target="_blank" class="cta_ayuda_btn">
                <i class="fab fa-whatsapp"></i>
                Contáctanos por WhatsApp
            </a>

        </div>
    </div>
</section>
