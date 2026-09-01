@extends('admin.layouts.app')

@section('title', 'Cerrar Venta')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Cerrar Venta</h4>
        </div>

        <a href="{{ route('admin.ventas.index') }}" class="btn btn-primary btn-round">
            <i class="fa fa-cart-plus"></i> Punto de Venta
        </a>

    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>N° Venta</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Vendedor</th>
                            <th class="text-center">Items</th>
                            <th class="text-end">Total</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($ventasPendientes as $venta)
                        <tr>
                            <td><span class="badge bg-dark">{{ $venta->numero }}</span></td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-semibold">{{ $venta->nombre_cliente }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $venta->tienda->codigo ?? '' }}</span>
                                {{ $venta->tienda->nombre ?? '' }}
                            </td>
                            <td>{{ $venta->vendedor->nombre ?? '—' }}</td>
                            <td class="text-center">{{ $venta->total_items }}</td>
                            <td class="text-end fw-bold">S/ {{ number_format($venta->total, 2) }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">Pendiente de cobro</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-success btn-round btn-cerrar-venta"
                                    data-id="{{ $venta->id_venta }}">
                                    <i class="fa fa-lock"></i> Cerrar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fa fa-check-circle fa-2x d-block mb-2 opacity-50"></i>
                                No hay ventas pendientes de cobro
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.ventas.modals.cierre')

@push('scripts')
<script>
    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function moneda(n) {
        return 'S/ ' + parseFloat(n || 0).toFixed(2);
    }

    var METODOS_PAGO_GLOBAL = [];
    var CUENTAS_GLOBAL = [];
    var CONFIG_CHECKOUT = { tipos_venta: [], motivos_descuento: [] };
    var ventaCierreActual = null;
    var pagosCierre = [];
    var detalleCierre = [];

    /* ============ ABRIR MODAL DE CIERRE ============ */
    $(document).on('click', '.btn-cerrar-venta', function() {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });

        $.getJSON('{{ url("admin/ventas") }}/' + id + '/cierre', function(res) {
            ventaCierreActual = res.venta;
            METODOS_PAGO_GLOBAL = res.metodosPagos;
            CUENTAS_GLOBAL = res.cuentas;
            CONFIG_CHECKOUT = res.configCheckout || CONFIG_CHECKOUT;
            pagosCierre = res.venta.venta_pagos || [];
            detalleCierre = res.venta.detalle.map(function(d) {
                return {
                    id_venta_detalle: d.id_venta_detalle,
                    id_variante: d.id_variante,
                    nombre: d.variante.producto.nombre,
                    sku: d.variante.sku || '',
                    cantidad: d.cantidad,
                    precio: parseFloat(d.precio),
                    subtotal: parseFloat(d.subtotal),
                    id_motivo_descuento: d.id_motivo_descuento || null,
                    tipo_descuento: d.tipo_descuento || null,
                    valor_descuento: d.tipo_descuento == 'MONTO'
                        ? (parseFloat(d.valor_descuento_unitario) || 0)
                        : (d.tipo_descuento == 'PORCENTAJE' ? (parseFloat(d.valor_descuento_unitario) / parseFloat(d.precio) * 100 || 0) : null)
                };
            });

            renderModalCierre();
            Swal.close();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCerrarVenta')).show();
        }).fail(function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo cargar la venta';
            Swal.fire('Error', msg, 'error');
        });
    });

    /* ============ RENDER MODAL ============ */
    function renderModalCierre() {
        if (!ventaCierreActual) return;

        // Header info
        $('#cierreVentaNumero').text(ventaCierreActual.numero);
        $('#cierreVentaCliente').text(ventaCierreActual.nombre_cliente);
        $('#cierreVentaTienda').text(ventaCierreActual.tienda ? ventaCierreActual.tienda.nombre : '—');
        $('#cierreVentaFecha').text(ventaCierreActual.created_at);
        $('#cierreVentaVendedor').text(ventaCierreActual.vendedor ? ventaCierreActual.vendedor.nombre : '—');

        // Detail table
        renderDetalleCierre();

        // Payments
        renderPagosCierre();

        // Descuentos / tipo de venta
        renderDescuentosCierre();
    }

    function motivosItemCheckout() {
        var motivos = (CONFIG_CHECKOUT.motivos_descuento || []).filter(function(m) { return m.aplica_a === 'ITEM'; });
        var opts = '<option value="">—</option>';
        motivos.forEach(function(m) {
            opts += '<option value="' + m.id_motivo_descuento + '">' + escapeHtml(m.nombre) + '</option>';
        });
        return opts;
    }

    function renderDescuentosCierre() {
        // Tipo de venta
        var tipos = CONFIG_CHECKOUT.tipos_venta || [];
        var htmlTv = '<option value="">— Sin tipo —</option>';
        tipos.forEach(function(t) {
            var sel = ventaCierreActual && ventaCierreActual.id_tipo_venta == t.id_tipo_venta ? ' selected' : '';
            htmlTv += '<option value="' + t.id_tipo_venta + '"' + sel + '>' + escapeHtml(t.nombre) + '</option>';
        });
        $('#cierreTipoVenta').html(htmlTv);

        // Motivos de cabecera (global)
        var motivosCab = (CONFIG_CHECKOUT.motivos_descuento || []).filter(function(m) { return m.aplica_a === 'CABECERA'; });
        var htmlM = '<option value="">— Sin motivo —</option>';
        motivosCab.forEach(function(m) {
            var sel = ventaCierreActual && ventaCierreActual.id_motivo_descuento_global == m.id_motivo_descuento ? ' selected' : '';
            htmlM += '<option value="' + m.id_motivo_descuento + '"' + sel + '>' + escapeHtml(m.nombre) + '</option>';
        });
        $('#cierreMotivoGlobal').html(htmlM);

        // Descuento global previamente aplicado
        var dg = ventaCierreActual ? parseFloat(ventaCierreActual.descuento_global) || 0 : 0;
        $('#cierreDescuentoGlobal').val(dg > 0 ? dg.toFixed(2) : '');

        actualizarResumenCierre();
    }

    function renderDetalleCierre() {
        var total = 0;
        var html = detalleCierre.map(function(item, idx) {
            var bruto = item.cantidad * item.precio;

            // Descuento por línea (porcentaje) según motivo ITEM seleccionado
            var pct = item.tipo_descuento === 'PORCENTAJE' && item.id_motivo_descuento
                ? (parseFloat(item.valor_descuento_unitario) / (item.precio > 0 ? item.precio : 1) * 100 || 0)
                : 0;
            item._pct = pct;
            var desc = Math.min(bruto, bruto * pct / 100);
            item._desc = desc;
            var sf = bruto - desc;
            item.subtotal = sf;
            total += sf;

            return '<tr data-idx="' + idx + '">' +
                '<td>' +
                    '<div class="fw-semibold">' + escapeHtml(item.nombre) + '</div>' +
                    '<div class="small text-muted">' + escapeHtml(item.sku) + '</div>' +
                '</td>' +
                '<td style="width:90px">' +
                    '<input type="number" class="form-control form-control-sm cierre-cant" data-idx="' + idx + '" value="' + item.cantidad + '" min="1">' +
                '</td>' +
                '<td class="text-end" style="width:100px">' + moneda(item.precio) + '</td>' +
                '<td style="width:150px">' +
                    '<select class="form-select form-select-sm cierre-motivo" data-idx="' + idx + '">' +
                    motivosItemCheckout().replace('<option value="">—</option>',
                        '<option value="">—</option>') +
                    '</select>' +
                '</td>' +
                '<td style="width:90px">' +
                    '<input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm cierre-desc" data-idx="' + idx + '" value="' + pct.toFixed(2) + '" placeholder="%">' +
                '</td>' +
                '<td class="text-end fw-bold" style="width:110px">' + moneda(sf) + '</td>' +
                '<td class="text-end" style="width:60px">' +
                    '<button type="button" class="btn btn-sm btn-danger btn-border btn-round btn-quitar-detalle" data-idx="' + idx + '">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</td>' +
                '</tr>';
        }).join('');

        // Pre-seleccionar motivo por línea tras renderizar
        setTimeout(function() {
            detalleCierre.forEach(function(item, idx) {
                if (item.id_motivo_descuento) {
                    var $sel = $('.cierre-motivo[data-idx="' + idx + '"]');
                    var present = $sel.find('option[value="' + item.id_motivo_descuento + '"]').length > 0;
                    if (present) $sel.val(String(item.id_motivo_descuento));
                }
            });
        }, 0);

        $('#cierreDetalleBody').html(html);
        $('#cierreTotalVenta').text(moneda(total));
        ventaCierreActual.total = total;
        actualizarResumenCierre();
    }

    function esPagoEfectivo(p) {
        if (!p || !p.id_metodo_pago) return false;
        var metodo = METODOS_PAGO_GLOBAL.find(function(m) { return m.id_metodo_pago == p.id_metodo_pago; });
        return metodo && (String(metodo.codigo).toLowerCase() === 'efectivo');
    }

    function renderPagosCierre() {
        var totalPagado = 0;
        var html = pagosCierre.map(function(p, idx) {
            totalPagado += parseFloat(p.monto);
            var efectivo = esPagoEfectivo(p);

            var celdaCuenta;
            if (efectivo) {
                celdaCuenta = '<span class="badge bg-success-subtle text-success px-2 py-1">' +
                    '<i class="fa fa-cash-register me-1"></i> Caja</span>';
            } else {
                celdaCuenta = '<select class="form-select form-select-sm cierre-pago-cuenta" data-idx="' + idx + '">' +
                    '<option value="">Selecciona cuenta</option>' +
                    CUENTAS_GLOBAL.map(function(c) {
                        return '<option value="' + c.id_cuenta_bancaria + '"' + (c.id_cuenta_bancaria == p.id_cuenta_bancaria ? ' selected' : '') +
                            '>' + escapeHtml(c.nombre_banco) + '</option>';
                    }).join('') +
                    '</select>';
            }

            return '<tr data-pago-idx="' + idx + '">' +
                '<td>' +
                    '<select class="form-select form-select-sm cierre-pago-metodo" data-idx="' + idx + '">' +
                    METODOS_PAGO_GLOBAL.map(function(m) {
                        return '<option value="' + m.id_metodo_pago + '"' + (m.id_metodo_pago == p.id_metodo_pago ? ' selected' : '') + '>' + escapeHtml(m.nombre) + '</option>';
                    }).join('') +
                    '</select>' +
                '</td>' +
                '<td>' + celdaCuenta + '</td>' +
                '<td style="width:140px">' +
                    '<input type="number" class="form-control form-control-sm cierre-pago-monto" data-idx="' + idx + '" value="' + parseFloat(p.monto).toFixed(2) + '" min="0.01" step="0.01">' +
                '</td>' +
                '<td class="text-end" style="width:60px">' +
                    '<button type="button" class="btn btn-sm btn-danger btn-border btn-round btn-quitar-pago" data-idx="' + idx + '">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</td>' +
                '</tr>';
        }).join('');

        if (!pagosCierre.length) {
            html = '<tr><td colspan="4" class="text-center text-muted py-3">Sin pagos registrados</td></tr>';
        }

        $('#cierrePagosBody').html(html);
        actualizarResumenCierre();
    }

    function actualizarResumenCierre() {
        var subtotal = parseFloat(ventaCierreActual ? ventaCierreActual.total : 0) || 0;
        var descGlobal = parseFloat($('#cierreDescuentoGlobal').val()) || 0;
        if (descGlobal < 0) descGlobal = 0;
        if (descGlobal > subtotal) descGlobal = subtotal;
        var total = subtotal - descGlobal;

        var pagado = 0;
        pagosCierre.forEach(function(p) { pagado += parseFloat(p.monto) || 0; });
        var diferencia = total - pagado;

        $('#cierreResumenTotal').text(moneda(total));
        $('#cierreResumenPagado').text(moneda(pagado));

        var diffEl = $('#cierreResumenDiferencia');
        diffEl.removeClass('text-success text-danger');
        diffEl.text(moneda(Math.abs(diferencia)));
        if (Math.abs(diferencia) < 0.01) {
            diffEl.addClass('text-success');
        } else {
            diffEl.addClass('text-danger');
        }

        // Indicador de estado
        var statusWrap = $('#cierreStatusWrap');
        var statusText = $('#cierreStatusText');
        var listo = false;

        if (pagosCierre.length === 0) {
            statusText.html('<i class="fa fa-info-circle me-1"></i> Agrega al menos un pago');
            statusWrap.removeClass('cierre-status-ok status-ok cierre-status-warn')
                .addClass('cierre-status-warn');
        } else if (Math.abs(diferencia) < 0.01) {
            listo = true;
            statusText.html('<i class="fa fa-check-circle me-1"></i> Pagos completos, listo para cerrar');
            statusWrap.removeClass('cierre-status-warn status-ok').addClass('cierre-status-ok');
        } else {
            statusText.html('<i class="fa fa-exclamation-triangle me-1"></i> Falta ' + moneda(diferencia) + ' por cubrir');
            statusWrap.removeClass('status-ok cierre-status-ok').addClass('cierre-status-warn');
        }

        // Enable/disable close button
        $('#btnProcesarCierre').prop('disabled', !listo);
    }

    /* ============ ACCIONES DETALLE ============ */
    $(document).on('change', '.cierre-cant', function() {
        var idx = parseInt($(this).data('idx'));
        var cant = parseInt($(this).val()) || 1;
        if (cant < 1) { cant = 1; $(this).val(1); }
        detalleCierre[idx].cantidad = cant;
        renderDetalleCierre();
    });

    $(document).on('click', '.btn-quitar-detalle', function() {
        var idx = parseInt($(this).data('idx'));
        if (detalleCierre.length <= 1) {
            Swal.fire('Atención', 'Debe haber al menos un producto', 'warning');
            return;
        }
        detalleCierre.splice(idx, 1);
        renderDetalleCierre();
    });

    /* ============ ACCIONES DESCUENTOS ============ */
    $(document).on('change', '.cierre-motivo', function() {
        var idx = parseInt($(this).data('idx'));
        detalleCierre[idx].id_motivo_descuento = $(this).val() ? parseInt($(this).val()) : null;
        if (!detalleCierre[idx].id_motivo_descuento) {
            detalleCierre[idx].tipo_descuento = null;
            detalleCierre[idx].valor_descuento = null;
        } else if (detalleCierre[idx].tipo_descuento !== 'PORCENTAJE') {
            detalleCierre[idx].tipo_descuento = 'PORCENTAJE';
            detalleCierre[idx].valor_descuento = parseFloat($('.cierre-desc[data-idx="' + idx + '"]').val()) || 0;
        }
        renderDetalleCierre();
    });

    $(document).on('input change', '.cierre-desc', function() {
        var idx = parseInt($(this).data('idx'));
        var pct = parseFloat($(this).val()) || 0;
        if (pct < 0) pct = 0; if (pct > 100) pct = 100;
        detalleCierre[idx].valor_descuento = pct;
        if (pct > 0) {
            detalleCierre[idx].tipo_descuento = 'PORCENTAJE';
        } else {
            detalleCierre[idx].tipo_descuento = null;
        }
        renderDetalleCierre();
    });

    $('#cierreDescuentoGlobal').on('input change', function() {
        actualizarResumenCierre();
    });

    $(document).on('change', '#cierreTipoVenta', function() {
        // Al elegir un tipo de venta (ej. Vestidos Fallados) se re-evalúan las reglas
        ventaCierreActual._tipo_venta = $(this).val();
        actualizarResumenCierre();
    });

    $(document).on('change', '#cierreMotivoGlobal', function() {
        actualizarResumenCierre();
    });

    /* ============ ACCIONES PAGOS ============ */
    $(document).on('click', '#btnAgregarPago', function() {
        // Remove empty row message if exists
        if (!pagosCierre.length) {
            pagosCierre = [];
        }

        var nuevo = {
            id_metodo_pago: METODOS_PAGO_GLOBAL.length ? METODOS_PAGO_GLOBAL[0].id_metodo_pago : '',
            id_cuenta_bancaria: null,
            monto: 0
        };
        if (METODOS_PAGO_GLOBAL.length && !esPagoEfectivo(nuevo)) {
            nuevo.id_cuenta_bancaria = CUENTAS_GLOBAL.length ? CUENTAS_GLOBAL[0].id_cuenta_bancaria : null;
        }

        pagosCierre.push(nuevo);

        renderPagosCierre();
    });

    $(document).on('change', '.cierre-pago-metodo', function() {
        var idx = parseInt($(this).data('idx'));
        pagosCierre[idx].id_metodo_pago = parseInt($(this).val());
        if (esPagoEfectivo(pagosCierre[idx])) {
            pagosCierre[idx].id_cuenta_bancaria = null;
        }
        renderPagosCierre();
    });

    $(document).on('change', '.cierre-pago-cuenta', function() {
        var idx = parseInt($(this).data('idx'));
        pagosCierre[idx].id_cuenta_bancaria = parseInt($(this).val());
    });

    $(document).on('input', '.cierre-pago-monto', function() {
        var idx = parseInt($(this).data('idx'));
        pagosCierre[idx].monto = parseFloat($(this).val()) || 0;
        actualizarResumenCierre();
    });

    $(document).on('click', '.btn-quitar-pago', function() {
        var idx = parseInt($(this).data('idx'));
        pagosCierre.splice(idx, 1);
        renderPagosCierre();
    });

    /* ============ GUARDAR DETALLE ============ */
    $(document).on('click', '#btnGuardarDetalleCierre', function() {
        if (!ventaCierreActual) return;

        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');

        $.ajax({
            url: '{{ url("admin/ventas") }}/' + ventaCierreActual.id_venta + '/detalle-venta',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: detalleCierre.map(function(d) {
                    return {
                        id_variante: d.id_variante,
                        cantidad: d.cantidad,
                        precio: d.precio
                    };
                })
            },
            dataType: 'json'
        }).done(function(res) {
            if (res.success) {
                ventaCierreActual.total = res.total;
                renderDetalleCierre();
                Swal.fire({ icon: 'success', title: 'Detalle actualizado', timer: 1000, showConfirmButton: false });
            }
        }).fail(function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Error al guardar';
            Swal.fire('Error', msg, 'error');
        }).always(function() {
            btn.prop('disabled', false).html('<i class="fa fa-save"></i> Guardar cambios');
        });
    });

    /* ============ PROCESAR CIERRE ============ */
    $(document).on('click', '#btnProcesarCierre', function() {
        if (!ventaCierreActual) return;

        var total = parseFloat(ventaCierreActual.total) || 0;
        var pagado = 0;
        pagosCierre.forEach(function(p) { pagado += parseFloat(p.monto) || 0; });

        if (Math.abs(total - pagado) > 0.01) {
            Swal.fire('Atención', 'La suma de pagos no coincide con el total', 'warning');
            return;
        }

        Swal.fire({
            title: '¿Cerrar venta?',
            html: 'Se procesará el cobro de <strong>' + moneda(total) + '</strong><br>con ' + pagosCierre.length + ' pago(s)',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Sí, cerrar venta',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (r.isConfirmed) {
                var btn = $('#btnProcesarCierre');
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');

                $.ajax({
                    url: '{{ url("admin/ventas") }}/' + ventaCierreActual.id_venta + '/procesar-cierre',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id_tipo_venta: $('#cierreTipoVenta').val() || null,
                        id_motivo_descuento_global: $('#cierreMotivoGlobal').val() || null,
                        descuento_global: parseFloat($('#cierreDescuentoGlobal').val()) || 0,
                        items: detalleCierre.map(function(d) {
                            var valor = parseFloat(d.valor_descuento) || 0;
                            return {
                                id_variante: d.id_variante,
                                cantidad: d.cantidad,
                                precio: d.precio,
                                id_motivo_descuento: (d.id_motivo_descuento && valor > 0) ? d.id_motivo_descuento : null,
                                tipo_descuento: valor > 0 ? (d.tipo_descuento || 'PORCENTAJE') : null,
                                valor_descuento: valor > 0 ? valor : null
                            };
                        }),
                        pagos: pagosCierre.map(function(p) {
                            return {
                                id_metodo_pago: p.id_metodo_pago,
                                id_cuenta_bancaria: p.id_cuenta_bancaria,
                                monto: p.monto
                            };
                        })
                    },
                    dataType: 'json'
                }).done(function(res) {
                    if (res.success) {
                        bootstrap.Modal.getInstance(document.getElementById('modalCerrarVenta')).hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Venta cerrada',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        setTimeout(function() { location.reload(); }, 800);
                    }
                }).fail(function(xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Error al cerrar la venta';
                    Swal.fire('Error', msg, 'error');
                    btn.prop('disabled', false).html('<i class="fa fa-lock"></i> Cerrar Venta');
                });
            }
        });
    });
</script>
@endpush

@endsection
