@extends('admin.layouts.app')

@section('title', 'Productos')

@section('content')

<div class="page-inner">

    @include('admin.partials.table_responsive')

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex flex-wrap align-items-center gap-2">
            <h4 class="page-title">Productos</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Listado</li>
            </ul>
        </div>

        <div class="d-grid d-sm-flex gap-2 flex-sm-wrap">

            <a href="{{ route('admin.productos.exportar') }}"
                class="btn btn-info btn-round">
                <i class="fa fa-file-excel"></i> Exportar Excel
            </a>

            <button class="btn btn-success btn-round" data-bs-toggle="modal" data-bs-target="#modalImportar">
                <i class="fa fa-file-excel"></i> Importar Excel
            </button>

            <button class="btn btn-secondary btn-round" data-bs-toggle="modal" data-bs-target="#modalImportarVariantes">
                <i class="fa fa-cubes"></i> Importar Variantes
            </button>

            <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa fa-plus"></i> Nuevo Producto
            </button>

        </div>

    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-striped table-hover table-cards" id="basic-datatables">

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
                            <td data-label="ID">{{ $p->id_producto }}</td>
                            <td data-label="Nombre">{{ $p->nombre }}</td>
                            <td data-label="Marca">{{ $p->marca->nombre ?? '-' }}</td>
                            <td data-label="Proveedor">{{ $p->proveedor->nombre ?? '-' }}</td>

                            <td data-label="Estado">
                                <span class="badge {{ $p->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $p->estado ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td class="table-card-actions">
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
@include('admin.productos.modals.importar')
@include('admin.productos.modals.importar_variantes')

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
        })
        .catch(function() {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'No se pudo cargar el producto', 'error');
            }
        });
}
</script>

@endsection