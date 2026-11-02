@php
$telefonoWhatsApp = isset($empresa)
    ? preg_replace('/\D+/', '', (string) (($empresa->whatsapp ?? null) ?: ($empresa->telefono ?? '')))
    : '';
@endphp

<div class="modal fade product-quick-view" id="productQuickView" tabindex="-1" role="dialog"
    aria-labelledby="productQuickViewTitle" aria-hidden="true" data-whatsapp-phone="{{ $telefonoWhatsApp }}">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="product-quick-view-close" data-dismiss="modal" aria-label="Cerrar">
                <i class="fal fa-times" aria-hidden="true"></i>
            </button>

            <div class="product-quick-view-layout">
                <div class="product-quick-view-media">
                    <span class="product-quick-view-badge" hidden></span>
                    <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                        alt="" class="product-quick-view-image" role="button" tabindex="0"
                        aria-label="Ampliar imagen del producto">
                </div>

                <div class="product-quick-view-details">
                    <div class="product-quick-view-category">Vista rápida</div>
                    <h2 id="productQuickViewTitle" class="product-quick-view-title"></h2>
                    <p class="product-quick-view-description"></p>

                    <div class="product-quick-view-price">
                        <span class="product-quick-view-price-current">Consultar</span>
                    </div>

                    <a class="product-quick-view-whatsapp" href="#" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                        Consultar por WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="producto-imagen-preview" id="productoImagenPreview" hidden role="dialog"
    aria-modal="true" aria-label="Vista ampliada de la imagen" tabindex="-1">
    <button type="button" class="producto-imagen-preview-cerrar" aria-label="Cerrar imagen ampliada">
        <i class="fal fa-times" aria-hidden="true"></i>
    </button>
    <img class="producto-imagen-preview-img" src="" alt="">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('productQuickView');
    if (!modal) return;

    const image = modal.querySelector('.product-quick-view-image');
    const title = modal.querySelector('.product-quick-view-title');
    const description = modal.querySelector('.product-quick-view-description');
    const badge = modal.querySelector('.product-quick-view-badge');
    const currentPrice = modal.querySelector('.product-quick-view-price-current');
    const whatsappButton = modal.querySelector('.product-quick-view-whatsapp');
    const fallbackImage = "{{ asset('assets/images/tienda_virtual/default.png') }}";
    let activeProduct = null;

    const updateWhatsApp = function () {
        const phone = modal.dataset.whatsappPhone;
        const lines = [
            'Hola, quisiera consultar por este producto:',
            activeProduct.name,
            activeProduct.url
        ].filter(Boolean);

        whatsappButton.href = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(lines.join('\n'));
    };

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('.catalogo-vista-previa');
        if (!trigger) return;

        event.preventDefault();
        event.stopPropagation();

        activeProduct = {
            name: trigger.dataset.previewName || '',
            description: trigger.dataset.previewDescription || '',
            url: trigger.dataset.previewUrl || '',
            image: trigger.dataset.previewImage || fallbackImage,
            price: trigger.dataset.previewPrice || ''
        };
        title.textContent = activeProduct.name;
        description.textContent = activeProduct.description;
        image.src = activeProduct.image;
        image.alt = activeProduct.name;
        currentPrice.textContent = activeProduct.price ? 'S/ ' + activeProduct.price : 'Consultar';

        const badgeText = trigger.dataset.previewNew === '1'
            ? 'Nuevo'
            : trigger.dataset.previewFeatured === '1' ? 'Más vendido' : '';
        badge.textContent = badgeText;
        badge.hidden = !badgeText;

        updateWhatsApp();

        window.jQuery(modal).modal('show');
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const preview = document.getElementById('productoImagenPreview');
    const previewImage = preview.querySelector('.producto-imagen-preview-img');
    const closeButton = preview.querySelector('.producto-imagen-preview-cerrar');
    let activeTrigger = null;

    function closePreview() {
        preview.hidden = true;
        previewImage.style.transform = '';
        previewImage.style.transformOrigin = '';
        document.body.classList.remove('producto-imagen-preview-activa');

        if (activeTrigger && document.contains(activeTrigger)) {
            activeTrigger.focus();
        }
        activeTrigger = null;
    }

    function openPreview(image) {
        activeTrigger = image;
        previewImage.src = image.currentSrc || image.src;
        previewImage.alt = image.alt || '';
        preview.hidden = false;
        document.body.classList.add('producto-imagen-preview-activa');
        closeButton.focus();
    }

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('.zoom-container img, .product-quick-view-image');
        if (trigger) {
            event.preventDefault();
            openPreview(trigger);
            return;
        }

        if (event.target === preview || event.target.closest('.producto-imagen-preview-cerrar')) {
            closePreview();
        }
    });

    document.addEventListener('keydown', function (event) {
        const trigger = event.target.closest
            ? event.target.closest('.zoom-container img, .product-quick-view-image')
            : null;

        if (trigger && (event.key === 'Enter' || event.key === ' ')) {
            event.preventDefault();
            openPreview(trigger);
        } else if (!preview.hidden && event.key === 'Escape') {
            closePreview();
        }
    });

    previewImage.addEventListener('mousemove', function (event) {
        const bounds = previewImage.getBoundingClientRect();
        const x = ((event.clientX - bounds.left) / bounds.width) * 100;
        const y = ((event.clientY - bounds.top) / bounds.height) * 100;

        previewImage.style.transformOrigin = x + '% ' + y + '%';
        previewImage.style.transform = 'scale(1.35)';
    });

    previewImage.addEventListener('mouseleave', function () {
        previewImage.style.transform = '';
        previewImage.style.transformOrigin = '';
    });
});
</script>
@endpush
