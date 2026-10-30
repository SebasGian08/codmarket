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
                        alt="" class="product-quick-view-image">
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
@endpush
