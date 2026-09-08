section class="gadget_feature_section ecommerce_features sec_ptb_50 clearfix mt-2" style="background: #f8fafc;">
    <div class="container">

        <!-- TITLE -->
        <div class="section_heading text-center mb_30">

            <div class="section_heading_title">
                <span></span>
                <small>
                    {{ $config['seccion_steps_titulo'] ?? 'Â¿POR QUÃ‰ ELEGIRNOS?' }}
                </small>
                <span></span>
            </div>

            <p class="section_heading_description">
                {!! limpiarTextoEditor($config['seccion_steps_descripcion'] ?? 'DiseÃ±amos una experiencia moderna, rÃ¡pida y segura para que compres con total confianza.') !!}
            </p>

        </div>

        <div class="row justify-content-center g-4">

            <!-- ITEM -->
            <div class="col-lg-4 col-md-6 col-sm-10 mt-3">
                <div class="feature_card">

                    <div class="feature_icon shipping">
                        <i class="fas fa-shipping-fast"></i>
                    </div>

                    <div class="feature_content">
                        <h3>EnvÃ­os RÃ¡pidos</h3>

                        <p>
                            Realizamos entregas Ã¡giles y seguras
                            para que recibas tus productos
                            en el menor tiempo posible.
                        </p>
                    </div>

                </div>
            </div>

            <!-- ITEM -->
            <div class="col-lg-4 col-md-6 col-sm-10 mt-3">
                <div class="feature_card">

                    <div class="feature_icon secure">
                        <i class="fas fa-shield-alt"></i>
                    </div>

                    <div class="feature_content">
                        <h3>Compra 100% Segura</h3>

                        <p>
                            Protegemos cada transacciÃ³n con mÃ©todos
                            de pago confiables y seguridad avanzada.
                        </p>
                    </div>

                </div>
            </div>

            <!-- ITEM -->
            <div class="col-lg-4 col-md-6 col-sm-10 mt-3">
                <div class="feature_card">

                    <div class="feature_icon support">
                        <i class="fab fa-whatsapp"></i>
                    </div>

                    <div class="feature_content">
                        <h3>AtenciÃ³n Personalizada</h3>

                        <p>
                            Nuestro equipo estÃ¡ listo para ayudarte
                            antes, durante y despuÃ©s de tu compra.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

{{-- CSS movido a assets/css/steps.css --}}
