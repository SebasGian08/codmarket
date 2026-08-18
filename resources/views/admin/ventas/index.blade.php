@extends('admin.layouts.app')

@section('title', 'Venta')

@section('content')
<div class="page-inner">

    @include('admin.partials.table_responsive')

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex flex-wrap align-items-center gap-2">
            <h4 class="page-title">Venta</h4>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.ventas.historial') }}" class="btn btn-light border btn-round">
                <i class="fa fa-history"></i> Historial
            </a>

            <button class="btn btn-light border btn-round" id="btnLimpiarVenta">
                <i class="fa fa-rotate-left"></i> Nueva Venta
            </button>
        </div>

    </div>

    @if($cajasAbiertas->isEmpty())
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="fa fa-exclamation-triangle"></i>
        <span>No hay ninguna caja abierta. Debes abrir una caja para poder registrar ventas.</span>
        <a href="{{ route('admin.cajas.index') }}" class="btn btn-sm btn-warning ms-auto">
            <i class="fa fa-folder-open"></i> Ir a cajas
        </a>
    </div>
    @endif

    <div class="row">

        <!-- ================= COLUMNA PRINCIPAL ================= -->
        <div class="col-lg-8">

            <!-- CAJA ACTIVA + CLIENTE (compacto) -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-2">

                    <div class="row g-2 align-items-center">

                        <div class="col-sm-4">
                            <label class="form-label small fw-semibold text-uppercase text-muted mb-1">
                                <i class="fa fa-folder-open me-1"></i> Caja activa
                            </label>

                            @if($cajasAbiertas->isNotEmpty())
                            <select id="cajaSelect" class="form-select form-select-sm">
                                <option value="">Selecciona una caja</option>
                                @foreach($cajasAbiertas as $caja)
                                <option value="{{ $caja->id_caja }}" data-tienda="{{ $caja->id_tienda }}"
                                    data-vendedor="{{ $caja->vendedor->nombre ?? '' }}">
                                    {{ $caja->tienda->codigo }} - {{ $caja->tienda->nombre }} · {{ $caja->nombre }}
                                    (S/ {{ number_format($caja->monto_apertura, 2) }})
                                </option>
                                @endforeach
                            </select>
                            @else
                            <select class="form-select form-select-sm" disabled>
                                <option>Sin cajas abiertas</option>
                            </select>
                            @endif
                        </div>

                        <div class="col-sm-5">
                            <label class="form-label small fw-semibold text-uppercase text-muted mb-1">
                                <i class="fa fa-user me-1"></i> Cliente
                            </label>

                            <div class="position-relative" id="clienteCombobox">
                                <div class="input-group input-group-sm">
                                    <input type="text" id="clienteInput" class="form-control"
                                        value="{{ $clienteVarios->nombre ?? 'CLIENTES VARIOS' }}" autocomplete="off"
                                        placeholder="Buscar cliente...">

                                    <button class="btn btn-outline-secondary" type="button" id="btnClienteToggle"
                                        title="Mostrar clientes">
                                        <i class="fa fa-chevron-down"></i>
                                    </button>

                                    <button class="btn btn-primary" type="button" id="btnClienteNuevo"
                                        title="Añadir cliente nuevo">
                                        <i class="fa fa-user-plus"></i>
                                    </button>
                                </div>

                                <ul class="dropdown-menu shadow" id="clientesDropdown"
                                    style="position:absolute;top:100%;left:0;right:0;width:100%;margin-top:4px;z-index:1050;max-height:260px;overflow-y:auto;">
                                    @foreach($clientes as $cl)
                                    <li>
                                        <a class="dropdown-item cliente-item" href="#" tabindex="-1"
                                            data-id="{{ $cl->id_cliente }}" data-nombre="{{ $cl->nombre }}">
                                            <i class="fa fa-user text-muted me-1"></i> {{ $cl->nombre }}
                                            @if($cl->id_cliente == ($clienteVarios->id_cliente ?? null))
                                            <span class="badge bg-info ms-1">por defecto</span>
                                            @endif
                                        </a>
                                    </li>
                                    @endforeach
                                    <li id="clienteDropdownVacio" class="px-3 py-2 small text-muted"
                                        style="display:none;">
                                        Sin resultados: el nombre se guardará tal cual.
                                    </li>
                                </ul>
                            </div>

                            <input type="hidden" id="clienteId" value="{{ $clienteVarios->id_cliente ?? '' }}">
                        </div>

                        <div class="col-sm-3 text-sm-end">
                            <div id="cajaInfo" class="text-muted small text-sm-end">
                                <i class="fa fa-info-circle me-1"></i>
                                Selecciona una caja para iniciar la venta
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- BUSCADOR: EL PROTAGONISTA -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-4">

                    <div class="venta-buscador position-relative">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="productoInput" class="form-control form-control-lg"
                                autocomplete="off" autofocus
                                placeholder="Buscar producto por nombre o SKU...">
                        </div>

                        <div id="productoResultados" class="venta-resultados d-none"></div>
                    </div>

                    <input type="hidden" id="productoId">

                    <div class="text-muted small mt-2 text-center">
                        <i class="fa fa-keyboard me-1"></i>
                        Escribe el nombre o el SKU y presiona Enter: se agrega al instante
                    </div>

                </div>
            </div>

            <!-- CATÁLOGO RÁPIDO: productos sin buscar -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-th-large me-1"></i> Catálogo de productos
                    </h6>
                    <span class="text-muted small">{{ $productos->count() }} productos</span>
                </div>

                <div class="card-body">
                    <div class="venta-catalogo" id="catalogoGrid">

                        @foreach($productos as $p)
                        @php($vP = $p['variantes'][0] ?? null)
                        @php($precioP = $vP ? (($vP['precio_oferta'] !== null && $vP['precio_oferta'] < $vP['precio']) ? $vP['precio_oferta'] : $vP['precio']) : 0)
                        <button type="button" class="venta-prod-btn" data-id="{{ $p['id'] }}"
                            data-variante-principal="{{ $vP['id'] ?? '' }}"
                            @if(isset($p['variantes']) && count($p['variantes']) === 1) data-variante="{{ $p['variantes'][0]['id'] }}" @endif>
                            <div class="venta-prod-img">
                                <img src="{{ $p['imagen'] }}" alt="{{ $p['nombre'] }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/tienda_virtual/default.png') }}'">
                            </div>
                            <div class="venta-prod-nombre">{{ $p['nombre'] }}</div>
                            <div class="venta-prod-precio">{{ $p['variantes'] ? 'S/ ' . number_format($precioP, 2) : '—' }}</div>
                            <div class="venta-prod-stock" data-stock-principal>S/ 0.00</div>
                        </button>
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- VARIANTE (se muestra al elegir producto) -->
            <div class="card border-0 shadow-sm mb-3 d-none" id="varianteCard">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-cubes me-1"></i> Variantes de <span id="varianteProductoNombre"></span>
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeseleccionarProducto" title="Volver a productos">
                        <i class="fa fa-arrow-left"></i> Volver
                    </button>
                </div>

                <div class="card-body py-3">

                    <div class="venta-variante-buscador position-relative mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="varianteInput" class="form-control"
                                autocomplete="off"
                                placeholder="Buscar variante por SKU, atributo...">
                        </div>
                        <div id="varianteResultados" class="venta-resultados d-none"></div>
                    </div>

                    <div class="venta-variantes-grid" id="variantesGrid"></div>

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
                        <i class="fa fa-check-circle me-1"></i> Confirmar Venta
                    </h6>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Cliente</span>
                        <span class="fw-semibold text-truncate" id="resumenCliente">{{ $clienteVarios->nombre ?? 'CLIENTES VARIOS' }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Tienda</span>
                        <span class="fw-semibold text-truncate" id="resumenTienda">—</span>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Items</span>
                        <span class="fw-semibold" id="resumenItems">0</span>
                    </div>

                    <div class="text-center my-4">
                        <div class="small text-uppercase fw-semibold text-muted mb-1">Total</div>
                        <div class="venta-total" id="totalDisplay">S/ 0.00</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted mb-2">Método de pago</label>
                        <div class="d-flex flex-wrap gap-2" id="metodosPagoWrap">
                            @foreach($metodosPagos as $metodo)
                            <button type="button" class="btn btn-outline-primary btn-sm btn-metodo-pago"
                                data-id="{{ $metodo->id_metodo_pago }}"
                                data-codigo="{{ $metodo->codigo }}">
                                {{ $metodo->nombre }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <div id="efectivoBox" class="d-none mb-3">
                        <div class="venta-efectivo-box p-3 rounded-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Recibido</label>
                                    <input type="number" id="recibidoInput" class="form-control"
                                        min="0" step="0.01" placeholder="0.00" autocomplete="off">
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Vuelto</label>
                                    <div class="fs-4 fw-bold text-success" id="vueltoDisplay">S/ 0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="ventaError" class="alert alert-danger d-none mb-3"></div>

                    <button class="btn btn-success btn-lg w-100 btn-round mb-2" id="btnConfirmarVenta" type="button">
                        <i class="fa fa-check-circle"></i> Confirmar Venta
                    </button>

                    <button class="btn btn-light border w-100 btn-round" id="btnLimpiarCarrito" type="button">
                        <i class="fa fa-trash"></i> Limpiar detalle
                    </button>

                </div>
            </div>

        </div>

    </div>

</div>

<div class="modal fade" id="modalClienteRapido" tabindex="-1">
    <div class="modal-dialog">
        <form id="formClienteRapido">
            <div class="modal-content">

                <div class="modal-header">
                    <h5><i class="fa fa-user-plus me-1"></i> Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Nombre</label>
                        <input type="text" id="nuevoClienteNombre" class="form-control" required maxlength="150"
                            placeholder="Nombre del cliente">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-muted">Teléfono</label>
                        <input type="text" id="nuevoClienteTelefono" class="form-control" maxlength="30"
                            placeholder="Opcional">
                    </div>

                    <div id="clienteRapidoError" class="alert alert-danger d-none mb-0"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>

                    <button type="submit" class="btn btn-success" id="btnGuardarClienteRapido">
                        <i class="fa fa-save"></i> Guardar y seleccionar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    var PRODUCTOS = @json($productos);
    var CLIENTES = @json($clientes);
    var STOCK_POR_TIENDA = @json($stockPorTienda);
    var METODOS_PAGO = @json($metodosPagos->map(function ($m) {
        return ['id' => $m->id_metodo_pago, 'nombre' => $m->nombre, 'codigo' => $m->codigo];
    })->values());
    var CLIENTES_VARIOS = {
        id: {{ $clienteVarios->id_cliente ?? 'null' }},
        nombre: @json($clienteVarios->nombre ?? 'CLIENTES VARIOS')
    };

    var carrito = [];
    var productoActual = null;
    var varianteActual = null;
    var cajaActual = null;

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

    function stockVariante(idVariante) {
        if (!cajaActual) return 0;

        var m = STOCK_POR_TIENDA[idVariante];
        return m ? (parseInt(m[cajaActual.id_tienda]) || 0) : 0;
    }

    /* ============ CAJA ACTIVA ============ */
    function renderCaja() {
        var el = document.getElementById('cajaInfo');

        if (!cajaActual) {
            el.innerHTML = '<i class="fa fa-info-circle me-1"></i> Selecciona una caja para iniciar la venta';
            el.className = 'text-muted small text-sm-end';
            document.getElementById('resumenTienda').textContent = '—';
            return;
        }

        el.innerHTML =
            '<i class="fa fa-check-circle text-success me-1"></i> ' +
            '<b>' + escapeHtml(cajaActual.tienda.nombre) + '</b> · ' +
            escapeHtml(cajaActual.nombre) +
            '<br><span class="text-muted">Apertura: ' + moneda(cajaActual.monto_apertura) +
            (cajaActual.vendedor ? ' · Vendedor: <b>' + escapeHtml(cajaActual.vendedor) + '</b>' : '') +
            '</span>';
        el.className = 'small text-sm-end';

        document.getElementById('resumenTienda').textContent = cajaActual.tienda.nombre;

        revalidarCarrito();
        actualizarStockCatalogo();
    }

    var cajaSelect = document.getElementById('cajaSelect');

    if (cajaSelect && cajaSelect.options.length > 1) {
        cajaSelect.addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];

            if (!this.value) {
                cajaActual = null;
                renderCaja();
                return;
            }

            cajaActual = {
                id_caja: parseInt(this.value),
                id_tienda: parseInt(opt.dataset.tienda)
            };

            @foreach($cajasAbiertas as $caja)
            if ({{ $caja->id_caja }} === cajaActual.id_caja) {
                cajaActual.tienda = { nombre: @json($caja->tienda->nombre), codigo: @json($caja->tienda->codigo) };
                cajaActual.nombre = @json($caja->nombre);
                cajaActual.monto_apertura = {{ $caja->monto_apertura }};
                cajaActual.vendedor = @json($caja->vendedor->nombre ?? '');
            }
            @endforeach

            renderCaja();
        });
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
        var termino = e.target.value;
        renderProductos(termino);

        // Coincidencia exacta de SKU: agregar esa variante al instante
        var t = termino.trim().toLowerCase();
        if (!t) return;

        for (var i = 0; i < PRODUCTOS.length; i++) {
            var p = PRODUCTOS[i];
            var vens = p.variantes || [];
            for (var j = 0; j < vens.length; j++) {
                if ((vens[j].sku || '').toLowerCase() === t) {
                    seleccionarProducto(p.id, vens[j].id);
                    return;
                }
            }
        }
    });

    document.getElementById('productoInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var first = document.getElementById('productoResultados').querySelector('.venta-resultado');
            if (first && first.dataset.id) {
                seleccionarProducto(parseInt(first.dataset.id));
            }
        }
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

    /* ============ CATÁLOGO RÁPIDO ============ */
    function actualizarStockCatalogo() {
        var botones = document.querySelectorAll('#catalogoGrid .venta-prod-btn');

        botones.forEach(function(b) {
            var stEl = b.querySelector('[data-stock-principal]');
            if (!stEl) return;

            var idVariante = b.getAttribute('data-variante-principal');
            var st = 0;

            if (cajaActual && idVariante) {
                var m = STOCK_POR_TIENDA[idVariante];
                st = m ? (parseInt(m[cajaActual.id_tienda]) || 0) : 0;
            }

            stEl.textContent = st + (st === 1 ? ' ud' : ' uds');
            stEl.classList.toggle('sin-stock', st <= 0);
        });
    }

    document.getElementById('catalogoGrid').addEventListener('click', function(e) {
        var btn = e.target.closest('.venta-prod-btn');
        if (!btn || !btn.dataset.id) return;

        var id = parseInt(btn.dataset.id);
        var idVariante = btn.dataset.variante ? parseInt(btn.dataset.variante) : undefined;
        seleccionarProducto(id, idVariante);
    });

    document.getElementById('productoInput').addEventListener('input', function() {
        var t = this.value.trim().toLowerCase();
        var botones = document.querySelectorAll('#catalogoGrid .venta-prod-btn');

        botones.forEach(function(b) {
            b.style.display = (!t || b.textContent.toLowerCase().indexOf(t) > -1) ? '' : 'none';
        });
    });

    /* ============ SELECCIÓN DE PRODUCTO / VARIANTE ============ */
    function renderVariantesGrid(variantes) {
        var grid = document.getElementById('variantesGrid');

        if (!variantes || !variantes.length) {
            grid.innerHTML = '<div class="text-muted text-center py-3">Sin variantes disponibles</div>';
            return;
        }

        var defaultImg = '{{ asset("assets/images/tienda_virtual/default.png") }}';

        grid.innerHTML = variantes.map(function(v) {
            var attrs = (v.atributos || []).map(function(a) {
                return '<span class="badge bg-light text-dark border">' +
                    escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor) + '</span>';
            }).join('');

            var st = stockVariante(v.id);
            var stockClass = st <= 0 ? ' sin-stock' : '';
            var imgSrc = v.imagen || defaultImg;

            return '<button type="button" class="venta-var-btn" data-variante="' + v.id + '">' +
                '<div class="venta-var-img">' +
                '<img src="' + escapeHtml(imgSrc) + '" alt="" loading="lazy" ' +
                'onerror="this.onerror=null;this.src=\'' + defaultImg + '\'">' +
                '</div>' +
                (attrs ? '<div class="venta-var-atributos">' + attrs + '</div>' : '') +
                '<div class="venta-var-sku">' + escapeHtml(v.sku || 'SKU ' + v.id) + '</div>' +
                '<div class="venta-var-precio">' + moneda(precioVariante(v)) + '</div>' +
                '<div class="venta-var-stock' + stockClass + '">' + st + (st === 1 ? ' ud' : ' uds') + '</div>' +
                '</button>';
        }).join('');
    }

    function filtrarVariantes(term) {
        if (!productoActual) return;

        var t = (term || '').trim().toLowerCase();

        var filtered = productoActual.variantes.filter(function(v) {
            if (!t) return true;

            var sku = (v.sku || '').toLowerCase();
            if (sku.indexOf(t) > -1) return true;

            var attrs = (v.atributos || []).map(function(a) {
                return (a.atributo || '') + ' ' + (a.valor || '');
            }).join(' ').toLowerCase();

            return attrs.indexOf(t) > -1;
        });

        renderVariantesGrid(filtered);
    }

    function seleccionarProducto(id, idVariante) {
        productoActual = PRODUCTOS.find(function(p) { return p.id === id; });
        if (!productoActual) return;

        document.getElementById('productoInput').value = productoActual.nombre;
        document.getElementById('productoId').value = productoActual.id;
        document.getElementById('productoResultados').classList.add('d-none');

        // Resaltar producto seleccionado en catálogo
        document.querySelectorAll('#catalogoGrid .venta-prod-btn').forEach(function(b) {
            b.classList.toggle('seleccionado', parseInt(b.dataset.id) === productoActual.id);
        });

        // Mostrar nombre del producto en header de variantes
        document.getElementById('varianteProductoNombre').textContent = productoActual.nombre;

        var card = document.getElementById('varianteCard');
        var grid = document.getElementById('variantesGrid');
        var input = document.getElementById('varianteInput');
        input.value = '';

        if (!productoActual.variantes.length) {
            grid.innerHTML = '<div class="text-muted text-center py-3">Sin variantes disponibles</div>';
            card.classList.remove('d-none');
            return;
        }

        // SKU exacto buscado: se agrega esa variante directamente
        if (idVariante) {
            var vExacta = productoActual.variantes.find(function(x) { return x.id === idVariante; });
            if (vExacta) {
                renderVariantesGrid(productoActual.variantes);
                varianteActual = vExacta;
                card.classList.remove('d-none');
                if (agregarAlCarrito()) finalizarAgregado();
                return;
            }
        }

        // Una sola variante: se agrega al instante
        if (productoActual.variantes.length === 1) {
            var unica = productoActual.variantes[0];
            renderVariantesGrid(productoActual.variantes);
            varianteActual = unica;
            card.classList.remove('d-none');
            if (agregarAlCarrito()) finalizarAgregado();
            return;
        }

        // Varias variantes: mostrar grid para que el usuario elija
        renderVariantesGrid(productoActual.variantes);
        varianteActual = null;
        card.classList.remove('d-none');
        input.focus();
    }

    function seleccionarVariante(id) {
        if (!productoActual) return;

        varianteActual = productoActual.variantes.find(function(v) { return v.id === id; }) || null;

        if (!varianteActual) {
            return;
        }

        if (agregarAlCarrito()) {
            finalizarAgregado();
        }
    }

    // Click en grid de variantes
    document.getElementById('variantesGrid').addEventListener('click', function(e) {
        var btn = e.target.closest('.venta-var-btn');
        if (!btn || !btn.dataset.variante) return;
        seleccionarVariante(parseInt(btn.dataset.variante));
    });

    // Buscador de variantes
    document.getElementById('varianteInput').addEventListener('input', function(e) {
        filtrarVariantes(e.target.value);
    });

    document.getElementById('varianteInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            var first = document.getElementById('variantesGrid').querySelector('.venta-var-btn');
            if (first && first.dataset.variante) {
                seleccionarVariante(parseInt(first.dataset.variante));
            }
        }
    });

    // Botón volver / deseleccionar producto
    document.getElementById('btnDeseleccionarProducto').addEventListener('click', function() {
        productoActual = null;
        varianteActual = null;

        document.getElementById('productoInput').value = '';
        document.getElementById('productoId').value = '';
        document.getElementById('varianteCard').classList.add('d-none');
        document.getElementById('varianteInput').value = '';
        document.getElementById('variantesGrid').innerHTML = '';

        // Quitar resaltado de catálogo
        document.querySelectorAll('#catalogoGrid .venta-prod-btn').forEach(function(b) {
            b.classList.remove('seleccionado');
        });

        document.getElementById('productoInput').focus();
    });

    // Cerrar dropdown de resultados de variante al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.venta-variante-buscador')) {
            document.getElementById('varianteResultados').classList.add('d-none');
        }
    });

    /* ============ AGREGAR AUTOMÁTICO AL CARRITO ============ */
    function agregarAlCarrito() {
        if (!cajaActual) {
            Swal.fire('Atención', 'Selecciona una caja activa primero', 'warning');
            return false;
        }

        if (!productoActual || !varianteActual) {
            Swal.fire('Atención', 'Selecciona un producto y una variante', 'warning');
            return false;
        }

        var cant = 1;
        var stock = stockVariante(varianteActual.id);

        if (stock > 0 && cant > stock) {
            Swal.fire('Stock insuficiente', 'Stock disponible en tienda: ' + stock, 'warning');
            return false;
        }

        var existente = carrito.find(function(i) { return i.id_variante === varianteActual.id; });
        var esNuevo = !existente;

        if (existente) {
            var nueva = existente.cantidad + cant;

            if (stock > 0 && nueva > stock) {
                Swal.fire('Stock insuficiente', 'Stock disponible en tienda: ' + stock, 'warning');
                return false;
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
                stock: stock
            });
        }

        renderCarrito();
        resaltarItem(varianteActual.id);

        Swal.fire({
            icon: 'success',
            title: esNuevo ? 'Agregado' : 'Cantidad actualizada',
            text: productoActual.nombre,
            timer: 700,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });

        return true;
    }

    function finalizarAgregado() {
        productoActual = null;
        varianteActual = null;

        document.getElementById('productoInput').value = '';
        document.getElementById('productoId').value = '';
        document.getElementById('varianteCard').classList.add('d-none');
        document.getElementById('varianteInput').value = '';
        document.getElementById('variantesGrid').innerHTML = '';

        // Quitar resaltado de catálogo
        document.querySelectorAll('#catalogoGrid .venta-prod-btn').forEach(function(b) {
            b.classList.remove('seleccionado');
        });

        document.getElementById('productoInput').focus();
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

                return '<tr data-variante="' + item.id_variante + '">' +
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

    function resaltarItem(idVariante) {
        var filas = document.querySelectorAll('#cartBody tr');

        for (var i = 0; i < filas.length; i++) {
            if (parseInt(filas[i].getAttribute('data-variante')) === idVariante) {
                var tr = filas[i];
                tr.classList.add('venta-item-nuevo');
                (function(el) {
                    setTimeout(function() { el.classList.remove('venta-item-nuevo'); }, 1200);
                })(tr);
                break;
            }
        }
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

    function revalidarCarrito() {
        carrito.forEach(function(item) {
            var stock = stockVariante(item.id_variante);
            item.stock = stock;

            if (stock > 0 && item.cantidad > stock) {
                item.cantidad = stock;
            }
        });

        renderCarrito();
    }

    function calcularTotal() {
        var total = 0;

        carrito.forEach(function(i) {
            total += i.precio * i.cantidad;
        });

        return total;
    }

    function actualizarResumen() {
        var total = calcularTotal();
        var items = 0;

        carrito.forEach(function(i) {
            items += i.cantidad;
        });

        document.getElementById('totalDisplay').textContent = moneda(total);
        document.getElementById('resumenItems').textContent = items;
        document.getElementById('itemsBadge').textContent = items + (items === 1 ? ' item' : ' items');

        if (metodoPagoActual && esMetodoEfectivo(metodoPagoActual)) {
            var r = document.getElementById('recibidoInput');
            var actual = parseFloat(r.value) || 0;

            if (r.value === '' || actual === recibidoAuto) {
                r.value = total.toFixed(2);
                recibidoAuto = total;
            }

            calcularVuelto();
        }
    }

    /* ============ CLIENTE ============ */
    function seleccionarCliente(nombre, id) {
        document.getElementById('clienteInput').value = nombre;
        document.getElementById('clienteId').value = id || '';
        document.getElementById('resumenCliente').textContent = nombre || 'CLIENTES VARIOS';
    }

    var clienteInput = document.getElementById('clienteInput');
    var clienteDropdown = document.getElementById('clientesDropdown');
    var clienteDropdownVacio = document.getElementById('clienteDropdownVacio');

    function clienteFiltrar(q) {
        q = (q || '').toLowerCase();
        var visibles = 0;
        var items = clienteDropdown.querySelectorAll('.cliente-item');

        for (var i = 0; i < items.length; i++) {
            var a = items[i];
            var ok = !q || a.getAttribute('data-nombre').toLowerCase().indexOf(q) !== -1;
            a.parentNode.style.display = ok ? '' : 'none';
            if (ok) visibles++;
        }

        clienteDropdownVacio.style.display = visibles ? 'none' : '';

        return visibles;
    }

    function clienteAbrir() {
        clienteFiltrar(clienteInput.value.trim());
        clienteDropdown.classList.add('show');
    }

    function clienteCerrar() {
        clienteDropdown.classList.remove('show');
    }

    document.getElementById('btnClienteToggle').addEventListener('click', function(e) {
        e.stopPropagation();
        if (clienteDropdown.classList.contains('show')) {
            clienteCerrar();
        } else {
            clienteAbrir();
        }
    });

    clienteInput.addEventListener('focus', function() {
        clienteAbrir();
    });

    clienteInput.addEventListener('input', function() {
        var v = this.value.trim();

        if (clienteDropdown.classList.contains('show')) {
            clienteFiltrar(v);
        }

        if (!v) {
            seleccionarCliente(CLIENTES_VARIOS.nombre, CLIENTES_VARIOS.id);
            return;
        }

        var cliente = CLIENTES.find(function(c) { return c.nombre === v; });

        if (cliente) {
            seleccionarCliente(cliente.nombre, cliente.id_cliente);
        } else {
            seleccionarCliente(v, '');
        }
    });

    clienteInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            clienteCerrar();
        }
    });

    clienteDropdown.addEventListener('click', function(e) {
        var a = e.target.closest('.cliente-item');
        if (!a) return;
        e.preventDefault();
        seleccionarCliente(a.getAttribute('data-nombre'), a.getAttribute('data-id'));
        clienteCerrar();
    });

    document.addEventListener('click', function(e) {
        var box = document.getElementById('clienteCombobox');
        if (!box.contains(e.target)) {
            clienteCerrar();
        }
    });

    /* ============ CLIENTE: ALTA RÁPIDA ============ */
    document.getElementById('btnClienteNuevo').addEventListener('click', function() {
        document.getElementById('nuevoClienteNombre').value = '';
        document.getElementById('nuevoClienteTelefono').value = '';
        document.getElementById('clienteRapidoError').classList.add('d-none');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('modalClienteRapido')).show();
        setTimeout(function() { document.getElementById('nuevoClienteNombre').focus(); }, 300);
    });

    document.getElementById('formClienteRapido').addEventListener('submit', function(e) {
        e.preventDefault();

        var nombre = document.getElementById('nuevoClienteNombre').value.trim();
        if (!nombre) return;

        var telefono = document.getElementById('nuevoClienteTelefono').value.trim();
        var btn = document.getElementById('btnGuardarClienteRapido');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Guardando...';

        $.ajax({
            url: '{{ route("admin.clientes.store") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: nombre,
                telefono: telefono,
                estado: 1
            },
            dataType: 'json'
        }).done(function(res) {
            var nuevo = { id_cliente: res.id_cliente, nombre: res.nombre };
            CLIENTES.push(nuevo);

            var li = document.createElement('li');
            var a = document.createElement('a');
            a.className = 'dropdown-item cliente-item';
            a.href = '#';
            a.tabIndex = -1;
            a.setAttribute('data-id', res.id_cliente);
            a.setAttribute('data-nombre', res.nombre);
            a.innerHTML = '<i class="fa fa-user text-muted me-1"></i> ' + escapeHtml(res.nombre);
            li.appendChild(a);
            clienteDropdown.insertBefore(li, clienteDropdownVacio);

            seleccionarCliente(res.nombre, res.id_cliente);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalClienteRapido')).hide();

            Swal.fire({
                icon: 'success',
                title: 'Cliente agregado',
                text: res.nombre,
                timer: 900,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }).fail(function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo registrar el cliente';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                var errs = Object.values(xhr.responseJSON.errors);
                msg = errs.map(function(a) {
                    return Array.isArray(a) ? a.join(', ') : a;
                }).join('<br>');
            }

            var el = document.getElementById('clienteRapidoError');
            el.innerHTML = msg;
            el.classList.remove('d-none');
        }).always(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-save"></i> Guardar y seleccionar';
        });
    });

    /* ============ MÉTODO DE PAGO ============ */
    var metodoPagoActual = null;
    var recibidoAuto = null;

    function esMetodoEfectivo(m) {
        if (!m) return false;
        if (m.codigo) return String(m.codigo).toLowerCase() === 'efectivo';
        return (m.nombre || '').toLowerCase().indexOf('efectivo') !== -1;
    }

    function seleccionarMetodoPago(id) {
        metodoPagoActual = METODOS_PAGO.find(function(m) { return m.id === id; }) || null;

        document.querySelectorAll('#metodosPagoWrap .btn-metodo-pago').forEach(function(b) {
            b.classList.toggle('active', String(b.getAttribute('data-id')) === String(id));
        });

        var efectivo = esMetodoEfectivo(metodoPagoActual);
        document.getElementById('efectivoBox').classList.toggle('d-none', !efectivo);
        document.getElementById('ventaError').classList.add('d-none');

        if (efectivo) {
            var r = document.getElementById('recibidoInput');
            r.value = calcularTotal().toFixed(2);
            recibidoAuto = calcularTotal();
            calcularVuelto();
            r.focus();
        }
    }

    function calcularVuelto() {
        var total = calcularTotal();
        var rec = parseFloat(document.getElementById('recibidoInput').value) || 0;
        var vuelto = rec - total;
        var el = document.getElementById('vueltoDisplay');

        el.textContent = moneda(Math.max(0, vuelto));
        el.classList.toggle('text-danger', rec > 0 && vuelto < 0);
        document.getElementById('ventaError').classList.add('d-none');
    }

    document.getElementById('metodosPagoWrap').addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-metodo-pago');
        if (btn) seleccionarMetodoPago(parseInt(btn.getAttribute('data-id')));
    });

    document.getElementById('recibidoInput').addEventListener('input', calcularVuelto);

    document.getElementById('recibidoInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('btnConfirmarVenta').click();
        }
    });

    // Método por defecto: efectivo si existe, si no el primero
    (function() {
        var efectivo = METODOS_PAGO.filter(esMetodoEfectivo)[0] || null;
        var def = efectivo ? efectivo.id : (METODOS_PAGO.length ? METODOS_PAGO[0].id : null);
        if (def) seleccionarMetodoPago(def);
    })();

    /* ============ CONFIRMAR / LIMPIAR ============ */
    document.getElementById('btnConfirmarVenta').addEventListener('click', function() {
        if (!cajaActual) {
            Swal.fire('Atención', 'Selecciona una caja activa primero', 'warning');
            return;
        }

        if (!carrito.length) {
            Swal.fire('Atención', 'Agrega al menos un producto', 'warning');
            return;
        }

        if (!metodoPagoActual) {
            Swal.fire('Atención', 'Selecciona un método de pago', 'warning');
            return;
        }

        var montoRecibido = null;

        if (esMetodoEfectivo(metodoPagoActual)) {
            var rec = parseFloat(document.getElementById('recibidoInput').value) || 0;
            var total = calcularTotal();

            if (rec < total) {
                var err = document.getElementById('ventaError');
                err.textContent = 'El monto recibido es menor al total de la venta';
                err.classList.remove('d-none');
                return;
            }

            montoRecibido = rec;
        }

        var cliente = document.getElementById('clienteInput').value.trim() || 'CLIENTES VARIOS';
        var idCliente = document.getElementById('clienteId').value || null;

        var items = carrito.map(function(i) {
            return {
                id_variante: i.id_variante,
                cantidad: i.cantidad,
                precio: i.precio
            };
        });

        var btn = document.getElementById('btnConfirmarVenta');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Registrando...';

        var data = {
            _token: '{{ csrf_token() }}',
            id_caja: cajaActual.id_caja,
            id_cliente: idCliente,
            nombre_cliente: cliente,
            id_metodo_pago: metodoPagoActual.id,
            items: items
        };

        if (montoRecibido != null) data.monto_recibido = montoRecibido;

        $.ajax({
            url: '{{ route("admin.ventas.guardar") }}',
            type: 'POST',
            data: data,
            dataType: 'json'
        }).done(function(res) {
            Swal.fire({
                icon: 'success',
                title: 'Venta registrada',
                html: '<div style="text-align:left;">' +
                    '<b>N°:</b> ' + escapeHtml(res.numero) + '<br>' +
                    '<b>Cliente:</b> ' + escapeHtml(res.cliente) + '<br>' +
                    '<b>Tienda:</b> ' + escapeHtml(res.tienda) + ' · ' + escapeHtml(res.caja) + '<br>' +
                    (res.vendedor ? '<b>Vendedor:</b> ' + escapeHtml(res.vendedor) + '<br>' : '') +
                    '<b>Fecha:</b> ' + escapeHtml(res.fecha) + '<br>' +
                    '<b>Total:</b> ' + moneda(res.total) +
                    '</div>',
                confirmButtonText: 'OK'
            }).then(function() {
                limpiarTodo();
            });
        }).fail(function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo registrar la venta';
            Swal.fire('Error', msg, 'error');
        }).always(function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check-circle"></i> Confirmar Venta';
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
        document.getElementById('varianteCard').classList.add('d-none');
        document.getElementById('varianteInput').value = '';
        document.getElementById('variantesGrid').innerHTML = '';

        // Quitar resaltado de catálogo
        document.querySelectorAll('#catalogoGrid .venta-prod-btn').forEach(function(b) {
            b.classList.remove('seleccionado');
        });

        seleccionarCliente(CLIENTES_VARIOS.nombre, CLIENTES_VARIOS.id);
        renderCarrito();
        document.getElementById('productoInput').focus();
    }

    /* ============ INIT ============ */
    renderCarrito();
    actualizarStockCatalogo();

    // Si solo hay una caja abierta, se selecciona automáticamente
    @if($cajasAbiertas->count() === 1)
    (function() {
        var sel = document.getElementById('cajaSelect');
        if (sel) {
            sel.value = '{{ $cajasAbiertas->first()->id_caja }}';
            sel.dispatchEvent(new Event('change'));
        }
    })();
    @endif
</script>

@endsection
