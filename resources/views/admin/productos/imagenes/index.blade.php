@extends('admin.layouts.app')

@section('title', 'Imágenes del Producto')

@section('content')

<div class="page-inner">

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fa fa-images me-2 text-primary"></i> Gestor de imágenes
            </h4>
            <small class="text-muted">
                <i class="fa fa-box me-1"></i> {{ $producto->nombre }}
            </small>
        </div>

        <a href="{{ route('admin.productos.index') }}" class="btn btn-light border">
            <i class="fa fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="row g-4">

        <!-- LEFT: UPLOAD -->
        <div class="col-lg-4 col-md-5">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <h6 class="fw-semibold mb-3">
                        <i class="fa fa-cloud-upload-alt me-2 text-primary"></i> Subir imágenes
                    </h6>

                    <form action="{{ route('admin.producto_imagen.store', $producto->id_producto) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">

                        <!-- DROPZONE -->
                        <div class="dropzone mb-3" id="dropzone">
                            <input type="file" name="imagen" id="inputImagen" hidden multiple accept="image/*">

                            <div class="dz-icon">
                                <i class="fa fa-cloud-upload-alt"></i>
                            </div>

                            <div class="dz-title">Arrastrá y soltá las imágenes</div>
                            <div class="dz-sub">o hacé clic para elegirlas del equipo</div>

                            <div class="dz-count d-none mt-2" id="dzCount"></div>
                        </div>

                        <small class="text-muted d-block mb-3">
                            Se suben automáticamente con la variante / principal / orden elegidos.
                        </small>

                        <!-- VARIANTE -->
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Variante</label>
                            <select name="id_variante" id="id_variante" class="form-select">
                                @foreach($producto->variantes as $v)
                                @php
                                    $atributos = $v->atributos
                                        ->map(function ($a) {
                                            return ($a->atributo->nombre ?? '') . ': ' . $a->valor;
                                        })
                                        ->implode(', ');
                                @endphp
                                <option value="{{ $v->id_variante }}">
                                    {{ $v->sku ?? 'Variante #' . $v->id_variante }}
                                    @if($atributos) — {{ $atributos }} @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- OPCIONES -->
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Principal</label>
                                <select name="principal" id="principal" class="form-select">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label small fw-semibold">Orden</label>
                                <input type="number" name="orden" id="orden" class="form-control" value="0">
                            </div>
                        </div>

                        <!-- PROGRESO -->
                        <div id="uploadStatus" class="d-none mt-4">
                            <div class="d-flex justify-content-between small mb-1">
                                <span id="statusText" class="fw-semibold">Subiendo...</span>
                                <span id="statusCount">0 / 0</span>
                            </div>
                            <div class="progress" style="height:8px;">
                                <div id="statusBar" class="progress-bar" style="width:0%"></div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        <!-- RIGHT: GALERÍA -->
        <div class="col-lg-8 col-md-7">

            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-semibold mb-0">
                            <i class="fa fa-th-large me-2 text-primary"></i> Galería
                        </h6>
                        <span class="badge rounded-pill bg-light text-dark border" id="contadorImagenes"
                            data-total="{{ $imagenes->count() }}">
                            <i class="fa fa-image me-1"></i>{{ $imagenes->count() }} imágenes
                        </span>
                    </div>

                    <div class="row g-3" id="galeria">

                        @if($imagenes->isEmpty())
                        <div class="text-center py-5 text-muted w-100" id="sinImagenes">
                            <i class="fa fa-images fa-3x mb-3 d-block opacity-50"></i>
                            <p class="fw-semibold mb-1">Aún no hay imágenes</p>
                            <small>Subí la primera imagen con el panel de la izquierda</small>
                        </div>
                        @else

                        @foreach($imagenes as $img)
                        <div class="col-lg-4 col-md-6">

                            <div class="gallery-card" data-id="{{ $img->id_imagen }}"
                                data-variante="{{ $img->id_variante }}" data-orden="{{ $img->orden }}">

                                <div class="gc-img">
                                    <img src="{{ asset($img->url) }}" alt="">

                                    @if($img->principal)
                                    <span class="gc-badge-principal"><i class="fa fa-star me-1"></i>Principal</span>
                                    @endif

                                    <div class="gc-actions">
                                        <button type="button" class="gc-btn gc-btn-view"
                                            data-url="{{ asset($img->url) }}" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button type="button"
                                            class="gc-btn gc-star {{ $img->principal ? 'active' : '' }}"
                                            title="Marcar principal">
                                            <i class="fa fa-star"></i>
                                        </button>
                                        <button type="button" class="gc-btn gc-btn-danger btn-delete-img"
                                            data-url="{{ route('admin.producto_imagen.destroy', ['producto' => $producto->id_producto, 'id' => $img->id_imagen]) }}"
                                            title="Eliminar">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="gc-footer">
                                    <span class="gc-sku" title="{{ $img->variante->sku ?? 'Sin SKU' }}">
                                        <i class="fa fa-tag me-1 text-muted"></i>
                                        {{ \Illuminate\Support\Str::limit($img->variante->sku ?? 'Sin SKU', 18, '...') }}
                                    </span>
                                    <span class="gc-orden">
                                        <i class="fa fa-sort-numeric-asc"></i> {{ $img->orden }}
                                    </span>
                                </div>

                            </div>

                        </div>
                        @endforeach

                        @endif

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- LIGHTBOX -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body p-0 text-center">
                <img id="lightboxImg" src="" class="img-fluid rounded-3 shadow-lg" style="max-height:78vh;">
            </div>
        </div>
    </div>
