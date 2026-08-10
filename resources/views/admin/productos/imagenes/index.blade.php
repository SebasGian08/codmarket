@extends('admin.layouts.app')

@section('title', 'Imágenes del Producto')

@section('content')

<div class="page-inner">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Gestión de Imágenes</h4>
            <small class="text-muted">{{ $producto->nombre }}</small>
        </div>

        <a href="{{ route('admin.productos.index') }}" class="btn btn-light border">
            <i class="fa fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row">

        <!-- LEFT: UPLOAD -->
        <div class="col-md-4">

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <h6 class="fw-semibold mb-3">Subir imagen</h6>

                    <form action="{{ route('admin.producto_imagen.store', $producto->id_producto) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="id_producto" value="{{ $producto->id_producto }}">

                        <!-- DROPZONE -->
                        <label class="upload-box w-100 text-center p-4 mb-3">
                            <input type="file" name="imagen" id="inputImagen" hidden multiple accept="image/*">

                            <div id="uploadContent">
                                <i class="fa fa-cloud-upload fa-2x mb-2 text-muted"></i>
                                <p class="mb-0 text-muted">Haz clic o arrastra imágenes</p>
                            </div>
                        </label>

                        <small class="text-muted d-block mb-3">
                            Puedes seleccionar varias imágenes a la vez: se suben automáticamente con la
                            variante / principal / orden elegidos.
                        </small>

                        <!-- VARIANTE -->
                        <div class="mb-2">
                            <label class="form-label small">Variante</label>
                            <select name="id_variante" id="id_variante" class="form-select">
                                <!-- <option value="">General</option> -->
                                @foreach($producto->variantes as $v)
                                <option value="{{ $v->id_variante }}">
                                    {{ $v->sku ?? 'Variante #' . $v->id_variante }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- OPCIONES -->
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label small">Principal</label>
                                <select name="principal" id="principal" class="form-select">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label small">Orden</label>
                                <input type="number" name="orden" id="orden" class="form-control" value="0">
                            </div>
                        </div>

                        <!-- PROGRESO -->
                        <div id="uploadStatus" class="d-none mt-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span id="statusText">Subiendo...</span>
                                <span id="statusCount">0 / 0</span>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div id="statusBar" class="progress-bar" style="width:0%"></div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        <!-- RIGHT: GALERÍA -->
        <div class="col-md-8">

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="fw-semibold mb-0">Galería</h6>
                        <small class="text-muted" id="contadorImagenes"
                            data-total="{{ $imagenes->count() }}">{{ $imagenes->count() }} imágenes</small>
                    </div>

                    <div class="row g-3" id="galeria">

                        @if($imagenes->isEmpty())
                        <div class="text-center py-5 text-muted w-100" id="sinImagenes">
                            <i class="fa fa-image fa-2x mb-2"></i>
                            <p>No hay imágenes</p>
                        </div>
                        @else

                        @foreach($imagenes as $img)
                        <div class="col-md-4">

                            <div class="image-card">

                                <img src="{{ asset($img->url) }}">

                                <div class="overlay">

                                    <div class="actions">

                                        <button type="button" class="btn btn-danger btn-delete-img"
                                            data-url="{{ route('admin.producto_imagen.destroy', ['producto' => $producto->id_producto, 'id' => $img->id_imagen]) }}">

                                            <i class="fa fa-trash"></i>

                                        </button>

                                    </div>

                                </div>

                                <div class="image-footer">

                                    <div class="sku-text" title="{{ $img->variante->sku ?? 'Sin SKU' }}">

                                        Sku :
                                        {{ \Illuminate\Support\Str::limit($img->variante->sku ?? 'Sin SKU', 20, '...') }}

                                    </div>

                                    @if($img->principal)
                                    <span class="main-badge">
                                        Principal
                                    </span>
                                    @endif

                                </div>

                            </div>
                            <!-- <small class="text-muted d-block text-center mt-1 product-name">
                                {{ \Illuminate\Support\Str::limit($producto->nombre, 18, '...') }}
                            </small> -->
                        </div>
                        @endforeach

                        @endif

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<style>
.upload-box {
    border: 2px dashed #ddd;
    border-radius: 14px;
    cursor: pointer;
    transition: .25s;
    background: #fff;
}

.upload-box:hover {
    border-color: #0d6efd;
    background: #f8fbff;
}

.image-card {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 4px 14px rgba(0, 0, 0, .06);
    transition: .25s;
}

.image-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, .12);
}

.image-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}

/* OVERLAY */
.overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top,
            rgba(0, 0, 0, .75),
            rgba(0, 0, 0, .15));
    opacity: 0;
    transition: .25s;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    padding: 12px;
}

.image-card:hover .overlay {
    opacity: 1;
}

