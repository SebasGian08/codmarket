@extends('admin.layouts.app')

@section('title', 'Venta')

@section('content')
<div class="page-inner">

    @include('admin.partials.table_responsive')

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex flex-wrap align-items-center gap-2">
            <h4 class="page-title">Venta</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Nueva Venta</li>
            </ul>
        </div>

        <button class="btn btn-light border btn-round" id="btnLimpiarVenta">
            <i class="fa fa-rotate-left"></i> Nueva Venta
        </button>

    </div>

    <div class="row">

        <!-- ================= COLUMNA PRINCIPAL ================= -->
        <div class="col-lg-8">

            <!-- CLIENTE -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">

                    <label class="form-label small fw-semibold text-uppercase text-muted mb-2">
                        <i class="fa fa-user me-1"></i> Cliente
                    </label>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" id="clienteInput" class="form-control" list="clientesList"
                                value="CLIENTES VARIOS" autocomplete="off" placeholder="Buscar cliente...">

                            <datalist id="clientesList">
                                @foreach($clientes as $cl)
                                <option value="{{ $cl['nombre'] }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-4 d-flex align-items-center">
                            <span class="text-muted small">
                                <i class="fa fa-check-circle text-success me-1"></i>
                                Cliente seleccionado
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PRODUCTO -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">

                    <label class="form-label small fw-semibold text-uppercase text-muted mb-2">
                        <i class="fa fa-box-open me-1"></i> Producto
                    </label>

                    <div class="venta-buscador position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="productoInput" class="form-control" autocomplete="off"
                                placeholder="Buscar producto por nombre o SKU...">
                        </div>

                        <div id="productoResultados" class="venta-resultados d-none"></div>
                    </div>

                    <input type="hidden" id="productoId">

                </div>
            </div>

            <!-- VARIANTE + ATRIBUTOS -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">

                    <div class="row g-3">

                        <div class="col-md-7">
                            <label class="form-label small fw-semibold text-uppercase text-muted">
                                <i class="fa fa-cubes me-1"></i> Variante
                            </label>

                            <select id="varianteSelect" class="form-select" disabled>
                                <option value="">Selecciona un producto primero</option>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label small fw-semibold text-uppercase text-muted">
                                <i class="fa fa-tags me-1"></i> Atributos
                            </label>

                            <div id="atributosWrap" class="venta-atributos">
                                <span class="text-muted small">Selecciona una variante</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- PRECIO + CANTIDAD + SUBTOTAL + AGREGAR -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">

                    <div class="row g-3 align-items-end">

                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-semibold text-uppercase text-muted">Precio</label>
                            <div class="venta-precio" id="precioDisplay">
                                <span class="text-muted">—</span>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-semibold text-uppercase text-muted">Stock</label>
                            <div id="stockDisplay" class="fw-bold text-muted">—</div>
                        </div>

                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-semibold text-uppercase text-muted">Cantidad</label>
                            <div class="input-group">
                                <button class="btn btn-light border" type="button" id="btnMenos">−</button>
                                <input type="number" id="cantidadInput" class="form-control text-center" value="1"
                                    min="1">
                                <button class="btn btn-light border" type="button" id="btnMas">+</button>
                            </div>
                        </div>

                        <div class="col-6 col-md-3 text-md-end">
                            <label class="form-label small fw-semibold text-uppercase text-muted">Subtotal</label>
                            <div class="venta-subtotal" id="subtotalDisplay">S/ 0.00</div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-primary btn-round w-100" id="btnAgregar" type="button">
                                <i class="fa fa-cart-plus"></i> Agregar al detalle
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <!-- DETALLE DE VENTA -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-shopping-cart me-1"></i> Detalle de venta
                    </h6>
                    <span class="badge bg-primary" id="itemsBadge">0 items</span>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-cards mb-0" id="cartTable">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Variante</th>
                                    <th>Atributos</th>
                                    <th class="text-center">Cant.</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-end">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="cartBody"></tbody>
                        </table>
                    </div>

                    <div id="cartVacio" class="text-center text-muted py-5">
                        <i class="fa fa-cart-arrow-down fa-2x d-block mb-2 opacity-50"></i>
                        Aún no hay productos en la venta
                    </div>

                </div>
            </div>

        </div>

        <!-- ================= RESUMEN ================= -->
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm venta-resumen">
                <div class="card-body">

                    <h6 class="fw-bold text-uppercase text-muted small mb-3">
                        <i class="fa fa-calculator me-1"></i> Resumen
                    </h6>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Cliente</span>
                        <span class="fw-semibold text-truncate" id="resumenCliente">CLIENTES VARIOS</span>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Items</span>
                        <span class="fw-semibold" id="resumenItems">0</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h6 mb-0">TOTAL</span>
                        <span class="venta-total" id="totalDisplay">S/ 0.00</span>
                    </div>

                    <button class="btn btn-success btn-lg w-100 btn-round mb-2" id="btnRegistrarVenta" type="button">
                        <i class="fa fa-check-circle"></i> Registrar Venta
                    </button>

                    <button class="btn btn-light border w-100 btn-round" id="btnLimpiarCarrito" type="button">
                        <i class="fa fa-trash"></i> Limpiar detalle
                    </button>

                </div>
            </div>

        </div>

    </div>

