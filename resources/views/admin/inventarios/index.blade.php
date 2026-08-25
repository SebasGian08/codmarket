@extends('admin.layouts.app')

@section('title', 'Inventario')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Inventario</h4>
        </div>
    </div>

    <div class="row">

        <!-- ================= COLUMNA FILTROS ================= -->
        <div class="col-lg-4">

            <!-- BUSCADOR DE PRODUCTO -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body py-3">

                    <div class="inv-buscador position-relative">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="productoInput" class="form-control"
                                autocomplete="off" autofocus
                                placeholder="Buscar producto por nombre o SKU...">
                        </div>

                        <div id="productoResultados" class="venta-resultados d-none"></div>
                    </div>

                    <input type="hidden" id="productoId">

                </div>
            </div>

            <!-- CATÁLOGO RÁPIDO -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-th-large me-1"></i> Productos
                    </h6>
                    <span class="text-muted small">{{ $productos->count() }}</span>
                </div>

                <div class="card-body">
                    <div class="venta-catalogo" id="catalogoGrid">

                        @foreach($productos as $p)
                        @php($vP = $p['variantes'][0] ?? null)
                        <button type="button" class="venta-prod-btn" data-id="{{ $p['id'] }}">
                            <div class="venta-prod-img">
                                <img src="{{ $p['imagen'] }}" alt="{{ $p['nombre'] }}" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/tienda_virtual/default.png') }}'">
                            </div>
                            <div class="venta-prod-nombre">{{ $p['nombre'] }}</div>
                            <div class="venta-prod-stock" data-variantes-count>{{ count($p['variantes']) }} var.</div>
                        </button>
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- FILTRO DE TIENDA -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <label class="form-label small fw-semibold text-muted mb-2">
                        <i class="fa fa-store me-1"></i> Tienda
                    </label>
                    <select id="tiendaSelect" class="form-select form-select-sm">
                        <option value="">Todas las tiendas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}">{{ $tienda->codigo }} - {{ $tienda->nombre }}</option>
                        @endforeach
                    </select>
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

                    <div class="inv-variante-buscador position-relative mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="varianteInput" class="form-control"
                                autocomplete="off"
                                placeholder="Buscar variante por SKU, atributo...">
                        </div>
                    </div>

                    <div id="atributosFiltros" class="venta-atributos-filtros mb-3 d-none"></div>

                    <div class="venta-variantes-grid" id="variantesGrid"></div>

                    <div id="seleccionadasWrap" class="d-none mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <small class="fw-bold text-muted text-uppercase">
                                <i class="fa fa-check-circle text-success me-1"></i> Seleccionadas
                                <span class="badge bg-success ms-1" id="seleccionadasBadge">0</span>
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btnLimpiarSeleccion" title="Quitar todas">
                                <i class="fa fa-times"></i> Limpiar
                            </button>
                        </div>
                        <div id="seleccionadasList" class="d-flex flex-wrap gap-1"></div>
                    </div>

                </div>
            </div>

        </div>

        <!-- ================= COLUMNA RESULTADOS ================= -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fa fa-list me-1"></i> Stock por variante
                    </h6>
                    <span class="badge bg-primary" id="resultadosBadge">0 registros</span>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-cards mb-0" id="resultadosTable">
                            <thead>
                                <tr>
                                    <th>Modelo</th>
                                    <th>Variante</th>
                                    <th>Tienda</th>
                                    <th class="text-center">Stock</th>
                                </tr>
                            </thead>
                            <tbody id="resultadosBody"></tbody>
                            <tfoot id="resultadosFoot" class="d-none">
                                <tr class="table-active fw-bold">
                                    <td colspan="3" class="text-end">Total</td>
                                    <td class="text-center" id="totalStockCell">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div id="resultadosVacio" class="text-center text-muted py-5">
                        <i class="fa fa-search fa-2x d-block mb-2 opacity-50"></i>
                        Selecciona un producto para ver su stock
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<script>
    var PRODUCTOS = @json($productos);
    var STOCK_POR_TIENDA = @json($stockPorTienda);
    var TIENDAS = @json($tiendas->map(function ($t) {
        return ['id' => $t->id_tienda, 'codigo' => $t->codigo, 'nombre' => $t->nombre];
    })->values());

    var productoActual = null;
    var variantesSeleccionadas = [];
    var atributosActivos = {};

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function stockVariante(idVariante, idTienda) {
        var m = STOCK_POR_TIENDA[idVariante];
        if (!m) return 0;
        if (idTienda) return parseInt(m[idTienda]) || 0;
        var total = 0;
        Object.keys(m).forEach(function(k) { total += parseInt(m[k]) || 0; });
        return total;
    }

    function stockPorTiendaMap(idVariante) {
        return STOCK_POR_TIENDA[idVariante] || {};
    }

    /* ============ KPIs ============ */
    function actualizarKPIs() {
        var totalVariantes = 0;
        var conStock = 0;
        var valorizado = 0;

        var idTienda = document.getElementById('tiendaSelect').value;

        PRODUCTOS.forEach(function(p) {
            (p.variantes || []).forEach(function(v) {
                if (!v.estado) return;
                totalVariantes++;
                var st = stockVariante(v.id, idTienda || null);
                if (st > 0) {
                    conStock++;
                    valorizado += st * (v.costo || 0);
                }
            });
        });

        document.getElementById('kpiVariantes').textContent = totalVariantes;
        document.getElementById('kpiConStock').textContent = conStock;
        document.getElementById('kpiValorizado').textContent = 'S/ ' + valorizado.toFixed(2);
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
            var extra = p.variantes.length + ' variantes';

            return '<div class="venta-resultado" data-id="' + p.id + '">' +
                '<div class="fw-semibold">' + escapeHtml(p.nombre) + '</div>' +
                '<div class="small text-muted">' + extra + '</div>' +
                '</div>';
        }).join('');

        lista.innerHTML = html;
        lista.classList.remove('d-none');
    }

    document.getElementById('productoInput').addEventListener('input', function(e) {
        renderProductos(e.target.value);

        var botones = document.querySelectorAll('#catalogoGrid .venta-prod-btn');
        var t = this.value.trim().toLowerCase();
        botones.forEach(function(b) {
            b.style.display = (!t || b.textContent.toLowerCase().indexOf(t) > -1) ? '' : 'none';
        });
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
        if (!e.target.closest('.inv-buscador')) {
            document.getElementById('productoResultados').classList.add('d-none');
        }
    });

    /* ============ CATÁLOGO CLICK ============ */
    document.getElementById('catalogoGrid').addEventListener('click', function(e) {
        var btn = e.target.closest('.venta-prod-btn');
        if (!btn || !btn.dataset.id) return;
        seleccionarProducto(parseInt(btn.dataset.id));
    });

    /* ============ SELECCIÓN DE PRODUCTO ============ */
    function seleccionarProducto(id) {
        productoActual = PRODUCTOS.find(function(p) { return p.id === id; });
        if (!productoActual) return;

        document.getElementById('productoInput').value = productoActual.nombre;
        document.getElementById('productoId').value = productoActual.id;
        document.getElementById('productoResultados').classList.add('d-none');

        document.querySelectorAll('#catalogoGrid .venta-prod-btn').forEach(function(b) {
            b.classList.toggle('seleccionado', parseInt(b.dataset.id) === productoActual.id);
        });

        document.getElementById('varianteProductoNombre').textContent = productoActual.nombre;

        var card = document.getElementById('varianteCard');
        var grid = document.getElementById('variantesGrid');
        var input = document.getElementById('varianteInput');
        input.value = '';

        variantesSeleccionadas = [];
        renderSeleccionadas();

        if (!productoActual.variantes.length) {
            grid.innerHTML = '<div class="text-muted text-center py-3">Sin variantes</div>';
            renderAtributosFiltros();
            card.classList.remove('d-none');
            renderResultados();
            return;
        }

        renderAtributosFiltros();
        renderVariantesGrid(productoActual.variantes);
        card.classList.remove('d-none');
        input.focus();
    }

    function renderVariantesGrid(variantes) {
        var grid = document.getElementById('variantesGrid');

        if (!variantes || !variantes.length) {
            grid.innerHTML = '<div class="text-muted text-center py-3">Sin variantes disponibles</div>';
            return;
        }

        var defaultImg = '{{ asset("assets/images/tienda_virtual/default.png") }}';
        var idTienda = document.getElementById('tiendaSelect').value;

        grid.innerHTML = variantes.map(function(v) {
            var attrs = (v.atributos || []).map(function(a) {
                return '<span class="badge bg-light text-dark border">' +
                    escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor) + '</span>';
            }).join('');

            var st = stockVariante(v.id, idTienda || null);
            var stockClass = st <= 0 ? ' sin-stock' : '';
            var imgSrc = v.imagen || defaultImg;
            var seleccionada = variantesSeleccionadas.indexOf(v.id) > -1;
            var selClass = seleccionada ? ' seleccionada' : '';

            return '<button type="button" class="venta-var-btn' + selClass + '" data-variante="' + v.id + '">' +
                '<div class="venta-var-img">' +
                '<img src="' + escapeHtml(imgSrc) + '" alt="" loading="lazy" ' +
                'onerror="this.onerror=null;this.src=\'' + defaultImg + '\'">' +
                '</div>' +
                (attrs ? '<div class="venta-var-atributos">' + attrs + '</div>' : '') +
                '<div class="venta-var-sku">' + escapeHtml(v.sku || 'SKU ' + v.id) + '</div>' +
                '<div class="venta-var-stock' + stockClass + '">' + st + ' uds</div>' +
                '</button>';
        }).join('');
    }

    /* ============ FILTRO DE ATRIBUTOS ============ */
    function renderAtributosFiltros() {
        var container = document.getElementById('atributosFiltros');
        atributosActivos = {};

        if (!productoActual || productoActual.variantes.length <= 1) {
            container.classList.add('d-none');
            container.innerHTML = '';
            return;
        }

        var grupos = {};
        productoActual.variantes.forEach(function(v) {
            (v.atributos || []).forEach(function(a) {
                var key = a.atributo;
                if (!grupos[key]) grupos[key] = {};
                grupos[key][a.valor] = true;
            });
        });

        var grupoKeys = Object.keys(grupos);
        if (!grupoKeys.length) {
            container.classList.add('d-none');
            container.innerHTML = '';
            return;
        }

        var html = '';
        grupoKeys.forEach(function(nombre, idx) {
            if (idx > 0) {
                html += '<div class="atributo-separador"></div>';
            }
            html += '<div class="atributo-grupo">';
            html += '<span class="atributo-grupo-label">' + escapeHtml(nombre) + ':</span>';
            Object.keys(grupos[nombre]).forEach(function(valor) {
                var key = nombre + ':' + valor;
                html += '<button type="button" class="btn-filtro-atributo" data-attr="' + escapeHtml(key) + '">' +
                    escapeHtml(valor) + '</button>';
            });
            html += '</div>';
        });

        container.innerHTML = html;
        container.classList.remove('d-none');
    }

    function filtrarVariantes(term) {
        if (!productoActual) return;

        var t = (term || '').trim().toLowerCase();
        var claves = Object.keys(atributosActivos);

        var filtered = productoActual.variantes.filter(function(v) {
            if (claves.length) {
                var matchAll = claves.every(function(k) {
                    return (v.atributos || []).some(function(a) {
                        return (a.atributo + ':' + a.valor) === k;
                    });
                });
                if (!matchAll) return false;
            }

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

    function toggleAtributoFiltro(key) {
        if (atributosActivos[key]) {
            delete atributosActivos[key];
        } else {
            atributosActivos[key] = true;
        }

        document.querySelectorAll('#atributosFiltros .btn-filtro-atributo').forEach(function(btn) {
            btn.classList.toggle('active', !!atributosActivos[btn.getAttribute('data-attr')]);
        });

        filtrarVariantes(document.getElementById('varianteInput').value);
    }

    document.getElementById('atributosFiltros').addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-filtro-atributo');
        if (!btn) return;
        toggleAtributoFiltro(btn.getAttribute('data-attr'));
    });

    document.getElementById('varianteInput').addEventListener('input', function(e) {
        filtrarVariantes(e.target.value);
    });

    /* ============ CLICK EN VARIANTE: seleccionar/deseleccionar ============ */
    document.getElementById('variantesGrid').addEventListener('click', function(e) {
        var btn = e.target.closest('.venta-var-btn');
        if (!btn || !btn.dataset.variante) return;

        var idVar = parseInt(btn.dataset.variante);
        var idx = variantesSeleccionadas.indexOf(idVar);

        if (idx > -1) {
            variantesSeleccionadas.splice(idx, 1);
        } else {
            variantesSeleccionadas.push(idVar);
        }

        btn.classList.toggle('seleccionada');
        renderSeleccionadas();
        renderResultados();
    });

    /* ============ VARIANTES SELECCIONADAS: badges ============ */
    function renderSeleccionadas() {
        var wrap = document.getElementById('seleccionadasWrap');
        var list = document.getElementById('seleccionadasList');
        var badgeEl = document.getElementById('seleccionadasBadge');

        if (!variantesSeleccionadas.length) {
            wrap.classList.add('d-none');
            list.innerHTML = '';
            badgeEl.textContent = '0';
            return;
        }

        badgeEl.textContent = variantesSeleccionadas.length;

        var html = variantesSeleccionadas.map(function(idVar) {
            var v = productoActual.variantes.find(function(x) { return x.id === idVar; });
            if (!v) return '';

            var attrs = (v.atributos || []).map(function(a) { return a.valor; }).join(' ');
            var label = v.sku || ('SKU ' + v.id);
            if (attrs) label += ' · ' + attrs;

            return '<span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 d-inline-flex align-items-center gap-1">' +
                '<i class="fa fa-tag small"></i> ' + escapeHtml(label) +
                '<button type="button" class="btn-close btn-close-sm ms-1" data-quitar="' + v.id + '" style="font-size:.55rem;" title="Quitar"></button>' +
                '</span>';
        }).join('');

        list.innerHTML = html;
        wrap.classList.remove('d-none');
    }

    document.getElementById('seleccionadasList').addEventListener('click', function(e) {
        var btn = e.target.closest('[data-quitar]');
        if (!btn) return;

        var idVar = parseInt(btn.getAttribute('data-quitar'));
        var idx = variantesSeleccionadas.indexOf(idVar);
        if (idx > -1) variantesSeleccionadas.splice(idx, 1);

        document.querySelectorAll('#variantesGrid .venta-var-btn').forEach(function(b) {
            if (parseInt(b.dataset.variante) === idVar) b.classList.remove('seleccionada');
        });

        renderSeleccionadas();
        renderResultados();
    });

    document.getElementById('btnLimpiarSeleccion').addEventListener('click', function() {
        variantesSeleccionadas = [];
        document.querySelectorAll('#variantesGrid .venta-var-btn').forEach(function(b) {
            b.classList.remove('seleccionada');
        });
        renderSeleccionadas();
        renderResultados();
    });

    /* ============ BOTON VOLVER ============ */
    document.getElementById('btnDeseleccionarProducto').addEventListener('click', function() {
        productoActual = null;
        variantesSeleccionadas = [];
        atributosActivos = {};

        document.getElementById('productoInput').value = '';
        document.getElementById('productoId').value = '';
        document.getElementById('varianteCard').classList.add('d-none');
        document.getElementById('varianteInput').value = '';
        document.getElementById('variantesGrid').innerHTML = '';

        var filtros = document.getElementById('atributosFiltros');
        filtros.innerHTML = '';
        filtros.classList.add('d-none');

        document.querySelectorAll('#catalogoGrid .venta-prod-btn').forEach(function(b) {
            b.classList.remove('seleccionado');
        });

        renderSeleccionadas();
        renderResultados();
        document.getElementById('productoInput').focus();
    });

    /* ============ FILTRO TIENDA ============ */
    document.getElementById('tiendaSelect').addEventListener('change', function() {
        actualizarKPIs();

        if (productoActual) {
            renderVariantesGrid(productoActual.variantes);
        }

        renderResultados();
    });

    /* ============ RESULTADOS ============ */
    function renderResultados() {
        var body = document.getElementById('resultadosBody');
        var vacio = document.getElementById('resultadosVacio');
        var badge = document.getElementById('resultadosBadge');
        var table = document.getElementById('resultadosTable');
        var foot = document.getElementById('resultadosFoot');
        var totalCell = document.getElementById('totalStockCell');
        var idTienda = document.getElementById('tiendaSelect').value;
        var filas = [];
        var totalStock = 0;

        var tiendasAMostrar = idTienda
            ? TIENDAS.filter(function(t) { return String(t.id) === String(idTienda); })
            : TIENDAS;

        if (productoActual) {
            var variantesAMostrar = variantesSeleccionadas.length
                ? productoActual.variantes.filter(function(v) { return variantesSeleccionadas.indexOf(v.id) > -1; })
                : productoActual.variantes;

            if (!variantesAMostrar.length) {
                body.innerHTML = '';
                vacio.style.display = 'block';
                vacio.innerHTML = '<i class="fa fa-search fa-2x d-block mb-2 opacity-50"></i>Selecciona al menos una variante';
                table.classList.add('d-none');
                foot.classList.add('d-none');
                badge.textContent = '0 registros';
                return;
            }

            variantesAMostrar.forEach(function(v) {
                var attrs = (v.atributos || []).map(function(a) {
                    return a.atributo + ': ' + a.valor;
                }).join(', ') || '—';

                var varianteLabel = (v.sku || 'SKU ' + v.id) + (attrs !== ' —' ? ' · ' + attrs : '');

                tiendasAMostrar.forEach(function(t) {
                    var st = stockVariante(v.id, t.id);
                    totalStock += st;

                    filas.push(
                        '<tr>' +
                        '<td data-label="Modelo" class="fw-semibold">' + escapeHtml(productoActual.nombre) + '</td>' +
                        '<td data-label="Variante">' + escapeHtml(varianteLabel) + '</td>' +
                        '<td data-label="Tienda"><span class="badge bg-dark me-1">' + escapeHtml(t.codigo) + '</span> ' + escapeHtml(t.nombre) + '</td>' +
                        '<td data-label="Stock" class="text-center">' +
                        '<span class="badge ' + (st <= 0 ? 'bg-secondary' : (st <= 5 ? 'bg-warning' : 'bg-success')) + '">' + st + '</span>' +
                        '</td>' +
                        '</tr>'
                    );
                });
            });

        } else if (idTienda) {
            PRODUCTOS.forEach(function(p) {
                (p.variantes || []).forEach(function(v) {
                    tiendasAMostrar.forEach(function(t) {
                        var st = stockVariante(v.id, t.id);
                        if (st <= 0) return;
                        totalStock += st;

                        var attrs = (v.atributos || []).map(function(a) {
                            return a.atributo + ': ' + a.valor;
                        }).join(', ') || '—';

                        var varianteLabel = (v.sku || 'SKU ' + v.id) + (attrs !== ' —' ? ' · ' + attrs : '');

                        filas.push(
                            '<tr>' +
                            '<td data-label="Modelo" class="fw-semibold">' + escapeHtml(p.nombre) + '</td>' +
                            '<td data-label="Variante">' + escapeHtml(varianteLabel) + '</td>' +
                            '<td data-label="Tienda"><span class="badge bg-dark me-1">' + escapeHtml(t.codigo) + '</span> ' + escapeHtml(t.nombre) + '</td>' +
                            '<td data-label="Stock" class="text-center">' +
                            '<span class="badge ' + (st <= 5 ? 'bg-warning' : 'bg-success') + '">' + st + '</span>' +
                            '</td>' +
                            '</tr>'
                        );
                    });
                });
            });
        }

        if (!filas.length) {
            body.innerHTML = '';
            vacio.style.display = 'block';
            vacio.innerHTML = idTienda
                ? '<i class="fa fa-search fa-2x d-block mb-2 opacity-50"></i>Sin stock en esta tienda'
                : '<i class="fa fa-search fa-2x d-block mb-2 opacity-50"></i>Selecciona un producto o tienda para ver stock';
            table.classList.add('d-none');
            foot.classList.add('d-none');
        } else {
            body.innerHTML = filas.join('');
            vacio.style.display = 'none';
            table.classList.remove('d-none');
            foot.classList.remove('d-none');
            totalCell.textContent = totalStock;
        }

        badge.textContent = filas.length + ' registro' + (filas.length !== 1 ? 's' : '');
    }

    /* ============ INIT ============ */
    actualizarKPIs();
    renderResultados();
</script>

@endsection
