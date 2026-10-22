@extends('admin.layouts.app')

@section('title', 'Ingresos / Ajuste de mercadería')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Ingresos / Ajuste de mercadería</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo movimiento
        </button>

    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card ingreso-filtros-card">
        <div class="card-body">
            <!-- <div class="ingreso-filtros-heading">
                <div>
                    <h5>Filtros</h5>
                    <small>Busca y organiza ingresos y ajustes de mercadería.</small>
                </div>
                <i class="fa fa-sliders-h" aria-hidden="true"></i>
            </div> -->
            <form method="GET" action="{{ route('admin.ingresos.index') }}" class="row g-3 align-items-end ingreso-filtros">
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label fw-semibold">N° / Documento</label>
                    <input type="text" name="numero" class="form-control" placeholder="ING-0001"
                        value="{{ $filtros['numero'] ?? '' }}">
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label fw-semibold">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="compra" {{ ($filtros['tipo'] ?? '') === 'compra' ? 'selected' : '' }}>Compra</option>
                        <option value="ajuste_suma" {{ ($filtros['tipo'] ?? '') === 'ajuste_suma' ? 'selected' : '' }}>Ajuste suma</option>
                        <option value="ajuste_resta" {{ ($filtros['tipo'] ?? '') === 'ajuste_resta' ? 'selected' : '' }}>Ajuste resta</option>
                        <option value="devolucion" {{ ($filtros['tipo'] ?? '') === 'devolucion' ? 'selected' : '' }}>Devolución</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label fw-semibold">Tienda</label>
                    <select name="id_tienda" class="form-select">
                        <option value="">Todas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}" {{ (string)($filtros['id_tienda'] ?? '') === (string)$tienda->id_tienda ? 'selected' : '' }}>
                            [{{ $tienda->codigo }}] {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label fw-semibold">Proveedor</label>
                    <select name="id_proveedor" class="form-select">
                        <option value="">Todos</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id_proveedor }}" {{ (string)($filtros['id_proveedor'] ?? '') === (string)$prov->id_proveedor ? 'selected' : '' }}>
                            {{ $prov->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" {{ ($filtros['estado'] ?? '') === '1' ? 'selected' : '' }}>Registrado</option>
                        <option value="0" {{ ($filtros['estado'] ?? '') === '0' ? 'selected' : '' }}>Anulado</option>
                    </select>
                </div>
                <div class="col-12 col-xl-6">
                    <label class="form-label fw-semibold">Rango de fechas</label>
                    <div class="input-group ingreso-fechas">
                        <input type="date" name="fecha_desde" class="form-control"
                            aria-label="Fecha desde" value="{{ $filtros['fecha_desde'] ?? '' }}">
                        <span class="input-group-text">hasta</span>
                        <input type="date" name="fecha_hasta" class="form-control"
                            aria-label="Fecha hasta" value="{{ $filtros['fecha_hasta'] ?? '' }}">
                    </div>
                </div>
                <div class="col-12 col-xl-6 d-flex flex-wrap justify-content-xl-end gap-2 ingreso-filtros-actions">
                    <button type="submit" class="btn btn-primary btn-round">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('admin.ingresos.index') }}" class="btn btn-light border btn-round">
                        <i class="fa fa-eraser"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Tipo</th>
                            <th>Tienda</th>
                            <th>Proveedor / Venta</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ingresos as $ingreso)
                        <tr>
                            <td><span class="badge bg-dark">{{ $ingreso->numero }}</span></td>
                            <td>
                                @switch($ingreso->tipo)
                                    @case('compra')
                                        <span class="badge bg-primary">Compra</span>
                                        @break
                                    @case('ajuste_suma')
                                        <span class="badge bg-success">Ajuste suma</span>
                                        @break
                                    @case('ajuste_resta')
                                        <span class="badge bg-warning text-dark">Ajuste resta</span>
                                        @break
                                    @case('devolucion')
                                        <span class="badge bg-info text-dark">Devolución</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Ajuste anterior</span>
                                @endswitch
                            </td>
                            <td class="fw-semibold">{{ $ingreso->tienda->nombre }}</td>
                            <td>
                                @if($ingreso->tipo === 'devolucion')
                                    {{ $ingreso->venta->numero ?? 'Venta #' . $ingreso->id_venta }}
                                @else
                                    {{ $ingreso->proveedor->nombre ?? '—' }}
                                @endif
                            </td>
                            <td>{{ $ingreso->fecha->format('d/m/Y') }}</td>
                            <td>{{ $ingreso->usuario->nombres }} {{ $ingreso->usuario->apellidos }}</td>
                            <td>
                                <span class="badge {{ $ingreso->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $ingreso->estado ? 'Registrado' : 'Anulado' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-info btn-border btn-round btn-ver-detalle"
                                    data-url="{{ route('admin.ingresos.detalle', $ingreso->id_ingreso) }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @if($ingreso->estado)
                                <button class="btn btn-sm btn-danger btn-round btn-anular"
                                    data-url="{{ route('admin.ingresos.anular', $ingreso->id_ingreso) }}"
                                    data-tipo="{{ $ingreso->tipo }}">
                                    <i class="fa fa-ban"></i> Anular
                                </button>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.ingresos.modals.create')
