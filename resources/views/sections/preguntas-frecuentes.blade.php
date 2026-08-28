<div class="faq-section">
    <div class="faq-container">

        <div class="section_heading text-center mb_30">
            <div class="section_heading_title">
                <span></span>
                <small>{{ $config['seccion_preguntas_titulo'] ?? 'PREGUNTAS' }}</small>
                <span></span>
            </div>
            <p class="section_heading_description">
                {{ $config['seccion_preguntas_descripcion'] ?? 'Resolvemos las dudas más comunes sobre nuestros servicios.' }}
            </p>
        </div>

        <!-- ITEM -->
        <div class="faq-item active">
            <button class="faq-question" onclick="toggleItem(this)">
                <span>¿Realizan envíos internacionales?</span>
                <div class="faq-icon"></div>
            </button>

            <div class="faq-answer">
                <p>
                    Sí, realizamos envíos a diferentes países con tiempos de entrega rápidos y seguros.
                </p>
            </div>
        </div>

        <!-- ITEM -->
        <div class="faq-item">
            <button class="faq-question" onclick="toggleItem(this)">
                <span>¿Puedo cambiar mi pedido?</span>
                <div class="faq-icon"></div>
            </button>

            <div class="faq-answer">
                <p>
                    Puedes modificar tu pedido dentro de las primeras 24 horas después de realizar la compra.
                </p>
            </div>
        </div>

        <!-- ITEM -->
        <div class="faq-item">
            <button class="faq-question" onclick="toggleItem(this)">
                <span>¿Qué métodos de pago aceptan?</span>
                <div class="faq-icon"></div>
            </button>

            <div class="faq-answer">
                <p>
                    Aceptamos tarjetas de crédito, débito, transferencias y pagos digitales.
                </p>
            </div>
        </div>

        <!-- ITEM -->
        <div class="faq-item">
            <button class="faq-question" onclick="toggleItem(this)">
                <span>¿Ofrecen garantía?</span>
                <div class="faq-icon"></div>
            </button>

            <div class="faq-answer">
                <p>
                    Sí, todos nuestros productos cuentan con garantía según las políticas establecidas.
                </p>
            </div>
        </div>

    </div>
</div>

<script>
function toggleItem(button) {
    const item = button.parentElement;
    item.classList.toggle("active");
}
</script>