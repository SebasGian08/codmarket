<section class="gadget_feature_section ecommerce_features sec_ptb_50 clearfix mt-2" style="background: #f8fafc;">
    <div class="container">

        <!-- TITLE -->
        <div class="section_title text-center mb-5">
            <span class="small_title">¿Por qué elegirnos?</span>
            <h2 class="mb-3">Experiencia premium en cada compra</h2>
            <p>
                Diseñamos una experiencia moderna, rápida y segura
                para que compres con total confianza.
            </p>
        </div>

        <div class="row justify-content-center g-4">

            <!-- ITEM -->
            <div class="col-lg-4 col-md-6 col-sm-10">
                <div class="feature_card">

                    <div class="feature_icon shipping">
                        <i class="fas fa-shipping-fast"></i>
                    </div>

                    <div class="feature_content">
                        <h3>Envíos Rápidos</h3>

                        <p>
                            Realizamos entregas ágiles y seguras
                            para que recibas tus productos
                            en el menor tiempo posible.
                        </p>
                    </div>

                </div>
            </div>

            <!-- ITEM -->
            <div class="col-lg-4 col-md-6 col-sm-10">
                <div class="feature_card">

                    <div class="feature_icon secure">
                        <i class="fas fa-shield-alt"></i>
                    </div>

                    <div class="feature_content">
                        <h3>Compra 100% Segura</h3>

                        <p>
                            Protegemos cada transacción con métodos
                            de pago confiables y seguridad avanzada.
                        </p>
                    </div>

                </div>
            </div>

            <!-- ITEM -->
            <div class="col-lg-4 col-md-6 col-sm-10">
                <div class="feature_card">

                    <div class="feature_icon support">
                        <i class="fab fa-whatsapp"></i>
                    </div>

                    <div class="feature_content">
                        <h3>Atención Personalizada</h3>

                        <p>
                            Nuestro equipo está listo para ayudarte
                            antes, durante y después de tu compra.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<style>
/*=====================================
FEATURE SECTION
=====================================*/

.ecommerce_features {
    position: relative;
}

.section_title .small_title {
    display: inline-block;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: black;
    margin-bottom: 12px;
}

.section_title h2 {
    font-size: 38px;
    font-weight: 700;
    color: var(--color-secundario);
    margin-bottom: 15px;
}

.section_title p {
    font-size: 16px;
    color: #777;
    max-width: 650px;
    margin: auto;
    line-height: 1.8;
}

/* CARD */

.feature_card {
    position: relative;
    background: #fff;
    border-radius: 22px;
    padding: 45px 35px;
    text-align: center;
    height: 100%;
    overflow: hidden;
    transition: all .35s ease;
    border: 1px solid #f1f1f1;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.04);
}

.feature_card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
}

/* ICON */

.feature_icon {
    width: 95px;
    height: 95px;
    margin: 0 auto 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.feature_icon i {
    font-size: 38px;
}

/* COLORS */

.feature_icon.shipping,
.feature_icon.secure,
.feature_icon.support {
    background: var(--color-secundario);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.feature_icon.shipping i,
.feature_icon.secure i,
.feature_icon.support i {
    color: #ffffff;
}

/* TEXT */

.feature_content h3 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--color-secundario);
}

.feature_content p {
    font-size: 15px;
    line-height: 1.9;
    color: #777;
    margin: 0;
}

/* RESPONSIVE */

@media (max-width: 991px) {

    .section_title h2 {
        font-size: 30px;
    }

    .feature_card {
        padding: 35px 25px;
    }

}

@media (max-width: 576px) {

    .section_title h2 {
        font-size: 26px;
    }

    .feature_content h3 {
        font-size: 21px;
    }

}
</style>