@include('admin.ingresos.modals.detalle_modal')

@push('scripts')
<script>
    var VARIANTES = @json($variantes);

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    var VARIANTES_DEVOLUCION = [];

    /* ============ BÚSQUEDA DE VARIANTE ============ */
    $('#varianteInputIngreso').on('input', function() {
        var t = $(this).val().trim().toLowerCase();
        var lista = $('#varianteResultadosIngreso');

        if (!t) {
            lista.addClass('d-none').html('');
            return;
        }

        if ($('#tipoIngreso').val() === 'devolucion' && !$('#idVentaDevolucion').val()) {
            lista.html('<div class="ingreso-resultado py-2 text-muted">Primero selecciona la venta.</div>')
                .removeClass('d-none');
            return;
        }

        // helper: texto de atributos (para buscar por talla/color/etc.)
        function textoAtributos(v) {
            return (v.atributos || []).map(function(a) {
                return (a.atributo || '') + ' ' + (a.valor || '');
            }).join(' ').toLowerCase();
        }

        var catalogo = $('#tipoIngreso').val() === 'devolucion' ? VARIANTES_DEVOLUCION : VARIANTES;
        var matches = catalogo.filter(function(v) {
            return (v.producto || '').toLowerCase().indexOf(t) > -1 ||
                (v.sku || '').toLowerCase().indexOf(t) > -1 ||
                textoAtributos(v).indexOf(t) > -1;
        });

        // helper: badges de atributos (igual que inventario)
        function badgesAtributos(v) {
            return (v.atributos || []).map(function(a) {
                return '<span class="badge bg-light text-dark border me-1">' +
                    escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor) + '</span>';
            }).join('');
        }

        if (!matches.length) {
            lista.html('<div class="ingreso-resultado py-2 text-muted">Sin resultados</div>');
        } else {
            lista.html(matches.slice(0, 12).map(function(v) {
                return '<div class="ingreso-resultado" data-id="' + v.id + '">' +
                    '<div class="fw-semibold">' + escapeHtml(v.producto) + '</div>' +
                    '<div class="small mb-1">' + badgesAtributos(v) + '</div>' +
                    '<div class="small text-muted">' + escapeHtml(v.sku || 'Sin SKU') +
                    (v.cantidad_disponible != null ? ' · Disponible para devolver: ' + v.cantidad_disponible :
                        ' · Costo: S/ ' + parseFloat(v.costo || 0).toFixed(2)) + '</div>' +
                    '</div>';
            }).join(''));
        }

        lista.removeClass('d-none');
    });

    $(document).on('mousedown', '#varianteResultadosIngreso .ingreso-resultado', function(e) {
        e.preventDefault();

        var id = parseInt($(this).data('id'));
        var catalogo = $('#tipoIngreso').val() === 'devolucion' ? VARIANTES_DEVOLUCION : VARIANTES;
        var v = catalogo.find(function(x) { return x.id === id; });

        if (!v) return;

        agregarFilaIngreso(v, 1, null, v.cantidad_disponible);

        $('#varianteInputIngreso').val('');
        $('#varianteResultadosIngreso').addClass('d-none').html('');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ingreso-buscador').length) {
            $('#varianteResultadosIngreso').addClass('d-none');
        }
    });

    /* ============ FILAS DEL DETALLE ============ */
    function agregarFilaIngreso(v, cantidad, costo, cantidadMaxima) {
        var filas = $('#ingresoFilas');

        if (filas.find('input[name="items[' + v.id + '][id_variante]"]').length) {
            Swal.fire('Atención', 'El producto ya está en el detalle', 'warning');
            return;
        }

        var attrHtml = (v.atributos || []).map(function(a) {
            return '<span class="badge bg-light text-dark border me-1">' +
                escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor) + '</span>';
        }).join('');

        var fila = $(`
            <tr data-variante="${v.id}">
                <td class="fw-semibold">${escapeHtml(v.producto)}
                    ${attrHtml ? '<div class="small mt-1">' + attrHtml + '</div>' : ''}<br>
                    <span class="small text-muted">${escapeHtml(v.sku || 'Sin SKU')}</span>
                </td>
                <td style="width:120px">
                    <input type="number" class="form-control form-control-sm ingreso-cantidad" value="${cantidad || 1}"
                        min="1" ${cantidadMaxima != null ? 'max="' + cantidadMaxima + '"' : ''} required>
                    <input type="hidden" name="items[${v.id}][id_variante]" value="${v.id}">
                </td>
                <td style="width:130px">
                    <input type="number" class="form-control form-control-sm ingreso-costo" value="${costo != null ? costo : v.costo}"
                        min="0" step="0.01" required>
                    <input type="hidden" name="items[${v.id}][cantidad]" value="${cantidad || 1}">
                    <input type="hidden" name="items[${v.id}][costo]" value="${costo != null ? costo : v.costo}">
                </td>
                <td class="text-end ingreso-subtotal fw-bold">S/ 0.00</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-border btn-round btn-quitar-ingreso">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        filas.append(fila);
        actualizarFilaIngreso(fila);
    }

    $(document).on('click', '.btn-quitar-ingreso', function() {
        $(this).closest('tr').remove();
        actualizarTotalIngreso();
    });

    $(document).on('input', '.ingreso-cantidad, .ingreso-costo', function() {
        var fila = $(this).closest('tr');
        var nombre = $(this).hasClass('ingreso-cantidad') ? 'cantidad' : 'costo';

        if (nombre === 'cantidad') {
            var maximo = parseInt($(this).attr('max'), 10);
            var cantidad = parseInt($(this).val(), 10);
            if (!isNaN(maximo) && cantidad > maximo) {
                $(this).val(maximo);
            }
        }

        fila.find('input[name^="items["][name$="[' + nombre + ']"]').val($(this).val());
        actualizarFilaIngreso(fila);
    });

    function actualizarFilaIngreso(fila) {
        var cant = parseFloat(fila.find('.ingreso-cantidad').val()) || 0;
        var costo = parseFloat(fila.find('.ingreso-costo').val()) || 0;
        fila.find('.ingreso-subtotal').text('S/ ' + (cant * costo).toFixed(2));
        actualizarTotalIngreso();
    }

    function actualizarTotalIngreso() {
        var total = 0;
        $('#ingresoFilas tr').each(function() {
            var cant = parseFloat($(this).find('.ingreso-cantidad').val()) || 0;
            var costo = parseFloat($(this).find('.ingreso-costo').val()) || 0;
            total += cant * costo;
        });
        $('#ingresoTotal').text('S/ ' + total.toFixed(2));
    }

    /* ============ TIPO DE INGRESO ============ */
    function cambiarTipoIngreso() {
        var tipo = $('#tipoIngreso').val();
        var ayudas = {
            compra: 'La cantidad recibida se suma al stock.',
            ajuste_suma: 'Indica las unidades que quieres sumar al stock.',
            ajuste_resta: 'Indica las unidades que quieres restar del stock.',
            devolucion: 'Registra unidades de una venta que regresan a la tienda donde se vendieron.'
        };

        $('#ingresoFilas').empty();
        actualizarTotalIngreso();
        $('#buscarVentaDevolucion').val('');
        $('#idVentaDevolucion').val('');
        $('#ventaResultadosDevolucion').addClass('d-none').empty();
        $('#varianteInputIngreso').val('');
        $('#varianteResultadosIngreso').addClass('d-none').empty();
        VARIANTES_DEVOLUCION = [];
        $('#tiendaIngreso').prop('disabled', false);
        $('#tiendaDevolucionHidden').remove();

        if (tipo === 'compra') {
            $('#proveedorWrapIngreso').removeClass('d-none');
            $('#proveedorIngreso').prop('required', true);
        } else {
            $('#proveedorWrapIngreso').addClass('d-none');
            $('#proveedorIngreso').prop('required', false).val('');
        }

        $('#devolucionVentaWrap').toggleClass('d-none', tipo !== 'devolucion');
        $('#tipoIngresoAyuda').text(ayudas[tipo] || '');
        $('#varianteInputIngreso').prop('disabled', tipo === 'devolucion');
        $('#varianteInputIngreso').attr('placeholder', tipo === 'devolucion'
            ? 'Selecciona una venta para ver sus productos...'
            : 'Buscar por nombre, SKU, talla o color...');
    }

    $('#tipoIngreso').on('change', cambiarTipoIngreso);

    $('#buscarVentaDevolucion').on('input', function() {
        var termino = $(this).val().trim();
        var resultados = $('#ventaResultadosDevolucion');

        $('#idVentaDevolucion').val('');
        VARIANTES_DEVOLUCION = [];
        $('#varianteInputIngreso').prop('disabled', true);
        $('#varianteResultadosIngreso').addClass('d-none').empty();
        $('#ingresoFilas').empty();
        actualizarTotalIngreso();

        if (termino.length < 2) {
            resultados.addClass('d-none').empty();
            return;
        }

        $.get("{{ route('admin.ingresos.ventas.buscar') }}", { q: termino })
            .done(function(ventas) {
                if (!ventas.length) {
                    resultados.html('<div class="ingreso-resultado py-2 text-muted">No se encontraron ventas.</div>');
                } else {
                    resultados.html(ventas.map(function(venta) {
                        return '<button type="button" class="ingreso-resultado w-100 text-start border-0 bg-white" ' +
                            'data-id="' + venta.id + '" data-tienda="' + venta.id_tienda + '">' +
                            '<span class="fw-semibold">' + escapeHtml(venta.numero) + '</span>' +
                            '<span class="small text-muted d-block">' + escapeHtml(venta.cliente) +
                            ' · ' + escapeHtml(venta.fecha) + '</span></button>';
                    }).join(''));
                }
                resultados.removeClass('d-none');
            })
            .fail(function() {
                resultados.html('<div class="ingreso-resultado py-2 text-danger">No se pudieron buscar las ventas.</div>')
                    .removeClass('d-none');
            });
    });

    $(document).on('mousedown', '#ventaResultadosDevolucion .ingreso-resultado[data-id]', function(e) {
        e.preventDefault();

        var idVenta = $(this).data('id');
        var textoVenta = $(this).find('.fw-semibold').text();
        $('#buscarVentaDevolucion').val(textoVenta);
        $('#idVentaDevolucion').val(idVenta);
        $('#ventaResultadosDevolucion').addClass('d-none').empty();
        $('#varianteInputIngreso').prop('disabled', true);
        $('#tiendaIngreso').val($(this).data('tienda')).prop('disabled', true);

        if ($('#tiendaDevolucionHidden').length === 0) {
            $('<input>', { type: 'hidden', name: 'id_tienda', id: 'tiendaDevolucionHidden' })
                .appendTo('#formIngreso');
        }
        $('#tiendaDevolucionHidden').val($(this).data('tienda'));

        var urlVariantes = "{{ route('admin.ingresos.ventas.devolucion', ['id' => '__ID__']) }}"
            .replace('__ID__', idVenta);
        $.get(urlVariantes)
            .done(function(data) {
                VARIANTES_DEVOLUCION = data.variantes || [];
                $('#varianteInputIngreso').prop('disabled', VARIANTES_DEVOLUCION.length === 0);

                if (!VARIANTES_DEVOLUCION.length) {
                    Swal.fire('Sin unidades disponibles', 'Esta venta ya no tiene unidades pendientes de devolución.', 'info');
                } else {
                    $('#varianteInputIngreso').attr('placeholder', 'Buscar producto de la venta seleccionada...');
                }
            })
            .fail(function() {
                $('#idVentaDevolucion').val('');
                Swal.fire('Error', 'No se pudieron cargar los productos de la venta.', 'error');
            });
    });

    /* ============ SUBMIT ============ */
    $('#formIngreso').on('submit', function(e) {
        var filas = $('#ingresoFilas tr').length;

        if ($('#tipoIngreso').val() === 'devolucion' && !$('#idVentaDevolucion').val()) {
            e.preventDefault();
            Swal.fire('Atención', 'Selecciona la venta asociada a la devolución.', 'warning');
            return;
        }

        if (!filas) {
            e.preventDefault();
            Swal.fire('Atención', 'Agrega al menos un producto al detalle', 'warning');
            return;
        }

        $('#btnGuardarIngreso').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    });

    /* ============ VER DETALLE ============ */
    $(document).on('click', '.btn-ver-detalle', function() {
        var url = $(this).data('url');

        $.get(url, function(html) {
            $('#modalDetalleIngreso .modal-content').html(html);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleIngreso')).show();
        });
    });

    /* ============ ANULAR ============ */
    $(document).on('click', '.btn-anular', function() {
        var url = $(this).data('url');
        var tipo = $(this).data('tipo');
        var textoAnulacion = tipo === 'ajuste_resta'
            ? 'Se devolverán al stock las unidades restadas.'
            : 'Se revertirá el movimiento de stock registrado.';

        Swal.fire({
            title: '¿Anular ingreso?',
            text: textoAnulacion + ' Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.post(url, { _token: '{{ csrf_token() }}' })
                    .done(function() {
                        Swal.fire({ icon: 'success', title: 'Anulado', timer: 1200, showConfirmButton: false });
                        setTimeout(function() { location.reload(); }, 800);
                    })
                    .fail(function(xhr) {
                        Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo anular', 'error');
                    });
            }
        });
    });

    cambiarTipoIngreso();
</script>
@endpush

<style>
    .ingreso-resultados {
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
        max-height: 260px;
        overflow-y: auto;
    }

    html[data-theme="dark"] .ingreso-resultados {
        background: var(--ka-surface, #273243);
        border-color: var(--ka-border, #3a4658);
    }

    .ingreso-resultado {
        padding: .55rem .9rem;
        cursor: pointer;
        border-bottom: 1px dashed rgba(0, 0, 0, .06);
    }

    .ingreso-resultado:last-child {
        border-bottom: 0;
    }

    .ingreso-resultado:hover {
        background: rgba(0, 0, 0, .04);
    }

    html[data-theme="dark"] .ingreso-resultado:hover {
        background: rgba(255, 255, 255, .06);
    }
</style>

@endsection
