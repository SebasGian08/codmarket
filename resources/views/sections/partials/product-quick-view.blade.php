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
                    <span class="product-quick-view-sku"></span>
                    <p class="product-quick-view-description"></p>

                    <div class="product-quick-view-price">
                        <span class="product-quick-view-price-old" hidden></span>
                        <span class="product-quick-view-price-current">Consultar</span>
                    </div>

                    <div class="product-quick-view-variants" hidden>
                        <span class="product-quick-view-variants-title">Selecciona una variante</span>
                        <div class="product-quick-view-options"></div>
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
    const sku = modal.querySelector('.product-quick-view-sku');
    const description = modal.querySelector('.product-quick-view-description');
    const badge = modal.querySelector('.product-quick-view-badge');
    const currentPrice = modal.querySelector('.product-quick-view-price-current');
    const oldPrice = modal.querySelector('.product-quick-view-price-old');
    const variantsContainer = modal.querySelector('.product-quick-view-variants');
    const optionsContainer = modal.querySelector('.product-quick-view-options');
    const whatsappButton = modal.querySelector('.product-quick-view-whatsapp');
    const fallbackImage = "{{ asset('assets/images/tienda_virtual/default.png') }}";
    let activeProduct = null;
    let activeVariant = null;
    let activeVariants = [];

    const formatPrice = function (price) {
        if (price === null || price === undefined || price === '') return 'Consultar';
        const amount = Number(price);
        return Number.isFinite(amount) ? 'S/ ' + amount.toFixed(2) : 'Consultar';
    };

    const getVariantDescription = function (variant) {
        const attributes = Array.isArray(variant.attributes) ? variant.attributes : [];
        return attributes.length
            ? attributes.map(function (attribute) {
                return attribute.name + ': ' + attribute.value;
            }).join(', ')
            : (variant.sku ? 'SKU ' + variant.sku : '');
    };

    const syncAttributeOptions = function () {
        optionsContainer.querySelectorAll('.product-quick-view-option').forEach(function (option) {
            const selectedValue = activeVariant && activeVariant.attributes.find(function (attribute) {
                return String(attribute.id) === option.dataset.attributeId;
            });
            const isSelected = selectedValue
                && String(selectedValue.valueId) === option.dataset.valueId;
            option.classList.toggle('is-selected', Boolean(isSelected));
            option.setAttribute('aria-pressed', isSelected ? 'true' : 'false');
        });
    };

    const updateWhatsApp = function () {
        const phone = modal.dataset.whatsappPhone;
        const lines = [
            'Hola, quisiera consultar por este producto:',
            activeProduct.name,
            activeVariant && getVariantDescription(activeVariant)
                ? 'Variante: ' + getVariantDescription(activeVariant)
                : '',
            activeVariant && activeVariant.sku ? 'SKU: ' + activeVariant.sku : '',
            activeVariant && activeVariant.salePrice !== null
                ? 'Precio: ' + formatPrice(activeVariant.salePrice)
                : activeVariant && activeVariant.price !== null
                    ? 'Precio: ' + formatPrice(activeVariant.price)
                    : '',
            activeProduct.url
        ].filter(Boolean);

        whatsappButton.href = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(lines.join('\n'));
    };

    const selectVariant = function (variant) {
        activeVariant = variant;
        syncAttributeOptions();
        sku.textContent = variant.sku ? 'SKU: ' + variant.sku : '';

        const variantImage = variant.images && variant.images.length ? variant.images[0] : activeProduct.image;
        image.src = variantImage || fallbackImage;
        image.alt = activeProduct.name;

        if (variant.salePrice !== null && variant.salePrice !== undefined) {
            oldPrice.textContent = formatPrice(variant.price);
            oldPrice.hidden = false;
            currentPrice.textContent = formatPrice(variant.salePrice);
        } else {
            oldPrice.hidden = true;
            currentPrice.textContent = formatPrice(variant.price);
        }

        updateWhatsApp();
    };

    const selectAttributeValue = function (attributeId, valueId) {
        const findVariant = function (requireCurrentAttributes) {
            return activeVariants.find(function (variant) {
                return variant.attributes.some(function (attribute) {
                    return String(attribute.id) === attributeId
                        && String(attribute.valueId) === valueId;
                }) && (!requireCurrentAttributes || activeVariant.attributes.every(function (current) {
                    if (String(current.id) === attributeId) return true;
                    return variant.attributes.some(function (attribute) {
                        return String(attribute.id) === String(current.id)
                            && String(attribute.valueId) === String(current.valueId);
                    });
                }));
            });
        };

        const variant = findVariant(true) || findVariant(false);
        if (variant) selectVariant(variant);
    };

    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('.catalogo-vista-previa');
        if (!trigger) return;

        event.preventDefault();
        event.stopPropagation();

        let variants;
        try {
            variants = JSON.parse(trigger.dataset.previewVariants || '[]');
        } catch (error) {
            console.error('No se pudieron cargar las variantes para la vista previa.', error);
            return;
        }

        activeProduct = {
            name: trigger.dataset.previewName || '',
            description: trigger.dataset.previewDescription || '',
            url: trigger.dataset.previewUrl || '',
            image: trigger.dataset.previewImage || fallbackImage
        };
        activeVariants = variants;
        activeVariant = null;
        title.textContent = activeProduct.name;
        description.textContent = activeProduct.description;
        sku.textContent = '';
        image.src = activeProduct.image;
        image.alt = activeProduct.name;

        const badgeText = trigger.dataset.previewNew === '1'
            ? 'Nuevo'
            : trigger.dataset.previewFeatured === '1' ? 'Más vendido' : '';
        badge.textContent = badgeText;
        badge.hidden = !badgeText;

        optionsContainer.replaceChildren();
        variantsContainer.hidden = variants.length === 0;

        const attributeGroups = new Map();
        variants.forEach(function (variant) {
            (Array.isArray(variant.attributes) ? variant.attributes : []).forEach(function (attribute) {
                const attributeId = String(attribute.id);
                if (!attributeGroups.has(attributeId)) {
                    attributeGroups.set(attributeId, {
                        name: attribute.name,
                        values: new Map()
                    });
                }
                attributeGroups.get(attributeId).values.set(String(attribute.valueId), attribute.value);
            });
        });

        attributeGroups.forEach(function (group, attributeId) {
            const groupElement = document.createElement('div');
            groupElement.className = 'product-quick-view-attribute';

            const label = document.createElement('span');
            label.className = 'product-quick-view-variants-title';
            label.textContent = group.name + ':';
            groupElement.appendChild(label);

            const values = document.createElement('div');
            values.className = 'product-quick-view-options';
            group.values.forEach(function (value, valueId) {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'product-quick-view-option';
                option.textContent = value;
                option.dataset.attributeId = attributeId;
                option.dataset.valueId = valueId;
                option.setAttribute('aria-pressed', 'false');
                option.addEventListener('click', function () {
                    selectAttributeValue(attributeId, valueId);
                });
                values.appendChild(option);
            });

            groupElement.appendChild(values);
            optionsContainer.appendChild(groupElement);
        });

        if (attributeGroups.size === 0 && variants.length > 1) {
            const values = document.createElement('div');
            values.className = 'product-quick-view-options';
            variants.forEach(function (variant) {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'product-quick-view-option';
                option.textContent = variant.sku ? 'SKU ' + variant.sku : 'Variante';
                option.addEventListener('click', function () {
                    selectVariant(variant);
                });
                values.appendChild(option);
            });
            optionsContainer.appendChild(values);
        }

        if (variants.length) {
            const initialVariant = variants.find(function (variant) {
                return String(variant.id) === trigger.dataset.previewVariantId;
            }) || variants[0];
            selectVariant(initialVariant);
        } else {
            oldPrice.hidden = true;
            currentPrice.textContent = 'Consultar';
            sku.textContent = '';
            updateWhatsApp();
        }

        window.jQuery(modal).modal('show');
    });
});
</script>
@endpush