</div>

<!-- MODAL ROTACIÓN -->
<div class="modal fade" id="rotarModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="fw-bold mb-0">
                    <i class="fa fa-sync-alt me-2 text-primary"></i> Previsualizar imágenes
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Girá las imágenes que lo necesiten antes de subirlas.
                </p>
                <div class="row g-3" id="rotarGrid"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <span class="text-muted small" id="rotarInfo">0 imágenes</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnSubirRotadas">
                        <i class="fa fa-cloud-upload-alt me-1"></i> Subir imágenes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TOASTS -->
<div id="toastBox"></div>

<style>
/* ================= DROPZONE ================= */
.dropzone {
    border: 2px dashed #c3c9d4;
    border-radius: 18px;
    padding: 34px 20px;
    text-align: center;
    cursor: pointer;
    transition: .25s;
    background: linear-gradient(180deg, #f8fbff, #f2f6fc);
}

.dropzone:hover,
.dropzone.dragging {
    border-color: #0d6efd;
    background: linear-gradient(180deg, #eef5ff, #e2edff);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(13, 110, 253, .12);
}

.dz-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: #fff;
    color: #0d6efd;
    font-size: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 16px rgba(13, 110, 253, .18);
}

.dropzone.dragging .dz-icon {
    animation: dz-pulse 1s infinite;
}

@keyframes dz-pulse {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.dz-title {
    font-weight: 700;
    color: #334155;
    margin-bottom: 4px;
    font-size: 15px;
}

.dz-sub {
    color: #7b8794;
    font-size: 13px;
}

.dz-count {
    font-size: 13px;
    font-weight: 600;
    color: #0d6efd;
    background: rgba(13, 110, 253, .1);
    padding: 5px 12px;
    border-radius: 30px;
    display: inline-block;
}

/* ================= TARJETAS GALERÍA ================= */
.gallery-card {
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #eef1f6;
    box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
    transition: .25s;
}

.gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, .12);
}

.gallery-card-error {
    box-shadow: 0 0 0 2px #dc3545;
}

.gc-img {
    position: relative;
}

.gc-img img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    cursor: pointer;
}

.gc-badge-principal {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 30px;
    box-shadow: 0 4px 10px rgba(217, 119, 6, .4);
}

.gc-actions {
    position: absolute;
    right: 10px;
    bottom: 10px;
    display: flex;
    gap: 6px;
    opacity: 0;
    transform: translateY(6px);
    transition: .25s;
}

