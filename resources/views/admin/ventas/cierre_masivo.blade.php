@extends('admin.layouts.app')

@section('title', 'Cierre Masivo de Ventas')

@section('content')

<div class="page-inner cierre-masivo-page">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2">
            <div class="cierre-masivo-header-icon">
                <i class="fa fa-layer-group"></i>
            </div>
            <div>
                <h4 class="page-title mb-0">Cierre Masivo de Ventas</h4>
                <span class="text-muted small">{{ $ventas->count() }} venta(s) seleccionada(s) para cierre</span>
            </div>
        </div>
        <a href="{{ route('admin.ventas.cerrar') }}" class="btn btn-light border btn-round">
            <i class="fa fa-arrow-left"></i> Volver a Cerrar Venta
        </a>
    </div>

    <div class="alert alert-info d-flex align-items-center gap-2 cierre-masivo-info">
        <i class="fa fa-info-circle"></i>
        <div>Puedes ajustar cantidades, aplicar precio por mayor, descuentos por línea y dividir los pagos por cada venta de forma independiente.</div>
    </div>

    <form id="formCierreMasivo">
        @csrf
        @foreach($ventas as $venta)
        <div class="card mb-4 venta-masivo-card" data-venta-id="{{ $venta->id_venta }}">
            <div class="card-header cierre-masivo-card-header">
                <div class="cierre-masivo-venta-info">
                    <div class="cierre-masivo-venta-head">
                        <span class="cierre-masivo-venta-num">
                            <i class="fa fa-file-invoice"></i>
                            Venta <span class="badge bg-dark ms-1">{{ $venta->numero }}</span>
                        </span>
                        <span class="cierre-masivo-venta-total-label">
                            Total
                            <strong class="venta-total" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->total, 2) }}</strong>
                        </span>
                    </div>
                    <div class="cierre-masivo-venta-meta">
                        <div class="cierre-masivo-meta-item">
                            <i class="fa fa-user"></i>
                            <div>
                                <span class="cierre-masivo-meta-label">Cliente</span>
                                <span class="cierre-masivo-meta-value">{{ $venta->nombre_cliente }}</span>
                            </div>
                        </div>
                        <div class="cierre-masivo-meta-item">
                            <i class="fa fa-store"></i>
                            <div>
                                <span class="cierre-masivo-meta-label">Tienda</span>
                                <span class="cierre-masivo-meta-value">{{ $venta->tienda->nombre ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="cierre-masivo-meta-item">
                            <i class="fa fa-user-tie"></i>
                            <div>
                                <span class="cierre-masivo-meta-label">Vendedor</span>
                                <span class="cierre-masivo-meta-value">{{ $venta->vendedor->nombre ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="cierre-masivo-meta-item">
                            <i class="fa fa-calendar-alt"></i>
                            <div>
                                <span class="cierre-masivo-meta-label">Fecha</span>
                                <span class="cierre-masivo-meta-value">{{ $venta->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">

                <!-- DETALLE -->
                <div class="cierre-masivo-section">
                    <div class="cierre-masivo-section-header">
                        <div class="cierre-step-title">
                            <span class="cierre-step-num">1</span>
                            <div>
                                <h6 class="mb-0">Detalle de la venta</h6>
                                <span class="cierre-step-hint">Ajusta cantidades, aplica precio por mayor o descuentos por línea</span>
                            </div>
                        </div>
                    </div>
                    <div class="cierre-masivo-section-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0 cierre-masivo-table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center" style="width:100px">Cantidad</th>
                                        <th class="text-end" style="width:110px">Precio</th>
                                        <th class="text-end" style="width:160px">Precio por mayor</th>
                                        <th class="text-end" style="width:140px">Desc. (S/)</th>
                                        <th class="text-end" style="width:130px">Subtotal</th>
                                        <th class="text-center" style="width:60px"></th>
                                    </tr>
                                </thead>
                                <tbody class="detalle-body" data-venta-id="{{ $venta->id_venta }}">
                                    @foreach($venta->detalle as $d)
                                    @php
                                        $precioMayorista = $d->variante->precio_mayorista !== null ? (float)$d->variante->precio_mayorista : null;
                                        $precioOferta = $d->variante->precio_oferta !== null ? (float)$d->variante->precio_oferta : null;
                                        $precioRegular = $precioOferta !== null && $precioOferta < (float)$d->variante->precio ? $precioOferta : (float)$d->variante->precio;
                                        $precioActual = (float)$d->precio;
                                        $esMayorista = $precioMayorista !== null && abs($precioActual - $precioMayorista) < 0.001;
                                        $precioUsar = $esMayorista ? $precioMayorista : $precioActual;
                                        $precioRegularUsar = $esMayorista ? $precioRegular : $precioActual;
                                        $descuentoUnitario = 0;
                                        if (isset($d->tipo_descuento) && $d->tipo_descuento === 'MONTO') {
                                            $descuentoUnitario = min($precioUsar, (float)($d->valor_descuento_unitario ?? 0));
                                        }
                                    @endphp
                                    <tr class="detalle-row" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" data-id-variante="{{ $d->id_variante }}">
                                        <td>
                                            <div class="fw-semibold">{{ $d->variante->producto->nombre ?? '' }}</div>
                                            <div class="small text-muted">{{ $d->variante->sku ?? '' }}</div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center detalle-cantidad" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" value="{{ $d->cantidad }}" min="1">
                                        </td>
                                        <td class="text-end detalle-precio" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">{{ number_format($precioUsar, 2) }}</td>
                                        <td class="text-end">
                                            @if($precioMayorista !== null)
                                            <label class="cierre-mayorista-control {{ $esMayorista ? 'activo' : '' }}">
                                                <input type="checkbox" class="form-check-input detalle-mayorista" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" {{ $esMayorista ? 'checked' : '' }} data-precio-mayorista="{{ number_format($precioMayorista, 2, '.', '') }}" data-precio-regular="{{ number_format($precioRegularUsar, 2, '.', '') }}">
                                                <span>S/ {{ number_format($precioMayorista, 2) }}</span>
                                            </label>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" class="form-control form-control-sm text-end detalle-descuento" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" value="{{ number_format($descuentoUnitario, 2, '.', '') }}" placeholder="0.00">
                                        </td>
                                        <td class="text-end fw-bold detalle-subtotal" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">S/ {{ number_format($d->subtotal, 2) }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-light-danger detalle-quitar" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" title="Quitar producto">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="cierre-masivo-divider"></div>

                <!-- PAGOS -->
                <div class="cierre-masivo-section">
                    <div class="cierre-masivo-section-header">
                        <div class="cierre-step-title">
                            <span class="cierre-step-num cierre-step-num-success">2</span>
                            <div>
                                <h6 class="mb-0">Registrar pagos</h6>
                                <span class="cierre-step-hint">La suma de pagos debe igualar el total de esta venta</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-success btn-round agregar-pago" data-venta-id="{{ $venta->id_venta }}">
                            <i class="fa fa-plus"></i> Agregar pago
                        </button>
                    </div>
                    <div class="cierre-masivo-section-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0 cierre-masivo-table">
                                <thead>
                                    <tr>
                                        <th>Método de pago</th>
                                        <th>Cuenta bancaria / Destino</th>
                                        <th class="text-end" style="width:170px">Monto</th>
                                        <th class="text-center" style="width:60px"></th>
                                    </tr>
                                </thead>
                                <tbody class="pagos-body" data-venta-id="{{ $venta->id_venta }}">
                                    @forelse($venta->ventaPagos as $pago)
                                    <tr class="pago-row" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">
                                        <td>
                                            <select class="form-select form-select-sm pago-metodo" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">
                                                @foreach($metodosPagos as $mp)
                                                <option value="{{ $mp->id_metodo_pago }}" data-codigo="{{ strtolower($mp->codigo) }}" data-afecta="{{ $mp->afectaCuenta() ? '1' : '0' }}" {{ $mp->id_metodo_pago == $pago->id_metodo_pago ? 'selected' : '' }}>{{ $mp->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm pago-cuenta" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">
                                                <option value="">Sin cuenta</option>
                                                @foreach($cuentas as $c)
                                                <option value="{{ $c->id_cuenta_bancaria }}" {{ $pago->id_cuenta_bancaria == $c->id_cuenta_bancaria ? 'selected' : '' }}>{{ $c->nombre_banco }} - {{ $c->numero_cuenta }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.01" class="form-control form-control-sm text-end pago-monto" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" value="{{ number_format($pago->monto, 2, '.', '') }}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-icon btn-light-danger pago-quitar" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" title="Quitar pago">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="pago-empty" data-venta-id="{{ $venta->id_venta }}">
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fa fa-money-bill-wave me-1"></i> Sin pagos registrados. Agrega al menos un pago.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="cierre-masivo-resumen-wrap">
                            <div class="cierre-masivo-resumen-grid">
                                <div class="cierre-masivo-resumen-item">
                                    <span class="cierre-masivo-resumen-label">Total venta</span>
                                    <span class="cierre-masivo-resumen-value venta-res-total" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->total, 2) }}</span>
                                </div>
                                <div class="cierre-masivo-resumen-item">
                                    <span class="cierre-masivo-resumen-label">Total pagado</span>
                                    <span class="cierre-masivo-resumen-value text-success venta-res-pagado" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->ventaPagos->sum('monto'), 2) }}</span>
                                </div>
                                <div class="cierre-masivo-resumen-item">
                                    <span class="cierre-masivo-resumen-label">Diferencia</span>
                                    <span class="cierre-masivo-resumen-value venta-res-dif" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->total - $venta->ventaPagos->sum('monto'), 2) }}</span>
                                </div>
                                <div class="cierre-masivo-status-wrap">
                                    <span class="cierre-masivo-status venta-status" data-venta-id="{{ $venta->id_venta }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </form>

    <div class="cierre-masivo-footer-spacer"></div>