/* BOTONES */
.actions {
    display: flex;
    gap: 8px;
}

.actions .btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(6px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, .15);
}

/* FOOTER */
.image-footer {
    padding: 12px;
    background: #fff;
    border-top: 1px solid #f1f1f1;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

/* SKU */
.sku-text {
    font-size: 13px;
    font-weight: 600;
    color: #444;
    max-width: 75%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}

/* BADGE */
.main-badge {
    background: #198754;
    color: #fff;
    font-size: 11px;
    padding: 5px 10px;
    border-radius: 30px;
    font-weight: 600;
}

/* OVERLAY DE SUBIDA */
.uploading-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    background: rgba(0, 0, 0, .4);
    z-index: 2;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
}

.image-card.upload-error {
    box-shadow: 0 0 0 2px #dc3545;
}

.upload-error .uploading-overlay {
    background: rgba(220, 53, 69, .55);
}
</style>

<script>
const input = document.getElementById('inputImagen');
const uploadContent = document.getElementById('uploadContent');
const galeria = document.getElementById('galeria');
const sinImagenes = document.getElementById('sinImagenes');
const contador = document.getElementById('contadorImagenes');

const statusBox = document.getElementById('uploadStatus');
const statusText = document.getElementById('statusText');
const statusCount = document.getElementById('statusCount');
const statusBar = document.getElementById('statusBar');

const token = document.querySelector('input[name="_token"]').value;
const url = "{{ route('admin.producto_imagen.store', $producto->id_producto) }}";
const productoId = {{ $producto->id_producto }};

function esc(t) {
    const d = document.createElement('div');
    d.textContent = t == null ? '' : String(t);
    return d.innerHTML;
}

function setStatus(texto, i, total) {
    statusText.textContent = texto;
    statusCount.textContent = i + ' / ' + total;
}

// Crea la tarjeta con la vista previa local mientras se sube
function appendTempCard(file) {
    const col = document.createElement('div');
    col.className = 'col-md-4';

    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);

    const card = document.createElement('div');
    card.className = 'image-card';

    const overlay = document.createElement('div');
    overlay.className = 'uploading-overlay';
    overlay.innerHTML = '<div class="spinner-border spinner-border-sm"></div><span>Subiendo...</span>';

    const footer = document.createElement('div');
    footer.className = 'image-footer';
    footer.innerHTML = '<div class="sku-text">Subiendo...</div>';

    card.append(img, overlay, footer);
    col.append(card);
    galeria.append(col);

    if (sinImagenes) { sinImagenes.remove(); }

    return col;
}

// Confirma la subida: reemplaza la vista previa por la imagen final
function markUploaded(col, data) {
    const card = col.querySelector('.image-card');
    card.querySelector('.uploading-overlay').remove();

    const img = card.querySelector('img');
    URL.revokeObjectURL(img.src);
    img.src = data.url;

    card.querySelector('.image-footer').innerHTML = `
        <div class="sku-text" title="${esc(data.sku)}">Sku : ${esc(data.sku)}</div>
        ${data.principal ? '<span class="main-badge">Principal</span>' : ''}`;

    const actions = document.createElement('div');
    actions.className = 'overlay';
    actions.innerHTML = `
        <div class="actions">
            <button type="button" class="btn btn-danger btn-delete-img"
                data-url="${esc(data.delete_url)}">
                <i class="fa fa-trash"></i>
            </button>
        </div>`;

    card.append(actions);
}

// Marca error en la tarjeta
function markError(col, msg) {
    const card = col.querySelector('.image-card');
    card.classList.add('upload-error');
    card.querySelector('.uploading-overlay').innerHTML =
        '<i class="fa fa-exclamation-triangle"></i><span>Error</span>';
    card.querySelector('.image-footer').innerHTML =
        '<div class="sku-text text-danger">' + esc(msg || 'No se pudo subir') + '</div>';
}

input.addEventListener('change', async () => {
    const files = Array.from(input.files || []);
    if (!files.length) return;

    const variante = document.getElementById('id_variante').value;
    const principal = document.getElementById('principal').value;
    const orden = document.getElementById('orden').value;

    statusBox.classList.remove('d-none');
    let ok = 0;

    for (let i = 0; i < files.length; i++) {
        setStatus('Subiendo...', i, files.length);
        statusBar.style.width = Math.round((i / files.length) * 100) + '%';

        const col = appendTempCard(files[i]);

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
    setTimeout(() => statusBox.classList.add('d-none'), 3500);

    if (contador) {
        const total = parseInt(contador.dataset.total || 0, 10) + ok;
        contador.dataset.total = total;
        contador.textContent = total + ' imágenes';
    }

    input.value = '';
});
</script>

@endsection