.gallery-card:hover .gc-actions,
.gallery-card.has-error .gc-actions {
    opacity: 1;
    transform: none;
}

.gc-btn {
    width: 34px;
    height: 34px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, .95);
    color: #334155;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, .18);
    transition: .2s;
}

.gc-btn:hover {
    transform: scale(1.1);
}

.gc-btn-danger {
    background: #dc3545;
    color: #fff;
}

.gc-btn-danger:hover {
    background: #bb2d3b;
}

.gc-star.active {
    background: #f59e0b;
    color: #fff;
}

.gc-spinner {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(0, 0, 0, .45);
    color: #fff;
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 30px;
}

.gc-error {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(220, 53, 69, .6);
    color: #fff;
    font-size: 13px;
    font-weight: 700;
}

.gc-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-top: 1px solid #f1f1f1;
}

.gc-sku {
    font-size: 12px;
    font-weight: 600;
    color: #444;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.gc-orden {
    font-size: 11px;
    color: #8a94a6;
    background: #f3f5f9;
    padding: 3px 8px;
    border-radius: 20px;
    white-space: nowrap;
}

/* ================= TOASTS ================= */
#toastBox {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 3000;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.toast-msg {
    background: #198754;
    color: #fff;
    padding: 10px 16px;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, .2);
    font-size: 14px;
    opacity: 0;
    transform: translateY(8px);
    transition: .25s;
}

.toast-msg.show {
    opacity: 1;
    transform: none;
}

.toast-msg.danger {
    background: #dc3545;
}

/* ================= MODO OSCURO ================= */
html[data-theme="dark"] .dropzone {
    background: var(--ka-surface);
    border-color: var(--ka-border);
}

html[data-theme="dark"] .dropzone:hover,
html[data-theme="dark"] .dropzone.dragging {
    border-color: #3f6fb5;
    background: var(--ka-surface-2);
}

html[data-theme="dark"] .dz-icon {
    background: var(--ka-surface-2);
    color: #7eb3ff;
}

html[data-theme="dark"] .dz-title {
    color: var(--ka-text);
}

html[data-theme="dark"] .dz-sub {
    color: var(--ka-text-muted);
}

html[data-theme="dark"] .dz-count {
    color: #7eb3ff;
    background: rgba(63, 111, 181, .2);
}

html[data-theme="dark"] .gallery-card {
    background: var(--ka-surface);
    border-color: var(--ka-border);
}

html[data-theme="dark"] .gc-sku {
    color: var(--ka-text);
}

html[data-theme="dark"] .gc-orden {
    color: var(--ka-text-muted);
    background: var(--ka-surface-2);
}

html[data-theme="dark"] .gc-footer {
    border-color: var(--ka-border);
}

html[data-theme="dark"] .gc-btn {
    background: var(--ka-surface-2);
    color: var(--ka-text);
}

/* ================= MODAL ROTACIÓN ================= */
.rotar-item {
    border-radius: 14px;
    overflow: hidden;
    background: #fff;
    border: 1px solid #eef1f6;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
    transition: .2s;
}

.rotar-item:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, .1);
}

.rotar-item-img {
    position: relative;
    background: #f8f9fb;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 180px;
    overflow: hidden;
}

.rotar-item-img img {
    max-width: 100%;
    max-height: 180px;
    object-fit: contain;
    transition: transform .3s ease;
}

.rotar-item-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 8px;
    border-top: 1px solid #f1f1f1;
}

.rotar-item-controls .btn {
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 8px;
}

.rotar-item-deg {
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    min-width: 36px;
    text-align: center;
}

html[data-theme="dark"] .rotar-item {
    background: var(--ka-surface);
    border-color: var(--ka-border);
}

html[data-theme="dark"] .rotar-item-img {
    background: var(--ka-surface-2);
}

html[data-theme="dark"] .rotar-item-controls {
    border-color: var(--ka-border);
}
</style>

<script>
const input = document.getElementById('inputImagen');
const dropzone = document.getElementById('dropzone');
const dzCount = document.getElementById('dzCount');
const galeria = document.getElementById('galeria');
const sinImagenes = document.getElementById('sinImagenes');
const contador = document.getElementById('contadorImagenes');

