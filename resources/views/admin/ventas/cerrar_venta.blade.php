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
            pagosCierre = res.venta.venta_pagos || [];
            detalleCierre = res.venta.detalle.map(function(d) {
                return {
                    id_venta_detalle: d.id_venta_detalle,
                    id_variante: d.id_variante,
                    nombre: d.variante.producto.nombre,
                    sku: d.variante.sku || '',
                    cantidad: d.cantidad,
                    precio: parseFloat(d.precio),
                    subtotal: parseFloat(d.subtotal)
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
    }

    function renderDetalleCierre() {
        var total = 0;
        var html = detalleCierre.map(function(item, idx) {
            var sub = item.cantidad * item.precio;
            item.subtotal = sub;
            total += sub;
            return '<tr data-idx="' + idx + '">' +
                '<td>' +
                    '<div class="fw-semibold">' + escapeHtml(item.nombre) + '</div>' +
                    '<div class="small text-muted">' + escapeHtml(item.sku) + '</div>' +
                '</td>' +
                '<td style="width:100px">' +
                    '<input type="number" class="form-control form-control-sm cierre-cant" data-idx="' + idx + '" value="' + item.cantidad + '" min="1">' +
                '</td>' +
                '<td class="text-end" style="width:120px">' + moneda(item.precio) + '</td>' +
                '<td class="text-end fw-bold" style="width:120px">' + moneda(sub) + '</td>' +
                '<td class="text-end" style="width:60px">' +
                    '<button type="button" class="btn btn-sm btn-danger btn-border btn-round btn-quitar-detalle" data-idx="' + idx + '">' +
                        '<i class="fa fa-trash"></i>' +
                    '</button>' +
                '</td>' +
                '</tr>';
        }).join('');

        $('#cierreDetalleBody').html(html);
        $('#cierreTotalVenta').text(moneda(total));
        ventaCierreActual.total = total;
        actualizarResumenCierre();
    }

    function renderPagosCierre() {
        var totalPagado = 0;
        var html = pagosCierre.map(function(p, idx) {
            totalPagado += parseFloat(p.monto);
            return '<tr data-pago-idx="' + idx + '">' +
                '<td>' +
                    '<select class="form-select form-select-sm cierre-pago-metodo" data-idx="' + idx + '">' +
                    METODOS_PAGO_GLOBAL.map(function(m) {
                        return '<option value="' + m.id + '"' + (m.id == p.id_metodo_pago ? ' selected' : '') + '>' + escapeHtml(m.nombre) + '</option>';
                    }).join('') +
                    '</select>' +
                '</td>' +
                '<td>' +
                    '<select class="form-select form-select-sm cierre-pago-cuenta" data-idx="' + idx + '">' +
                    CUENTAS_GLOBAL.map(function(c) {
                        return '<option value="' + c.id_cuenta_bancaria + '"' + (c.id_cuenta_bancaria == p.id_cuenta_bancaria ? ' selected' : '') +                                 '>' + escapeHtml(c.nombre_banco) + '</option>';
                    }).join('') +
                    '</select>' +
                '</td>' +
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
        var total = parseFloat(ventaCierreActual ? ventaCierreActual.total : 0) || 0;
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

    /* ============ ACCIONES PAGOS ============ */
    $(document).on('click', '#btnAgregarPago', function() {
        // Remove empty row message if exists
        if (!pagosCierre.length) {
            pagosCierre = [];
        }

        pagosCierre.push({
            id_metodo_pago: METODOS_PAGO_GLOBAL.length ? METODOS_PAGO_GLOBAL[0].id : '',
            id_cuenta_bancaria: CUENTAS_GLOBAL.length ? CUENTAS_GLOBAL[0].id_cuenta_bancaria : '',
            monto: 0
        });

        renderPagosCierre();
    });

    $(document).on('change', '.cierre-pago-metodo', function() {
        var idx = parseInt($(this).data('idx'));
        pagosCierre[idx].id_metodo_pago = parseInt($(this).val());
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