</div>

<div class="cierre-masivo-sticky-footer">
    <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <div class="cierre-masivo-footer-icon">
                <i class="fa fa-clipboard-check"></i>
            </div>
            <div>
                <div class="fw-semibold">Estado general</div>
                <div id="estadoGeneral" class="small text-muted">Revisa que todas las ventas estén cuadradas para habilitar el proceso</div>
            </div>
        </div>
        <button type="button" class="btn btn-success btn-lg btn-round px-4" id="btnProcesarMasivo" disabled>
            <i class="fa fa-lock"></i> Procesar cierre masivo
        </button>
    </div>
</div>

@push('scripts')
<script>
    var METODOS_PAGO = @json($metodosPagos);
    var CUENTAS = @json($cuentas);
    var VENTAS_INICIALES = {};

    function moneda(n) {
        return 'S/ ' + parseFloat(n || 0).toFixed(2);
    }

    function initVentas() {
        $('.venta-masivo-card').each(function() {
            var ventaId = $(this).data('venta-id');
            VENTAS_INICIALES[ventaId] = {
                detalle: [],
                pagos: []
            };
            $(this).find('.detalle-row').each(function() {
                var row = $(this);
                var cantidad = parseInt(row.find('.detalle-cantidad').val()) || 1;
                var precioMayorista = row.find('.detalle-mayorista').data('precio-mayorista');
                var esMayorista = row.find('.detalle-mayorista').is(':checked');
                var precioTexto = row.find('.detalle-precio').text().replace('S/', '').replace(/\s/g, '');
                var precio = esMayorista && precioMayorista ? parseFloat(precioMayorista) : parseFloat(precioTexto.replace(',', '.'));
                var descuento = parseFloat(row.find('.detalle-descuento').val()) || 0;
                VENTAS_INICIALES[ventaId].detalle.push({
                    id_variante: row.data('id-variante'),
                    cantidad: cantidad,
                    precio: precio,
                    es_mayorista: esMayorista ? 1 : 0,
                    descuento_unitario: descuento
                });
            });
            $(this).find('.pago-row').each(function() {
                var row = $(this);
                var metodo = row.find('.pago-metodo').val();
                var cuenta = row.find('.pago-cuenta').val();
                var monto = parseFloat(row.find('.pago-monto').val()) || 0;
                VENTAS_INICIALES[ventaId].pagos.push({
                    id_metodo_pago: metodo,
                    id_cuenta_bancaria: cuenta || null,
                    monto: monto
                });
            });
            recalcularVenta(ventaId);
        });
        validarGeneral();
    }

    function recalcularVenta(ventaId) {
        var total = 0;
        var $card = $('.venta-masivo-card[data-venta-id="'+ventaId+'"]');
        var detalle = [];
        $card.find('.detalle-row').each(function() {
            var row = $(this);
            var cantidad = parseInt(row.find('.detalle-cantidad').val()) || 1;
            if (cantidad < 1) { cantidad = 1; row.find('.detalle-cantidad').val(1); }
            var precioMayorista = row.find('.detalle-mayorista').data('precio-mayorista');
            var esMayorista = row.find('.detalle-mayorista').is(':checked');
            var precioTexto = row.find('.detalle-precio').text().replace('S/', '').replace(/\s/g, '');
            var precio = esMayorista && precioMayorista ? parseFloat(precioMayorista) : parseFloat(precioTexto.replace(',', '.'));
            var descuento = Math.min(precio, Math.max(0, parseFloat(row.find('.detalle-descuento').val()) || 0));
            row.find('.detalle-descuento').val(descuento.toFixed(2));
            var bruto = precio * cantidad;
            var descTotal = descuento * cantidad;
            var sf = bruto - descTotal;
            if (sf < 0) sf = 0;
            row.find('.detalle-subtotal').text(moneda(sf));
            total += sf;
            detalle.push({
                id_variante: row.data('id-variante'),
                cantidad: cantidad,
                precio: precio,
                es_mayorista: esMayorista ? 1 : 0,
                tipo_descuento: descuento > 0 ? 'MONTO' : null,
                valor_descuento: descuento > 0 ? descuento : null
            });
        });
        VENTAS_INICIALES[ventaId].detalle = detalle;
        $card.find('.venta-total, .venta-res-total').text(moneda(total));

        var pagado = 0;
        var pagos = [];
        $card.find('.pago-row').each(function() {
            var row = $(this);
            var metodoSel = row.find('.pago-metodo option:selected');
            var afecta = metodoSel.data('afecta') == '1';
            var codigo = metodoSel.data('codigo') || '';
            var metodo = row.find('.pago-metodo').val();
            var cuenta = row.find('.pago-cuenta').val();
            if (codigo === 'efectivo' || !afecta) {
                cuenta = null;
                row.find('.pago-cuenta').val('');
            }
            row.find('.pago-cuenta').prop('disabled', (codigo === 'efectivo' || !afecta));
            var monto = parseFloat(row.find('.pago-monto').val()) || 0;
            pagado += monto;
            pagos.push({
                id_metodo_pago: metodo,
                id_cuenta_bancaria: cuenta,
                monto: monto
            });
        });
        VENTAS_INICIALES[ventaId].pagos = pagos;
        $card.find('.venta-res-pagado').text(moneda(pagado));
        var dif = total - pagado;
        var $dif = $card.find('.venta-res-dif');
        $dif.removeClass('text-success text-danger text-warning');
        if (Math.abs(dif) < 0.01) {
            $dif.addClass('text-success').text(moneda(0));
        } else if (dif > 0) {
            $dif.addClass('text-danger').text('Falta ' + moneda(dif));
        } else {
            $dif.addClass('text-warning').text('Excede ' + moneda(Math.abs(dif)));
        }
        var $status = $card.find('.venta-status');
        $status.removeClass('cierre-masivo-status-ok cierre-masivo-status-warn cierre-masivo-status-info');
        if ($card.find('.pago-row').length === 0) {
            $status.addClass('cierre-masivo-status-info').html('<i class="fa fa-info-circle me-1"></i> Sin pagos');
        } else if (Math.abs(dif) < 0.01) {
            $status.addClass('cierre-masivo-status-ok').html('<i class="fa fa-check-circle me-1"></i> Cuadrado');
        } else {
            $status.addClass('cierre-masivo-status-warn').html('<i class="fa fa-exclamation-triangle me-1"></i> Por cuadrar');
        }
        validarGeneral();
    }

    function validarGeneral() {
        var ok = true;
        var cuadradas = 0;
        var totalVentas = $('.venta-masivo-card').length;
        $('.venta-masivo-card').each(function() {
            var ventaId = $(this).data('venta-id');
            if (!VENTAS_INICIALES[ventaId]) { ok = false; return; }
            if (!VENTAS_INICIALES[ventaId].pagos || VENTAS_INICIALES[ventaId].pagos.length === 0) { ok = false; return; }
            var total = 0;
            $(this).find('.detalle-subtotal').each(function(){ total += parseFloat($(this).text().replace('S/','').replace(/\s/g,'').replace(',','.')); });
            var pagado = 0;
            VENTAS_INICIALES[ventaId].pagos.forEach(function(p){ pagado += parseFloat(p.monto)||0; });
            if (Math.abs(total - pagado) > 0.01) { ok = false; return; }
            cuadradas++;
        });
        $('#btnProcesarMasivo').prop('disabled', !ok);
        if (ok && totalVentas > 0) {
            $('#estadoGeneral').html('<span class="text-success"><i class="fa fa-check-circle me-1"></i> '+cuadradas+' de '+totalVentas+' venta(s) cuadradas. Listo para procesar</span>');
        } else {
            $('#estadoGeneral').html('<span class="text-muted"><i class="fa fa-circle-notch me-1"></i> '+cuadradas+' de '+totalVentas+' venta(s) cuadradas. Todas deben estar cuadradas para habilitar el cierre</span>');
        }
    }

    $(document).on('change keyup input', '.detalle-cantidad, .detalle-descuento', function(){ recalcularVenta($(this).data('venta-id')); });
    $(document).on('change', '.detalle-mayorista', function(){ recalcularVenta($(this).data('venta-id')); });

    $(document).on('click', '.detalle-quitar', function(){
        var ventaId = $(this).data('venta-id');
        var $card = $('.venta-masivo-card[data-venta-id="'+ventaId+'"]');
        if ($card.find('.detalle-row').length <= 1) {
            Swal.fire('Atención', 'Debe haber al menos un producto en esta venta', 'warning');
            return;
        }
        $(this).closest('.detalle-row').remove();
        recalcularVenta(ventaId);
    });

    $(document).on('click', '.agregar-pago', function(){
        var ventaId = $(this).data('venta-id');
        var $body = $('.pagos-body[data-venta-id="'+ventaId+'"]');
        $body.find('.pago-empty').remove();
        var idx = $body.find('.pago-row').length;
        var metodoOptions = '';
        METODOS_PAGO.forEach(function(m){
            metodoOptions += '<option value="'+m.id_metodo_pago+'" data-codigo="'+(m.codigo||'').toLowerCase()+'" data-afecta="'+(m.afectaCuenta ? '1' : (m.destino_pago ? '1':'0'))+'">'+m.nombre+'</option>';
        });
        var cuentaOptions = '<option value="">Sin cuenta</option>';
        CUENTAS.forEach(function(c){ cuentaOptions += '<option value="'+c.id_cuenta_bancaria+'">'+c.nombre_banco+' - '+(c.numero_cuenta||'')+'</option>'; });
        var html = '<tr class="pago-row" data-venta-id="'+ventaId+'" data-idx="'+idx+'">'+
            '<td><select class="form-select form-select-sm pago-metodo" data-venta-id="'+ventaId+'" data-idx="'+idx+'">'+metodoOptions+'</select></td>'+
            '<td><select class="form-select form-select-sm pago-cuenta" data-venta-id="'+ventaId+'" data-idx="'+idx+'">'+cuentaOptions+'</select></td>'+
            '<td><input type="number" step="0.01" min="0.01" class="form-control form-control-sm text-end pago-monto" data-venta-id="'+ventaId+'" data-idx="'+idx+'" value="0.00"></td>'+
            '<td class="text-center"><button type="button" class="btn btn-sm btn-icon btn-light-danger pago-quitar" data-venta-id="'+ventaId+'" data-idx="'+idx+'"><i class="fa fa-trash"></i></button></td>'+
        '</tr>';
        $body.append(html);
        recalcularVenta(ventaId);
    });

    $(document).on('change', '.pago-metodo', function(){ recalcularVenta($(this).data('venta-id')); });
    $(document).on('change keyup input', '.pago-cuenta, .pago-monto', function(){ recalcularVenta($(this).data('venta-id')); });

    $(document).on('click', '.pago-quitar', function(){
        var ventaId = $(this).data('venta-id');
        var $body = $('.pagos-body[data-venta-id="'+ventaId+'"]');
        $(this).closest('.pago-row').remove();
        if ($body.find('.pago-row').length === 0) {
            $body.html('<tr class="pago-empty" data-venta-id="'+ventaId+'"><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-money-bill-wave me-1"></i> Sin pagos registrados. Agrega al menos un pago.</td></tr>');
        }
        recalcularVenta(ventaId);
    });

    $(document).on('click', '#btnProcesarMasivo', function(){
        var ventas = {};
        var valido = true;
        $('.venta-masivo-card').each(function(){
            var ventaId = $(this).data('venta-id');
            if (!VENTAS_INICIALES[ventaId]) { valido = false; return; }
            if (!VENTAS_INICIALES[ventaId].detalle || !VENTAS_INICIALES[ventaId].detalle.length) { valido = false; return; }
            if (!VENTAS_INICIALES[ventaId].pagos || !VENTAS_INICIALES[ventaId].pagos.length) { valido = false; return; }
            var total = 0;
            $(this).find('.detalle-subtotal').each(function(){ total += parseFloat($(this).text().replace('S/','').replace(/\s/g,'').replace(',','.')); });
            var pagado = 0;
            VENTAS_INICIALES[ventaId].pagos.forEach(function(p){ pagado += parseFloat(p.monto)||0; });
            if (Math.abs(total - pagado) > 0.01) { valido = false; return; }
            ventas[ventaId] = {
                items: VENTAS_INICIALES[ventaId].detalle.map(function(d){
                    return {
                        id_variante: d.id_variante,
                        cantidad: d.cantidad,
                        precio: d.precio,
                        es_mayorista: d.es_mayorista,
                        tipo_descuento: d.tipo_descuento,
                        valor_descuento: d.valor_descuento
                    };
                }),
                pagos: VENTAS_INICIALES[ventaId].pagos
            };
        });
        if (!valido) {
            Swal.fire('Atención', 'Todas las ventas deben estar con pagos completos para poder procesar.', 'warning');
            return;
        }
        Swal.fire({
            title: '¿Procesar cierre masivo?',
            html: 'Se cerrarán <strong>'+Object.keys(ventas).length+'</strong> venta(s) seleccionada(s).<br>Esta acción no se puede deshacer.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: '<i class="fa fa-lock"></i> Sí, procesar',
            cancelButtonText: 'Cancelar'
        }).then(function(r){
            if (!r.isConfirmed) return;
            var btn = $('#btnProcesarMasivo');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
            $.ajax({
                url: '{{ route("admin.ventas.cierre.masivo.procesar") }}',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', ventas: ventas},
                dataType: 'json'
            }).done(function(res){
                var msg = 'Cerradas correctamente: '+res.cerradas+' | Con errores: '+res.errores;
                if (res.errores > 0) {
                    var detalleErr = res.resultados.filter(function(x){return !x.success;}).map(function(x){return '• Venta #'+x.id_venta+': '+x.message;}).join('<br>');
                    Swal.fire({icon: 'warning', title: 'Cierre masivo finalizado', html: msg + '<br><br><div class="text-start">'+detalleErr+'</div>'});
                    btn.prop('disabled', false).html('<i class="fa fa-lock"></i> Procesar cierre masivo');
                } else {
                    Swal.fire({icon: 'success', title: '¡Cierre masivo exitoso!', text: msg, timer: 1600, showConfirmButton: false});
                    setTimeout(function(){ window.location.href = '{{ route("admin.ventas.cerrar") }}'; }, 1400);
                }
            }).fail(function(xhr){
                var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Error al procesar el cierre masivo';
                Swal.fire('Error', msg, 'error');
                btn.prop('disabled', false).html('<i class="fa fa-lock"></i> Procesar cierre masivo');
            });
        });
    });

    $(document).ready(function(){ initVentas(); });
</script>
@endpush

@endsection