const statusBox = document.getElementById('uploadStatus');
const statusText = document.getElementById('statusText');
const statusCount = document.getElementById('statusCount');
const statusBar = document.getElementById('statusBar');

const token = document.querySelector('input[name="_token"]').value;
const url = "{{ route('admin.producto_imagen.store', $producto->id_producto) }}";
const base = url.replace(/\/guardar$/, '');
const productoId = {{ $producto->id_producto }};

/* ============ ROTACIÓN ============ */
let pendingFiles = [];
let rotaciones = {};

function esc(t) {
    const d = document.createElement('div');
    d.textContent = t == null ? '' : String(t);
    return d.innerHTML;
}

function toast(msg, type) {
    const t = document.createElement('div');
    t.className = 'toast-msg' + (type === 'danger' ? ' danger' : '');
    t.textContent = msg;
    document.getElementById('toastBox').appendChild(t);
    requestAnimationFrame(() => t.classList.add('show'));
    setTimeout(() => {
        t.classList.remove('show');
        setTimeout(() => t.remove(), 300);
    }, 2500);
}

function setStatus(texto, i, total) {
    statusText.textContent = texto;
    statusCount.textContent = i + ' / ' + total;
}

/* ============ MODAL DE ROTACIÓN ============ */
function abrirModalRotacion(files) {
    pendingFiles = Array.from(files);
    rotaciones = {};

    const grid = document.getElementById('rotarGrid');
    grid.innerHTML = '';

    pendingFiles.forEach((file, idx) => {
        rotaciones[idx] = 0;
        const col = document.createElement('div');
        col.className = 'col-sm-6 col-lg-4';
        col.innerHTML = `
            <div class="rotar-item" data-idx="${idx}">
                <div class="rotar-item-img">
                    <img src="${URL.createObjectURL(file)}" alt="${esc(file.name)}">
                </div>
                <div class="rotar-item-controls">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-action="rot-izq" data-idx="${idx}" title="Girar izquierda">
                        <i class="fa fa-undo"></i>
                    </button>
                    <span class="rotar-item-deg" data-deg-idx="${idx}">0°</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-action="rot-der" data-idx="${idx}" title="Girar derecha">
                        <i class="fa fa-redo"></i>
                    </button>
                </div>
            </div>`;
        grid.appendChild(col);
    });

    document.getElementById('rotarInfo').textContent = pendingFiles.length + ' imagen(es)';

    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('rotarModal'));
    modal.show();
}

function girarImagen(idx, grados) {
    rotaciones[idx] = ((rotaciones[idx] || 0) + grados + 360) % 360;
    const degEl = document.querySelector(`[data-deg-idx="${idx}"]`);
    if (degEl) degEl.textContent = rotaciones[idx] + '°';

    const item = document.querySelector(`.rotar-item[data-idx="${idx}"]`);
    if (item) {
        const img = item.querySelector('img');
        if (img) img.style.transform = 'rotate(' + rotaciones[idx] + 'deg)';
    }
}

function rotarCanvas(file, grados) {
    return new Promise((resolve) => {
        if (!grados || grados === 0) { resolve(file); return; }

        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = function() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const rad = (grados * Math.PI) / 180;
            const absCos = Math.abs(Math.cos(rad));
            const absSin = Math.abs(Math.sin(rad));

            if (grados === 90 || grados === 270) {
                canvas.width = img.height;
                canvas.height = img.width;
            } else {
                canvas.width = img.width;
                canvas.height = img.height;
            }

            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate(rad);
            ctx.drawImage(img, -img.width / 2, -img.height / 2);

            canvas.toBlob(function(blob) {
                URL.revokeObjectURL(url);
                const rotated = new File([blob], file.name, { type: file.type });
                resolve(rotated);
            }, file.type, 0.92);
        };
        img.src = url;
    });
}

