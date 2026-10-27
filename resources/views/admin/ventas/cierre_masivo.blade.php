@extends('admin.layouts.app')

@section('title', 'Cierre Masivo de Ventas')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Cierre Masivo de Ventas</h4>
            <span class="badge bg-warning text-dark ms-2">{{ $ventas->count() }} venta(s) seleccionada(s)</span>
        </div>
        <a href="{{ route('admin.ventas.cerrar') }}" class="btn btn-light border btn-round">
            <i class="fa fa-arrow-left"></i> Volver a Cerrar Venta
        </a>
    </div>

    <div class="alert alert-info">
        <i class="fa fa-info-circle me-2"></i> Puedes ajustar cantidades, precio mayorista, descuentos por línea y dividir los pagos por cada venta de forma independiente.
    </div>

    <form id="formCierreMasivo">
        @csrf
        @foreach($ventas as $venta)
        <div class="card mb-4 venta-masivo-card" data-venta-id="{{ $venta->id_venta }}">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div>
                        <span class="text-muted small">N° Venta</span>
                        <div><span class="badge bg-dark">{{ $venta->numero }}</span></div>
                    </div>
                    <div>
                        <span class="text-muted small">Cliente</span>
                        <div class="fw-semibold">{{ $venta->nombre_cliente }}</div>
                    </div>
                    <div>
                        <span class="text-muted small">Tienda</span>
                        <div>{{ $venta->tienda->nombre ?? '—' }}</div>
                    </div>
                    <div>
                        <span class="text-muted small">Vendedor</span>
                        <div>{{ $venta->vendedor->nombre ?? '—' }}</div>
                    </div>
                    <div>
                        <span class="text-muted small">Fecha</span>
                        <div>{{ $venta->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="text-muted small">Total venta</span>
                    <div class="h5 mb-0 fw-bold venta-total" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->total, 2) }}</div>
                </div>
            </div>
            <div class="card-body">

                <!-- DETALLE -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0"><span class="badge bg-primary me-2">1</span>Detalle de la venta</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width:110px">Cantidad</th>
                                    <th class="text-end" style="width:120px">Precio</th>
                                    <th class="text-end" style="width:150px">Precio por mayor</th>
                                    <th class="text-end" style="width:140px">Desc. (S/)</th>
                                    <th class="text-end" style="width:130px">Subtotal</th>
                                    <th style="width:60px"></th>
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
                                        <input type="number" step="0.01" min="0" class="form-control form-control-sm text-end detalle-descuento" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" value="{{ number_format($descuentoUnitario, 2, '.', '') }}">
                                    </td>
                                    <td class="text-end fw-bold detalle-subtotal" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">S/ {{ number_format($d->subtotal, 2) }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-danger btn-border btn-round detalle-quitar" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PAGOS -->
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0"><span class="badge bg-success me-2">2</span>Registrar pagos</h6>
                        <button type="button" class="btn btn-sm btn-success btn-round agregar-pago" data-venta-id="{{ $venta->id_venta }}">
                            <i class="fa fa-plus"></i> Agregar pago
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Método de pago</th>
                                    <th>Cuenta</th>
                                    <th style="width:170px">Monto</th>
                                    <th style="width:60px"></th>
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
                                            <option value="{{ $c->id_cuenta_bancaria }}" {{ $pago->id_cuenta_bancaria == $c->id_cuenta_bancaria ? 'selected' : '' }}>{{ $c->nombre_banco }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0.01" class="form-control form-control-sm text-end pago-monto" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}" value="{{ number_format($pago->monto, 2, '.', '') }}">
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-danger btn-border btn-round pago-quitar" data-venta-id="{{ $venta->id_venta }}" data-idx="{{ $loop->index }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr class="pago-empty" data-venta-id="{{ $venta->id_venta }}">
                                    <td colspan="4" class="text-center text-muted py-2">Sin pagos registrados. Agrega al menos un pago.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end gap-3 mt-3">
                        <div class="text-end">
                            <div class="text-muted small">Total</div>
                            <div class="fw-bold venta-res-total" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->total, 2) }}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Pagado</div>
                            <div class="fw-bold text-success venta-res-pagado" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->ventaPagos->sum('monto'), 2) }}</div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted small">Diferencia</div>
                            <div class="fw-bold venta-res-dif" data-venta-id="{{ $venta->id_venta }}">S/ {{ number_format($venta->total - $venta->ventaPagos->sum('monto'), 2) }}</div>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="badge venta-status" data-venta-id="{{ $venta->id_venta }}"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </form>

    <div class="card">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="fw-semibold">Estado general:</span>
                <span id="estadoGeneral" class="ms-2">Revisa que todas las ventas estén cuadradas</span>
            </div>
            <button type="button" class="btn btn-success btn-lg btn-round" id="btnProcesarMasivo" disabled>
                <i class="fa fa-lock"></i> Procesar cierre masivo
            </button>
        </div>
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
            $(this).find('.detalle-row').each(function(idx) {
                var row = $(this);
                var cantidad = parseInt(row.find('.detalle-cantidad').val()) || 1;
                var precioMayorista = row.find('.detalle-mayorista').data('precio-mayorista');
                var esMayorista = row.find('.detalle-mayorista').is(':checked');
                var precio = esMayorista && precioMayorista ? parseFloat(precioMayorista) : parseFloat(row.find('.detalle-precio').text().replace('S/','').replace(',','.'));
                var descuento = parseFloat(row.find('.detalle-descuento').val()) || 0;
                VENTAS_INICIALES[ventaId].detalle.push({
                    id_variante: row.data('id-variante'),
                    cantidad: cantidad,
                    precio: precio,
                    es_mayorista: esMayorista ? 1 : 0,
                    descuento_unitario: descuento
                });
            });
            $(this).find('.pago-row').each(function(idx) {
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
            var idx = row.data('idx');
            var cantidad = parseInt(row.find('.detalle-cantidad').val()) || 1;
            if (cantidad < 1) { cantidad = 1; row.find('.detalle-cantidad').val(1); }
            var precioMayorista = row.find('.detalle-mayorista').data('precio-mayorista');
            var esMayorista = row.find('.detalle-mayorista').is(':checked');
            var precio = esMayorista && precioMayorista ? parseFloat(precioMayorista) : parseFloat(row.find('.detalle-precio').text().replace('S/','').replace(',','.'));
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
            if (codigo === 'efectivo' || !afecta) cuenta = null;
            row.find('.pago-cuenta').prop('disabled', (codigo === 'efectivo' || !afecta));
            if (codigo === 'efectivo' || !afecta) row.find('.pago-cuenta').val('');
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
        $dif.removeClass('text-success text-danger');
        $dif.text(moneda(Math.abs(dif)));
        if (Math.abs(dif) < 0.01) $dif.addClass('text-success'); else $dif.addClass('text-danger');
        var $status = $card.find('.venta-status');
        if ($card.find('.pago-row').length === 0) {
            $status.removeClass('bg-success').addClass('bg-warning text-dark').html('<i class="fa fa-info-circle me-1"></i> Sin pagos');
        } else if (Math.abs(dif) < 0.01) {
            $status.removeClass('bg-warning text-dark').addClass('bg-success').html('<i class="fa fa-check-circle me-1"></i> Cuadrado');
        } else {
            $status.removeClass('bg-success').addClass('bg-warning text-dark').html('<i class="fa fa-exclamation-triangle me-1"></i> Falta ' + moneda(dif));
        }
        validarGeneral();
    }

    function validarGeneral() {
        var ok = true;
        $('.venta-masivo-card').each(function() {
            var ventaId = $(this).data('venta-id');
            if (!VENTAS_INICIALES[ventaId]) { ok = false; return; }
            if (!VENTAS_INICIALES[ventaId].pagos || VENTAS_INICIALES[ventaId].pagos.length === 0) { ok = false; return; }
            var total = 0;
            $(this).find('.detalle-subtotal').each(function(){ total += parseFloat($(this).text().replace('S/','').replace(',','.')); });
            var pagado = 0;
            VENTAS_INICIALES[ventaId].pagos.forEach(function(p){ pagado += parseFloat(p.monto)||0; });
            if (Math.abs(total - pagado) > 0.01) ok = false;
        });
        $('#btnProcesarMasivo').prop('disabled', !ok);
        if (ok) $('#estadoGeneral').html('<span class="text-success"><i class="fa fa-check-circle me-1"></i> Todas las ventas están cuadradas, listo para procesar</span>');
        else $('#estadoGeneral').html('<span class="text-warning"><i class="fa fa-exclamation-triangle me-1"></i> Revisa pagos/detalle: todas deben cuadrar para habilitar el cierre</span>');
    }

    $(document).on('change keyup input', '.detalle-cantidad, .detalle-descuento', function(){ recalcularVenta($(this).data('venta-id')); });
    $(document).on('change', '.detalle-mayorista', function(){ recalcularVenta($(this).data('venta-id')); });

    $(document).on('click', '.detalle-quitar', function(){
        var ventaId = $(this).data('venta-id');
        var $card = $('.venta-masivo-card[data-venta-id="'+ventaId+'"]');
        if ($card.find('.detalle-row').length <= 1) {
            Swal.fire('Atención', 'Debe haber al menos un producto', 'warning');
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
            metodoOptions += '<option value="'+m.id_metodo_pago+'" data-codigo="'+(m.codigo||'').toLowerCase()+'" data-afecta="'+(m.destino_pago || m.afecta_cuenta ? '1':'0')+'">'+m.nombre+'</option>';
        });
        var cuentaOptions = '<option value="">Sin cuenta</option>';
        CUENTAS.forEach(function(c){ cuentaOptions += '<option value="'+c.id_cuenta_bancaria+'">'+c.nombre_banco+'</option>'; });
        var html = '<tr class="pago-row" data-venta-id="'+ventaId+'" data-idx="'+idx+'">'+
            '<td><select class="form-select form-select-sm pago-metodo" data-venta-id="'+ventaId+'" data-idx="'+idx+'">'+metodoOptions+'</select></td>'+
            '<td><select class="form-select form-select-sm pago-cuenta" data-venta-id="'+ventaId+'" data-idx="'+idx+'">'+cuentaOptions+'</select></td>'+
            '<td><input type="number" step="0.01" min="0.01" class="form-control form-control-sm text-end pago-monto" data-venta-id="'+ventaId+'" data-idx="'+idx+'" value="0.00"></td>'+
            '<td class="text-end"><button type="button" class="btn btn-sm btn-danger btn-border btn-round pago-quitar" data-venta-id="'+ventaId+'" data-idx="'+idx+'"><i class="fa fa-trash"></i></button></td>'+
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
            $body.html('<tr class="pago-empty" data-venta-id="'+ventaId+'"><td colspan="4" class="text-center text-muted py-2">Sin pagos registrados. Agrega al menos un pago.</td></tr>');
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
            $(this).find('.detalle-subtotal').each(function(){ total += parseFloat($(this).text().replace('S/','').replace(',','.')); });
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
            Swal.fire('Atención', 'Todas las ventas deben estar con pagos completos', 'warning');
            return;
        }
        Swal.fire({
            title: '¿Procesar cierre masivo?',
            html: 'Se cerrarán <strong>'+Object.keys(ventas).length+'</strong> venta(s). Esta acción no se puede deshacer.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Sí, procesar',
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
                var msg = 'Cerradas: '+res.cerradas+' | Errores: '+res.errores;
                if (res.errores > 0) {
                    var detalleErr = res.resultados.filter(function(x){return !x.success;}).map(function(x){return x.id_venta+': '+x.message;}).join('\n');
                    Swal.fire({icon: 'warning', title: 'Cierre masivo finalizado', html: msg.replace(/\n/g,'<br>') + (detalleErr ? '<br><br><small>'+detalleErr.replace(/\n/g,'<br>')+'</small>' : '')});
                } else {
                    Swal.fire({icon: 'success', title: 'Cierre masivo exitoso', text: msg, timer: 1500, showConfirmButton: false});
                    setTimeout(function(){ window.close(); }, 1200);
                }
            }).fail(function(xhr){
                var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Error al procesar cierre masivo';
                Swal.fire('Error', msg, 'error');
                btn.prop('disabled', false).html('<i class="fa fa-lock"></i> Procesar cierre masivo');
            });
        });
    });

    $(document).ready(function(){ initVentas(); });
</script>
@endpush

@endsection
