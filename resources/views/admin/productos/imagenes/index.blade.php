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
                            <input type="file" name="imagen" id="inputImagen" hidden required accept="image/*">

                            <div id="uploadContent">
                                <i class="fa fa-cloud-upload fa-2x mb-2 text-muted"></i>
                                <p class="mb-0 text-muted">Haz clic o arrastra una imagen</p>
                            </div>

                            <img id="preview" class="img-fluid d-none rounded" style="max-height:180px;">
                        </label>

                        <!-- VARIANTE -->
                        <div class="mb-2">
                            <label class="form-label small">Variante</label>
                            <select name="id_variante" class="form-select">
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
                                <select name="principal" class="form-select">
                                    <option value="0">No</option>
                                    <option value="1">Sí</option>
                                </select>
                            </div>

                            <div class="col-6">
                                <label class="form-label small">Orden</label>
                                <input type="number" name="orden" class="form-control" value="0">
                            </div>
                        </div>

                        <!-- BOTÓN -->
                        <button class="btn btn-primary w-100 mt-3">
                            Subir imagen
                        </button>

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
                        <small class="text-muted">{{ $imagenes->count() }} imágenes</small>
                    </div>

                    @if($imagenes->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-image fa-2x mb-2"></i>
                        <p>No hay imágenes</p>
                    </div>
                    @else

                    <div class="row g-3">

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

                    </div>

                    @endif

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
</style>

<script>
const input = document.getElementById('inputImagen');
const preview = document.getElementById('preview');
const uploadContent = document.getElementById('uploadContent');

input.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
        uploadContent.classList.add('d-none');
    }
});
</script>

@endsection