document.getElementById('rotarGrid').addEventListener('click', function(e) {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const idx = parseInt(btn.dataset.idx);
    const action = btn.dataset.action;

    if (action === 'rot-izq') girarImagen(idx, -90);
    if (action === 'rot-der') girarImagen(idx, 90);
});

document.getElementById('btnSubirRotadas').addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner-border spinner-border-sm me-1"></div> Procesando...';

    const filesFinales = [];
    for (let i = 0; i < pendingFiles.length; i++) {
        const rot = rotaciones[i] || 0;
        const processed = await rotarCanvas(pendingFiles[i], rot);
        filesFinales.push(processed);
    }

    bootstrap.Modal.getOrCreateInstance(document.getElementById('rotarModal')).hide();

    btn.disabled = false;
    btn.innerHTML = '<i class="fa fa-cloud-upload-alt me-1"></i> Subir imágenes';

    subirArchivos(filesFinales);
});

/* ============ SUBIDA ============ */
function buildCard(data, tempFile) {
    const col = document.createElement('div');
    col.className = 'col-lg-4 col-md-6';

    const card = document.createElement('div');
    card.className = 'gallery-card';
    if (!tempFile) {
        card.dataset.id = data.id;
        card.dataset.variante = data.variante || '';
        card.dataset.orden = data.orden || 0;
    }

    const imgBox = document.createElement('div');
    imgBox.className = 'gc-img';

    const pic = document.createElement('img');
    pic.src = tempFile ? URL.createObjectURL(tempFile) : data.url;

    const badge = (!tempFile && data.principal)
        ? '<span class="gc-badge-principal"><i class="fa fa-star me-1"></i>Principal</span>'
        : '';

    const error = tempFile
        ? '<span class="gc-error d-none"><i class="fa fa-exclamation-triangle me-1"></i>Error</span>'
        : '';

    imgBox.innerHTML = badge + error;

    const actions = document.createElement('div');
    actions.className = 'gc-actions';
    actions.innerHTML = tempFile
        ? '<span class="gc-spinner"><div class="spinner-border spinner-border-sm"></div> Subiendo</span>'
        : `
            <button type="button" class="gc-btn gc-btn-view" data-url="${esc(data.url)}" title="Ver">
                <i class="fa fa-eye"></i>
            </button>
            <button type="button" class="gc-btn gc-star${data.principal ? ' active' : ''}" title="Marcar principal">
                <i class="fa fa-star"></i>
            </button>
            <button type="button" class="gc-btn gc-btn-danger btn-delete-img" data-url="${esc(data.delete_url)}" title="Eliminar">
                <i class="fa fa-trash"></i>
            </button>`;

    imgBox.append(pic, actions);

    const footer = document.createElement('div');
    footer.className = 'gc-footer';
    footer.innerHTML = tempFile
        ? '<span class="gc-sku"><i class="fa fa-tag me-1 text-muted"></i>Subiendo...</span>'
        : `<span class="gc-sku"><i class="fa fa-tag me-1 text-muted"></i>${esc(data.sku)}</span>
           <span class="gc-orden"><i class="fa fa-sort-numeric-asc"></i> ${data.orden || 0}</span>`;

    card.append(imgBox, footer);
    col.append(card);
    return col;
}

function markUploaded(col, data) {
    col.replaceWith(buildCard(data, null));
}

function markError(col, msg) {
    const card = col.querySelector('.gallery-card');
    card.classList.add('gallery-card-error', 'has-error');
    const err = col.querySelector('.gc-error');
    if (err) err.classList.remove('d-none');
    const sku = col.querySelector('.gc-sku');
    if (sku) sku.textContent = 'No se pudo subir';
    toast(msg || 'No se pudo subir', 'danger');
}

function removeEmptyState() {
    if (sinImagenes) { sinImagenes.remove(); }
}