</div>

<style>
    .venta-resultados {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .12);
        border-radius: .5rem;
        margin-top: 4px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .12);
        max-height: 280px;
        overflow-y: auto;
    }

    html[data-theme="dark"] .venta-resultados {
        background: var(--ka-surface, #273243);
        border-color: var(--ka-border, #3a4658);
    }

    .venta-resultado {
        padding: .55rem .9rem;
        cursor: pointer;
        border-bottom: 1px dashed rgba(0, 0, 0, .06);
    }

    .venta-resultado:last-child {
        border-bottom: 0;
    }

    .venta-resultado:hover {
        background: rgba(0, 0, 0, .04);
    }

    html[data-theme="dark"] .venta-resultado {
        border-bottom-color: var(--ka-border, #3a4658);
    }

    html[data-theme="dark"] .venta-resultado:hover {
        background: rgba(255, 255, 255, .06);
    }

    .venta-precio {
        font-size: 1.15rem;
        font-weight: 700;
    }

    .venta-subtotal {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--bs-primary);
    }

    .venta-total {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--bs-success);
    }

    .venta-resumen {
        position: sticky;
        top: 1rem;
    }
</style>

<script>
var PRODUCTOS = @json($productos);

var carrito = [];
var productoActual = null;
var varianteActual = null;

function escapeHtml(s) {
    return String(s == null ? '' : s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function moneda(n) {
    return 'S/ ' + parseFloat(n || 0).toFixed(2);
}

function precioVariante(v) {
    return (v.precio_oferta != null && v.precio_oferta < v.precio) ? v.precio_oferta : v.precio;
}

/* ============ PRODUCTO: BUSCADOR ============ */
function renderProductos(termino) {
    var lista = document.getElementById('productoResultados');
    var t = (termino || '').trim().toLowerCase();

    if (!t) {
        lista.classList.add('d-none');
        lista.innerHTML = '';
        return;
    }

    var matches = PRODUCTOS.filter(function(p) {
        var nombre = (p.nombre || '').toLowerCase();
        var skus = (p.variantes || []).map(function(v) { return v.sku || ''; }).join(' ');
        return nombre.indexOf(t) > -1 || skus.toLowerCase().indexOf(t) > -1;
    });

    if (!matches.length) {
        lista.innerHTML = '<div class="venta-resultado py-2 text-muted">Sin resultados</div>';
        lista.classList.remove('d-none');
        return;
    }

    var html = matches.slice(0, 12).map(function(p) {
        var v = p.variantes[0];
        var info = v ? moneda(precioVariante(v)) : 'sin variantes';
        var extra = p.variantes.length > 1 ? p.variantes.length + ' variantes' : '1 variante';

        return '<div class="venta-resultado" data-id="' + p.id + '">' +
            '<div class="fw-semibold">' + escapeHtml(p.nombre) + '</div>' +
            '<div class="small text-muted">' + extra + ' · ' + info + '</div>' +
            '</div>';
    }).join('');

    lista.innerHTML = html;
    lista.classList.remove('d-none');
}

document.getElementById('productoInput').addEventListener('input', function(e) {
    renderProductos(e.target.value);
});

document.getElementById('productoResultados').addEventListener('mousedown', function(e) {
    var item = e.target.closest('.venta-resultado');
    if (!item || !item.dataset.id) return;
    e.preventDefault();
    seleccionarProducto(parseInt(item.dataset.id));
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.venta-buscador')) {
        document.getElementById('productoResultados').classList.add('d-none');
    }
});

/* ============ SELECCIÓN DE PRODUCTO / VARIANTE ============ */
function seleccionarProducto(id) {
    productoActual = PRODUCTOS.find(function(p) { return p.id === id; });
    if (!productoActual) return;

    document.getElementById('productoInput').value = productoActual.nombre;
    document.getElementById('productoId').value = productoActual.id;
    document.getElementById('productoResultados').classList.add('d-none');

    var sel = document.getElementById('varianteSelect');

    if (!productoActual.variantes.length) {
        sel.innerHTML = '<option value="">Sin variantes disponibles</option>';
        sel.disabled = true;
        varianteActual = null;
        limpiarDatosVariante();
        return;
    }

    sel.innerHTML = productoActual.variantes.map(function(v) {
        return '<option value="' + v.id + '">' +
            escapeHtml(v.sku || 'SKU ' + v.id) + ' · ' + moneda(precioVariante(v)) + ' · Stock: ' + v.stock +
            '</option>';
    }).join('');

    sel.disabled = false;
    seleccionarVariante(productoActual.variantes[0].id);
}

function seleccionarVariante(id) {
    if (!productoActual) return;

    varianteActual = productoActual.variantes.find(function(v) { return v.id === id; }) || null;
    document.getElementById('varianteSelect').value = id;

    if (!varianteActual) {
        limpiarDatosVariante();
        return;
    }

    var wrap = document.getElementById('atributosWrap');

    if (varianteActual.atributos && varianteActual.atributos.length) {
        wrap.innerHTML = varianteActual.atributos.map(function(a) {
            return '<span class="badge bg-light text-dark border me-1">' +
                escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor) + '</span>';
        }).join('');
    } else {
        wrap.innerHTML = '<span class="text-muted small">Sin atributos</span>';
    }

    var pre = varianteActual.precio;
    var oferta = varianteActual.precio_oferta;

    if (oferta != null && oferta < pre) {
        document.getElementById('precioDisplay').innerHTML =
            '<span class="text-decoration-line-through text-muted me-1">' + moneda(pre) + '</span>' +
            '<span class="fw-bold text-success">' + moneda(oferta) + '</span>';
    } else {
        document.getElementById('precioDisplay').innerHTML = '<span class="fw-bold">' + moneda(pre) + '</span>';
    }

    var st = varianteActual.stock;
    var stEl = document.getElementById('stockDisplay');
    stEl.textContent = st;
    stEl.className = 'fw-bold ' + (st <= 5 ? 'text-danger' : 'text-muted');

    document.getElementById('cantidadInput').value = 1;
    actualizarSubtotal();
}

function limpiarDatosVariante() {
    document.getElementById('precioDisplay').innerHTML = '<span class="text-muted">—</span>';
    document.getElementById('stockDisplay').textContent = '—';
    document.getElementById('stockDisplay').className = 'fw-bold text-muted';
    document.getElementById('atributosWrap').innerHTML = '<span class="text-muted small">Selecciona una variante</span>';
    document.getElementById('subtotalDisplay').textContent = 'S/ 0.00';
}

document.getElementById('varianteSelect').addEventListener('change', function() {
    if (this.value) seleccionarVariante(parseInt(this.value));
});

/* ============ CANTIDAD Y SUBTOTAL ============ */
function actualizarSubtotal() {
    if (!varianteActual) {
        document.getElementById('subtotalDisplay').textContent = 'S/ 0.00';
        return;
    }

    var cant = parseInt(document.getElementById('cantidadInput').value) || 1;
    document.getElementById('subtotalDisplay').textContent = moneda(precioVariante(varianteActual) * cant);
}

document.getElementById('btnMenos').addEventListener('click', function() {
    var c = document.getElementById('cantidadInput');
    if (parseInt(c.value) > 1) c.value = parseInt(c.value) - 1;
    actualizarSubtotal();
});

document.getElementById('btnMas').addEventListener('click', function() {
    var c = document.getElementById('cantidadInput');
    c.value = parseInt(c.value) + 1;
    actualizarSubtotal();
});

document.getElementById('cantidadInput').addEventListener('input', function() {
    var c = parseInt(this.value);
    if (!c || c < 1) this.value = 1;
    actualizarSubtotal();
});

/* ============ AGREGAR AL CARRITO ============ */
document.getElementById('btnAgregar').addEventListener('click', agregarAlCarrito);

function agregarAlCarrito() {
    if (!productoActual || !varianteActual) {
        Swal.fire('Atención', 'Selecciona un producto y una variante', 'warning');
        return;
    }

    var cant = parseInt(document.getElementById('cantidadInput').value) || 1;

    if (varianteActual.stock > 0 && cant > varianteActual.stock) {
        Swal.fire('Stock insuficiente', 'Stock disponible: ' + varianteActual.stock, 'warning');
        return;
    }

    var existente = carrito.find(function(i) { return i.id_variante === varianteActual.id; });

    if (existente) {
        var nueva = existente.cantidad + cant;

        if (varianteActual.stock > 0 && nueva > varianteActual.stock) {
            Swal.fire('Stock insuficiente', 'Stock disponible: ' + varianteActual.stock, 'warning');
            return;
        }

        existente.cantidad = nueva;
    } else {
        carrito.push({
            id_variante: varianteActual.id,
            producto: productoActual.nombre,
            variante: varianteActual.sku || ('SKU ' + varianteActual.id),
            atributos: varianteActual.atributos || [],
            precio: precioVariante(varianteActual),
            cantidad: cant,
            stock: varianteActual.stock
        });
    }

    renderCarrito();

    Swal.fire({
        icon: 'success',
        title: 'Agregado',
        text: productoActual.nombre,
        timer: 900,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

/* ============ CARRITO ============ */
function renderCarrito() {
    var body = document.getElementById('cartBody');
    var vacio = document.getElementById('cartVacio');

    if (!carrito.length) {
        body.innerHTML = '';
        vacio.style.display = 'block';
    } else {
        vacio.style.display = 'none';

        body.innerHTML = carrito.map(function(item, idx) {
            var attrs = item.atributos.length
                ? item.atributos.map(function(a) {
                    return '<span class="badge bg-light text-dark border me-1">' +
                        escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor) + '</span>';
                }).join(' ')
                : '<span class="text-muted">—</span>';

            return '<tr>' +
                '<td data-label="Producto">' + escapeHtml(item.producto) + '</td>' +
                '<td data-label="Variante">' + escapeHtml(item.variante) + '</td>' +
                '<td data-label="Atributos">' + attrs + '</td>' +
                '<td data-label="Cantidad" class="text-center">' +
                    '<div class="input-group input-group-sm d-inline-flex" style="width:110px">' +
                        '<button class="btn btn-light border btn-cant-menos" data-idx="' + idx + '" type="button">−</button>' +
                        '<input type="number" class="form-control text-center carrito-cant" data-idx="' + idx + '" value="' + item.cantidad + '" min="1">' +
                        '<button class="btn btn-light border btn-cant-mas" data-idx="' + idx + '" type="button">+</button>' +
                    '</div>' +
                '</td>' +
                '<td data-label="Precio" class="text-end">' + moneda(item.precio) + '</td>' +
                '<td data-label="Subtotal" class="text-end fw-bold">' + moneda(item.precio * item.cantidad) + '</td>' +
                '<td class="table-card-actions text-end">' +
                    '<button class="btn btn-sm btn-danger btn-border btn-round btn-quitar-item" data-idx="' + idx + '" type="button" title="Quitar"><i class="fa fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }).join('');
    }

    actualizarResumen();
}

document.getElementById('cartBody').addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-quitar-item');
    if (btn) {
        carrito.splice(parseInt(btn.dataset.idx), 1);
        renderCarrito();
        return;
    }

    var menos = e.target.closest('.btn-cant-menos');
    if (menos) {
        var i = parseInt(menos.dataset.idx);
        if (carrito[i].cantidad > 1) carrito[i].cantidad--;
        renderCarrito();
        return;
    }

    var mas = e.target.closest('.btn-cant-mas');
    if (mas) {
        var i2 = parseInt(mas.dataset.idx);
        var stock = carrito[i2].stock;
        if (stock > 0 && carrito[i2].cantidad >= stock) {
            Swal.fire('Stock insuficiente', 'Stock disponible: ' + stock, 'warning');
            return;
        }
        carrito[i2].cantidad++;
        renderCarrito();
    }
});

document.getElementById('cartBody').addEventListener('input', function(e) {
    var inp = e.target.closest('.carrito-cant');
    if (!inp) return;

    var i = parseInt(inp.dataset.idx);
    var c = parseInt(inp.value);

    if (!c || c < 1) {
        carrito[i].cantidad = 1;
    } else if (carrito[i].stock > 0 && c > carrito[i].stock) {
        carrito[i].cantidad = carrito[i].stock;
    } else {
        carrito[i].cantidad = c;
    }

    renderCarrito();
});

function actualizarResumen() {
    var total = 0;
    var items = 0;

    carrito.forEach(function(i) {
        total += i.precio * i.cantidad;
        items += i.cantidad;
    });

    document.getElementById('totalDisplay').textContent = moneda(total);
    document.getElementById('resumenItems').textContent = items;
    document.getElementById('itemsBadge').textContent = items + (items === 1 ? ' item' : ' items');
}

/* ============ CLIENTE ============ */
document.getElementById('clienteInput').addEventListener('input', function() {
    var v = this.value.trim() || 'CLIENTES VARIOS';
    document.getElementById('resumenCliente').textContent = v;
});

/* ============ REGISTRAR / LIMPIAR ============ */
document.getElementById('btnRegistrarVenta').addEventListener('click', function() {
    if (!carrito.length) {
        Swal.fire('Atención', 'Agrega al menos un producto', 'warning');
        return;
    }

    var cliente = document.getElementById('clienteInput').value.trim() || 'CLIENTES VARIOS';
    var total = 0;
    var items = 0;

    carrito.forEach(function(i) {
        total += i.precio * i.cantidad;
        items += i.cantidad;
    });

    Swal.fire({
        icon: 'success',
        title: 'Venta registrada',
        html: '<div style="text-align:left;">' +
            '<b>Cliente:</b> ' + escapeHtml(cliente) + '<br>' +
            '<b>Items:</b> ' + items + '<br>' +
            '<b>Total:</b> ' + moneda(total) +
            '</div>',
        confirmButtonText: 'OK'
    }).then(function() {
        limpiarTodo();
    });
});

document.getElementById('btnLimpiarCarrito').addEventListener('click', function() {
    if (!carrito.length) return;

    Swal.fire({
        title: '¿Limpiar detalle?',
        text: 'Se eliminarán todos los productos de la venta',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then(function(r) {
        if (r.isConfirmed) {
            carrito = [];
            renderCarrito();
        }
    });
});

document.getElementById('btnLimpiarVenta').addEventListener('click', limpiarTodo);

function limpiarTodo() {
    carrito = [];
    productoActual = null;
    varianteActual = null;

    document.getElementById('productoInput').value = '';
    document.getElementById('productoId').value = '';
    document.getElementById('cantidadInput').value = 1;

    var sel = document.getElementById('varianteSelect');
    sel.innerHTML = '<option value="">Selecciona un producto primero</option>';
    sel.disabled = true;

    limpiarDatosVariante();
    renderCarrito();
}

/* ============ INIT ============ */
renderCarrito();
</script>

@endsection
