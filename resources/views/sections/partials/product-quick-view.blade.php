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
                    <div class="product-quick-view-gallery">
                        <div class="product-quick-view-thumbnails"
                            aria-label="Otras imágenes del producto" hidden></div>
                        <div class="product-quick-view-stage">
                            <img src="{{ asset('assets/images/tienda_virtual/default.png') }}"
                                alt="" class="product-quick-view-image" role="button" tabindex="0"
                                aria-label="Ampliar imagen del producto">
                            <button type="button" class="product-quick-view-zoom"
                                aria-label="Ampliar imagen">
                                <i class="fal fa-search-plus" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="product-quick-view-arrow product-quick-view-previous"
                                aria-label="Imagen anterior" hidden>
                                <i class="fal fa-arrow-left" aria-hidden="true"></i>
                            </button>
                            <div class="product-quick-view-dots" aria-label="Seleccionar imagen"></div>
                            <button type="button" class="product-quick-view-arrow product-quick-view-next"
                                aria-label="Imagen siguiente" hidden>
                                <i class="fal fa-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="product-quick-view-details">
                    <h2 id="productQuickViewTitle" class="product-quick-view-title"></h2>
                    <p class="product-quick-view-description"></p>

                    <div class="product-quick-view-price">
                        <span class="product-quick-view-price-current">Consultar</span>
                    </div>

                    <div class="product-quick-view-actions">
                        <a class="product-quick-view-whatsapp" href="#" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-whatsapp" aria-hidden="true"></i>
                            Consultar por WhatsApp
                        </a>
                        <a class="product-quick-view-details-link" href="#">
                            Ver más detalle <i class="fal fa-angle-right" aria-hidden="true"></i>
                        </a>
                    </div>
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
    const detailsLink = modal.querySelector('.product-quick-view-details-link');
    const gallery = modal.querySelector('.product-quick-view-gallery');
    const stage = modal.querySelector('.product-quick-view-stage');
    const thumbnails = modal.querySelector('.product-quick-view-thumbnails');
    const dots = modal.querySelector('.product-quick-view-dots');
    const previousButton = modal.querySelector('.product-quick-view-previous');
    const nextButton = modal.querySelector('.product-quick-view-next');
    const fallbackImage = "{{ asset('assets/images/tienda_virtual/default.png') }}";
    let activeProduct = null;
    let activeImageIndex = 0;

    const showImage = function (index) {
        activeImageIndex = (index + activeProduct.images.length) % activeProduct.images.length;
        image.style.transform = '';
        image.style.transformOrigin = '';
        image.src = activeProduct.images[activeImageIndex];
        image.alt = activeProduct.name + ' - Imagen ' + (activeImageIndex + 1);

        thumbnails.querySelectorAll('.product-quick-view-thumbnail').forEach(function (thumbnail, i) {
            const isActive = i === activeImageIndex;
            thumbnail.classList.toggle('active', isActive);
            thumbnail.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });

        dots.querySelectorAll('.product-quick-view-dot').forEach(function (dot, i) {
            const isActive = i === activeImageIndex;
            dot.classList.toggle('active', isActive);
            dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    };

    const renderGallery = function (images) {
        activeProduct.images = images.length ? images : [fallbackImage];
        activeImageIndex = 0;
        thumbnails.replaceChildren();
        dots.replaceChildren();

        activeProduct.images.forEach(function (src, index) {
            const thumbnail = document.createElement('button');
            thumbnail.type = 'button';
            thumbnail.className = 'product-quick-view-thumbnail';
            thumbnail.setAttribute('aria-label', 'Ver imagen ' + (index + 1));
            thumbnail.addEventListener('click', function () {
                showImage(index);
            });

            const thumbnailImage = document.createElement('img');
            thumbnailImage.src = src;
            thumbnailImage.alt = '';
            thumbnail.appendChild(thumbnailImage);
            thumbnails.appendChild(thumbnail);

            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'product-quick-view-dot';
            dot.setAttribute('aria-label', 'Ver imagen ' + (index + 1));
            dot.addEventListener('click', function () {
                showImage(index);
            });
            dots.appendChild(dot);
        });

        const hasMultipleImages = activeProduct.images.length > 1;
        thumbnails.hidden = !hasMultipleImages;
        previousButton.hidden = !hasMultipleImages;
        nextButton.hidden = !hasMultipleImages;
        dots.hidden = !hasMultipleImages;
        gallery.classList.toggle('has-multiple-images', hasMultipleImages);
        showImage(0);
    };

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
            images: [],
            price: trigger.dataset.previewPrice || ''
        };
        const images = JSON.parse(trigger.dataset.previewImages || '[]');
        activeProduct.images = Array.isArray(images)
            ? images.filter(function (src) { return typeof src === 'string' && src !== ''; })
            : [];
        if (!activeProduct.images.length && activeProduct.image) {
            activeProduct.images = [activeProduct.image];
        }

        title.textContent = activeProduct.name;
        description.textContent = activeProduct.description;
        detailsLink.href = activeProduct.url;
        renderGallery(activeProduct.images);
        currentPrice.textContent = activeProduct.price ? 'S/ ' + activeProduct.price : 'Consultar';

        const badgeText = trigger.dataset.previewNew === '1'
            ? 'Nuevo'
            : trigger.dataset.previewFeatured === '1' ? 'Más vendido' : '';
        badge.textContent = badgeText;
        badge.hidden = !badgeText;

        updateWhatsApp();

        window.jQuery(modal).modal('show');
    });

    previousButton.addEventListener('click', function () {
        showImage(activeImageIndex - 1);
    });

    nextButton.addEventListener('click', function () {
        showImage(activeImageIndex + 1);
    });

    stage.addEventListener('mousemove', function (event) {
        if (event.target.closest('button')) {
            image.style.transform = '';
            image.style.transformOrigin = '';
            return;
        }

        const bounds = stage.getBoundingClientRect();
        const x = ((event.clientX - bounds.left) / bounds.width) * 100;
        const y = ((event.clientY - bounds.top) / bounds.height) * 100;

        image.style.transformOrigin = x + '% ' + y + '%';
        image.style.transform = 'scale(2)';
    });

    stage.addEventListener('mouseleave', function () {
        image.style.transform = '';
        image.style.transformOrigin = '';
    });

    modal.addEventListener('keydown', function (event) {
        if (!activeProduct || activeProduct.images.length < 2) return;

        if (event.key === 'ArrowLeft') {
            showImage(activeImageIndex - 1);
        } else if (event.key === 'ArrowRight') {
            showImage(activeImageIndex + 1);
        }
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
        const sourceImage = image.matches('img')
            ? image
            : image.closest('.product-quick-view-stage').querySelector('.product-quick-view-image');
        activeTrigger = image;
        previewImage.src = sourceImage.currentSrc || sourceImage.src;
        previewImage.alt = sourceImage.alt || '';
        preview.hidden = false;
        document.body.classList.add('producto-imagen-preview-activa');
        closeButton.focus();
    }

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest(
            '.zoom-container img, .product-quick-view-image, .product-quick-view-zoom'
        );
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
            ? event.target.closest(
                '.zoom-container img, .product-quick-view-image, .product-quick-view-zoom'
            )
            : null;

        if (trigger && (event.key === 'Enter' || event.key === ' ')) {
            event.preventDefault();
            openPreview(trigger);
        } else if (!preview.hidden && event.key === 'Escape') {
            event.stopPropagation();
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
