@php
$iconosBeneficios = [
    'Negocios' => [
        'fa fa-briefcase' => 'Negocio',
        'fa fa-building' => 'Edificio',
        'fa fa-chart-bar' => 'Gráfica barras',
        'fa fa-chart-line' => 'Gráfica línea',
        'fa fa-clipboard-list' => 'Lista',
        'fa fa-tags' => 'Etiquetas',
        'fa fa-store' => 'Tienda',
        'fa fa-handshake' => 'Acuerdo',
        'fa fa-crown' => 'Corona',
        'fa fa-star' => 'Estrella',
        'fa fa-award' => 'Premio',
        'fa fa-gem' => 'Gema',
        'fa fa-trophy' => 'Trofeo',
        'fa fa-ribbon' => 'Cinta',
        'fa fa-thumbtack' => 'Fijar',
        'fa fa-bullseye' => 'Objetivo',
    ],
    'Calidad y Garantía' => [
        'fa fa-check-circle' => 'Verificado',
        'fa fa-check-double' => 'Doble check',
        'fa fa-badge-check' => 'Insignia',
        'fa fa-user-check' => 'Usuario OK',
        'fa fa-shield-alt' => 'Escudo',
        'fa fa-lock' => 'Candado',
        'fa fa-thumbs-up' => 'Pulgar arriba',
        'fa fa-tasks' => 'Tareas',
        'fa fa-clipboard-check' => 'Checklist',
        'fa fa-medal' => 'Medalla',
        'fa fa-certificate' => 'Certificado',
    ],
    'Atención y Soporte' => [
        'fa fa-headset' => 'Atención',
        'fa fa-comments' => 'Chat',
        'fa fa-comment-dots' => 'Comentario',
        'fa fa-phone-alt' => 'Teléfono',
        'fa fa-envelope-open' => 'Correo',
        'fa fa-users' => 'Usuarios',
        'fa fa-user-tie' => 'Ejecutivo',
        'fa fa-life-ring' => 'Soporte',
        'fa fa-question-circle' => 'Ayuda',
        'fa fa-info-circle' => 'Información',
        'fa fa-clock' => 'Reloj',
        'fa fa-calendar-check' => 'Calendario OK',
        'fa fa-hourglass-end' => 'Tiempo',
    ],
    'Rapidez y Tecnología' => [
        'fa fa-bolt' => 'Rayo',
        'fa fa-rocket' => 'Cohete',
        'fa fa-paper-plane' => 'Enviar',
        'fa fa-globe' => 'Web',
        'fa fa-code' => 'Código',
        'fa fa-laptop-code' => 'Desarrollo',
        'fa fa-server' => 'Servidor',
        'fa fa-database' => 'Base de datos',
        'fa fa-cloud' => 'Nube',
        'fa fa-mobile-alt' => 'Celular',
        'fa fa-desktop' => 'PC',
        'fa fa-wifi' => 'Wi-Fi',
        'fa fa-plug' => 'Conexión',
        'fa fa-cog' => 'Configuración',
        'fa fa-wrench' => 'Herramienta',
        'fa fa-sitemap' => 'Estructura',
        'fa fa-layer-group' => 'Capas',
        'fa fa-puzzle-piece' => 'Pieza',
        'fa fa-lightbulb' => 'Idea',
    ],
    'Pagos y Finanzas' => [
        'fa fa-credit-card' => 'Tarjeta',
        'fa fa-wallet' => 'Billetera',
        'fa fa-money-bill-wave' => 'Dinero',
        'fa fa-dollar-sign' => 'Dólar',
        'fa fa-coins' => 'Monedas',
        'fa fa-receipt' => 'Recibo',
        'fa fa-university' => 'Banco',
        'fa fa-hand-holding-usd' => 'Pago',
        'fa fa-percent' => 'Porcentaje',
        'fa fa-exchange-alt' => 'Intercambio',
        'fa fa-file-invoice' => 'Factura',
        'fa fa-truck' => 'Envío',
    ],
    'Crecimiento' => [
        'fa fa-trend-up' => 'Tendencia',
        'fa fa-seedling' => 'Crecimiento',
        'fa fa-heart' => 'Confianza',
        'fa fa-magic' => 'Mágico',
        'fa fa-search' => 'Búsqueda',
        'fa fa-eye' => 'Visión',
        'fa fa-link' => 'Enlace',
        'fa fa-book-open' => 'Aprendizaje',
    ],
];
@endphp

<div class="modal fade" id="modalGuiaIconos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-icons me-1"></i> Guía de Iconos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Haz clic en un icono para copiar su clase al campo <strong>Icono</strong>.</p>

                @foreach($iconosBeneficios as $categoria => $iconos)
                <h6 class="mt-3 mb-2 text-uppercase" style="font-size: 12px; letter-spacing: 1px; color: var(--ka-text, #6c757d);">
                    {{ $categoria }}
                </h6>

                <div class="row g-2">
                    @foreach($iconos as $clase => $etiqueta)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <button type="button" class="btn btn-light border w-100 guia-icono-btn"
                            data-clase="{{ $clase }}" title="{{ $clase }}" onclick="usarIcono(this)">
                            <i class="{{ $clase }} d-block" style="font-size: 22px; line-height: 1.4; color: var(--ka-primary, #177dff);"></i>
                            <small class="d-block text-truncate" style="font-size: 10px;">{{ $etiqueta }}</small>
                        </button>
                    </div>
                    @endforeach
                </div>
                @endforeach

                <p class="text-muted small mt-3 mb-0">
                    También puedes escribir cualquier clase de FontAwesome manualmente, ej: <code>fa fa-rocket</code>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@once
<script>
    window.prepararIcono = function(btn) {
        window._iconoInput = btn.closest('.input-group').querySelector('input[name*="icono"]');
    };

    window.usarIcono = function(btn) {
        if (window._iconoInput) {
            window._iconoInput.value = btn.getAttribute('data-clase');
        }
        var modal = document.getElementById('modalGuiaIconos');
        if (modal && window.bootstrap) {
            var instancia = bootstrap.Modal.getInstance(modal);
            if (instancia) {
                instancia.hide();
            }
        }
    };
</script>
@endonce