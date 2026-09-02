@extends('admin.layouts.app')

@section('title', 'Productos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex flex-wrap align-items-center gap-2">
            <h4 class="page-title">Productos</h4>
        </div>

        <div class="d-grid d-sm-flex gap-2 flex-sm-wrap">

            <a href="{{ route('admin.productos.exportar') }}"
                class="btn btn-info btn-round">
                <i class="fa fa-file-excel"></i> Exportar Excel
            </a>

            <a href="{{ route('admin.productos.carga.index') }}" class="btn btn-success btn-round">
                <i class="fa fa-file-excel"></i> Carga Masiva
            </a>

            <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus"></i> Nuevo Producto
            </button>

        </div>

    </div>

    <div class="card">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.productos.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Buscar producto</label>
                    <input type="text" name="nombre" class="form-control form-control-sm" placeholder="Nombre del producto"
                        value="{{ $filtros['nombre'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Marca</label>
                    <select name="id_marca" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($marcas as $marca)
                        <option value="{{ $marca->id_marca }}" {{ (string)($filtros['id_marca'] ?? '') === (string)$marca->id_marca ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Proveedor</label>
                    <select name="id_proveedor" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id_proveedor }}" {{ (string)($filtros['id_proveedor'] ?? '') === (string)$prov->id_proveedor ? 'selected' : '' }}>
                            {{ $prov->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-sm btn-primary btn-round">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('admin.productos.index') }}" class="btn btn-sm btn-secondary btn-round">
                        <i class="fa fa-eraser"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Proveedor</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($productos as $p)
                        <tr>
                            <td>{{ $p->id_producto }}</td>
                            <td>{{ $p->nombre }}</td>
                            <td>{{ $p->marca->nombre ?? '-' }}</td>
                            <td>{{ $p->proveedor->nombre ?? '-' }}</td>

                            <td>
                                <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex flex-wrap gap-1">

                                    <button class="btn btn-sm btn-primary btn-border btn-round btn-edit-producto"
                                        data-id="{{ $p->id_producto }}"
                                        aria-label="Editar {{ $p->nombre }}" title="Editar">
                                        <i class="fa fa-edit"></i> Editar
                                    </button>

                                    <form action="{{ route('admin.productos.destroy', $p->id_producto) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger btn-border btn-round btn-delete"
                                            aria-label="Eliminar {{ $p->nombre }}" title="Eliminar">
                                            <i class="fa fa-trash"></i> Eliminar
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.variantes.index', $p->id_producto) }}"
                                        class="btn btn-sm btn-info btn-round"
                                        aria-label="Variantes de {{ $p->nombre }}" title="Variantes">
                                        <i class="fa fa-cubes"></i> Variantes
                                    </a>

                                    <a href="{{ route('admin.producto_imagen.index', $p->id_producto) }}"
                                        class="btn btn-sm btn-warning btn-round"
                                        aria-label="Imágenes de {{ $p->nombre }}" title="Imágenes">
                                        <i class="fa fa-image"></i> Imágenes
                                    </a>

                                </div>
                            </td>
                        </tr>

                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<!-- Contenedor del modal de edición (se carga bajo demanda por AJAX) -->
<div id="modalEditWrap"></div>

@include('admin.productos.modals.create')

<script>
// Este script corre antes de que cargue jQuery (el layout lo incluye al final),
// por eso se usa JS puro. bootstrap/Swal/tinymce ya están disponibles al hacer clic.
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-edit-producto');
    if (!btn) return;
    e.preventDefault();
    cargarEdicion(btn.getAttribute('data-id'));
});

function cargarEdicion(id) {
    const wrap = document.getElementById('modalEditWrap');
    const url = '{{ route('admin.productos.index') }}/' + id + '/editar';

    if (window.adminLoader) adminLoader.show('Cargando producto...');

    fetch(url, { headers: { 'Accept': 'text/html' } })
        .then(function(r) {
            if (!r.ok) throw new Error();
            return r.text();
        })
        .then(function(html) {
            wrap.innerHTML = html;

            const el = document.getElementById('edit' + id);
            if (!el) throw new Error();

            const modal = bootstrap.Modal.getOrCreateInstance(el);

            el.addEventListener('hidden.bs.modal', function() {
                if (typeof tinymce !== 'undefined') {
                    tinymce.remove('#edit' + id + ' .editor');
                }
                wrap.innerHTML = '';
            });

            modal.show();

            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '#edit' + id + ' .editor',
                    height: 150,
                    menubar: false,
                    plugins: 'link lists paste',
                    toolbar: 'undo redo | bold italic | bullist numlist | link'
                });
            }

            if (window.adminLoader) adminLoader.hide();
        })
        .catch(function() {
            if (window.adminLoader) adminLoader.hide();

            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'No se pudo cargar el producto', 'error');
            }
        });
}
</script>

@endsection