async function subirArchivos(files) {
    const variante = document.getElementById('id_variante').value;
    const principal = document.getElementById('principal').value;
    const orden = document.getElementById('orden').value;

    dzCount.classList.remove('d-none');
    dzCount.textContent = 'Subiendo ' + files.length + ' imagen(es)...';

    statusBox.classList.remove('d-none');
    let ok = 0;

    for (let i = 0; i < files.length; i++) {
        setStatus('Subiendo...', i, files.length);
        statusBar.style.width = Math.round((i / files.length) * 100) + '%';

        const col = buildCard(null, files[i]);
        galeria.appendChild(col);
        removeEmptyState();

        const fd = new FormData();
        fd.append('imagen', files[i]);
        fd.append('id_producto', productoId);
        fd.append('id_variante', variante);
        fd.append('principal', principal);
        fd.append('orden', orden);

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
                body: fd
            });
            const data = await res.json();
            if (res.ok && data.success) {
                ok++;
                markUploaded(col, data.image);
            } else {
                markError(col, (data && data.message) || 'No se pudo subir');
            }
        } catch (err) {
            markError(col, 'Error de red');
        }
    }

    statusBar.style.width = '100%';
    setStatus(
        ok === files.length ? '¡Listo! Imágenes subidas.' : 'Terminado con ' + (files.length - ok) + ' error(es).',
        ok,
        files.length
    );

    dzCount.textContent = ok + ' de ' + files.length + ' subida(s) correctamente';
    setTimeout(() => {
        statusBox.classList.add('d-none');
        dzCount.classList.add('d-none');
    }, 3500);

    if (contador) {
        const total = parseInt(contador.dataset.total || 0, 10) + ok;
        contador.dataset.total = total;
        contador.innerHTML = '<i class="fa fa-image me-1"></i>' + total + ' imágenes';
    }

    input.value = '';
}

/* ============ DRAG & DROP + CLICK ============ */
dropzone.addEventListener('click', () => input.click());

dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.classList.add('dragging');
});

dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragging'));

dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('dragging');
    if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        input.dispatchEvent(new Event('change'));
    }
});

input.addEventListener('change', () => {
    const files = Array.from(input.files || []);
    if (!files.length) return;
    abrirModalRotacion(files);
});

/* ============ LIGHTBOX ============ */
function openLightbox(src) {
    const el = document.getElementById('lightboxModal');
    const img = document.getElementById('lightboxImg');
    img.src = src;
    const modal = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
    modal.show();
}

/* ============ MARCAR PRINCIPAL ============ */
function marcarPrincipal(btn) {
    const card = btn.closest('.gallery-card');
    const id = card.dataset.id;
    const variante = card.dataset.variante || '';
    const orden = card.dataset.orden || 0;

    const fd = new FormData();
    fd.append('_method', 'PUT');
    fd.append('principal', 1);
    fd.append('id_variante', variante);
    fd.append('orden', orden);
    fd.append('_token', token);

    fetch(base + '/' + id, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
        body: fd
    })
    .then(function(res) {
        if (!res.ok) throw new Error();
        document.querySelectorAll('.gallery-card').forEach(function(c) {
            const s = c.querySelector('.gc-star');
            if (s) s.classList.remove('active');
            const b = c.querySelector('.gc-badge-principal');
            if (b) b.remove();
        });
        btn.classList.add('active');
        card.querySelector('.gc-img').insertAdjacentHTML('afterbegin',
            '<span class="gc-badge-principal"><i class="fa fa-star me-1"></i>Principal</span>');
        toast('Imagen marcada como principal');
    })
    .catch(function() {
        toast('No se pudo actualizar la imagen principal', 'danger');
    });
}

/* ============ DELEGACIÓN DE CLICS ============ */
document.addEventListener('click', function(e) {
    const viewBtn = e.target.closest('.gc-btn-view');
    if (viewBtn) {
        e.preventDefault();
        openLightbox(viewBtn.getAttribute('data-url'));
        return;
    }

    const starBtn = e.target.closest('.gc-star');
    if (starBtn) {
        e.preventDefault();
        marcarPrincipal(starBtn);
        return;
    }

    const imgEl = e.target.closest('.gallery-card .gc-img > img');
    if (imgEl) {
        openLightbox(imgEl.getAttribute('src'));
    }
});
</script>

@endsection
