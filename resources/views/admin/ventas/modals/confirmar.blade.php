{{-- ============================================================
    PARTIAL REUTILIZABLE: Modal "Confirmar Venta"
    ------------------------------------------------------------
    Uso:
      1) @include('admin.ventas.modals.confirmar', ['metodosPagos' => $metodosPagos])
      2) Llamar en JS:  abrirConfirmarVenta({
             total: 239.90,
             items: [{ id_variante, cantidad, precio }, ...],
             idCaja: 1,
             clienteNombre: '...',
             clienteId: null,
             onRegistrada: function(res) { ... }
         })
    El modal envía el POST a admin.ventas.guardar y muestra el resumen.
    ============================================================ --}}

@php($confMetodos = $metodosPagos ?? collect())

<div class="modal fade" id="modalConfirmarVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-check-circle me-1"></i> Confirmar Venta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">

                <div class="d-flex justify-content-between align-items-center small mb-3">
                    <span class="text-muted">Cliente</span>
                    <span class="fw-semibold text-truncate ms-3" id="confirmarVentaCliente">—</span>
                </div>

                <div class="text-center mb-4">
                    <div class="small text-uppercase fw-semibold text-muted mb-1">Total</div>
                    <div class="venta-total-mdl" id="confirmarVentaTotal">S/ 0.00</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted mb-2">Método de pago</label>
                    <div class="d-flex flex-wrap gap-2" id="confirmarVentaMetodos">
                        @foreach($confMetodos as $metodo)
                        <button type="button" class="btn btn-outline-primary btn-sm btn-metodo-pago"
                            data-id="{{ $metodo->id_metodo_pago }}"
                            data-codigo="{{ $metodo->codigo }}">
                            {{ $metodo->nombre }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div id="confirmarVentaEfectivo" class="d-none">
                    <div class="venta-efectivo-box p-3 rounded-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Recibido</label>
                                <input type="number" id="confirmarVentaRecibido" class="form-control"
                                    min="0" step="0.01" placeholder="0.00" autocomplete="off">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Vuelto</label>
                                <div class="fs-4 fw-bold text-success" id="confirmarVentaVuelto">S/ 0.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="confirmarVentaError" class="alert alert-danger d-none mb-0"></div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border btn-round" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success btn-lg btn-round px-4" id="btnConfirmarVenta">
                    <i class="fa fa-check-circle"></i> Confirmar Venta
                </button>
            </div>

        </div>
    </div>
</div>

<style>
    .venta-total-mdl {
        font-size: 2rem;
        font-weight: 800;
        color: var(--bs-success);
        line-height: 1.1;
    }

    .venta-efectivo-box {
        background: #f4f6f9;
        border: 1px solid rgba(0, 0, 0, .08);
    }

    html[data-theme="dark"] .venta-efectivo-box {
        background: var(--ka-surface-2);
        border-color: var(--ka-border);
    }

    #confirmarVentaMetodos .btn-metodo-pago.active {
        color: #fff;
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
        box-shadow: 0 2px 6px rgba(13, 110, 253, .35);
    }
</style>

@push('scripts')
<script>
(function() {
    var modalEl = document.getElementById('modalConfirmarVenta');
    if (!modalEl) return;

    var metodos = @json($confMetodos->map(function ($m) {
        return ['id' => $m->id_metodo_pago, 'nombre' => $m->nombre, 'codigo' => $m->codigo];
    })->values());

    var totalEl = document.getElementById('confirmarVentaTotal');
    var clienteEl = document.getElementById('confirmarVentaCliente');
    var metodosWrap = document.getElementById('confirmarVentaMetodos');
    var efectivoWrap = document.getElementById('confirmarVentaEfectivo');
    var recibidoInput = document.getElementById('confirmarVentaRecibido');
    var vueltoEl = document.getElementById('confirmarVentaVuelto');
    var errorEl = document.getElementById('confirmarVentaError');
    var confirmBtn = document.getElementById('btnConfirmarVenta');

    var estado = null;

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function dinero(n) {
        return 'S/ ' + parseFloat(n || 0).toFixed(2);
    }

    function esEfectivo(m) {
        if (!m) return false;
        if (m.codigo) return String(m.codigo).toLowerCase() === 'efectivo';
        return (m.nombre || '').toLowerCase().indexOf('efectivo') !== -1;
    }

    function seleccionarMetodo(id) {
        estado.metodo = metodos.find(function(m) { return m.id === id; }) || null;

        metodosWrap.querySelectorAll('.btn-metodo-pago').forEach(function(b) {
            b.classList.toggle('active', String(b.getAttribute('data-id')) === String(id));
        });

        var efectivo = esEfectivo(estado.metodo);
        efectivoWrap.classList.toggle('d-none', !efectivo);

        if (efectivo) {
            recibidoInput.value = '';
            calcularVuelto();
            setTimeout(function() {
                recibidoInput.focus();
                recibidoInput.select();
            }, 150);
        }
    }

    function calcularVuelto() {
        var total = estado ? estado.total : 0;
        var rec = parseFloat(recibidoInput.value) || 0;
        var vuelto = rec - total;

        vueltoEl.textContent = dinero(Math.max(0, vuelto));
        vueltoEl.classList.toggle('text-danger', rec > 0 && vuelto < 0);
        errorEl.classList.add('d-none');
    }

    metodosWrap.addEventListener('click', function(e) {
        var btn = e.target.closest('.btn-metodo-pago');
        if (btn) seleccionarMetodo(parseInt(btn.getAttribute('data-id')));
    });

    recibidoInput.addEventListener('input', calcularVuelto);

    recibidoInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            confirmBtn.click();
        }
    });

    confirmBtn.addEventListener('click', function() {
        if (!estado) return;

        var m = estado.metodo;
        if (!m) {
            errorEl.textContent = 'Selecciona un método de pago';
            errorEl.classList.remove('d-none');
            return;
        }

        if (esEfectivo(m)) {
            var rec = parseFloat(recibidoInput.value) || 0;
            if (rec < estado.total) {
                errorEl.textContent = 'El monto recibido es menor al total de la venta';
                errorEl.classList.remove('d-none');
                return;
            }
            estado.montoRecibido = rec;
        } else {
            estado.montoRecibido = null;
        }

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Registrando...';
        errorEl.classList.add('d-none');

        var data = {
            _token: '{{ csrf_token() }}',
            id_caja: estado.idCaja,
            id_cliente: estado.clienteId || null,
            nombre_cliente: estado.clienteNombre || 'CLIENTES VARIOS',
            id_metodo_pago: m.id,
            items: estado.items
        };

        if (estado.montoRecibido != null) data.monto_recibido = estado.montoRecibido;

        $.ajax({
            url: '{{ route("admin.ventas.guardar") }}',
            type: 'POST',
            data: data,
            dataType: 'json'
        }).done(function(res) {
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            var onRegistrada = estado.onRegistrada;

            Swal.fire({
                icon: 'success',
                title: 'Venta registrada',
                html: '<div style="text-align:left;">' +
                    '<b>N°:</b> ' + esc(res.numero) + '<br>' +
                    '<b>Cliente:</b> ' + esc(res.cliente) + '<br>' +
                    '<b>Tienda:</b> ' + esc(res.tienda) + ' · ' + esc(res.caja) + '<br>' +
                    (res.vendedor ? '<b>Vendedor:</b> ' + esc(res.vendedor) + '<br>' : '') +
                    '<b>Fecha:</b> ' + esc(res.fecha) + '<br>' +
                    '<b>Total:</b> ' + dinero(res.total) +
                    '</div>',
                confirmButtonText: 'OK'
            }).then(function() {
                if (onRegistrada) onRegistrada(res);
            });
        }).fail(function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo registrar la venta';
            errorEl.textContent = msg;
            errorEl.classList.remove('d-none');
        }).always(function() {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fa fa-check-circle"></i> Confirmar Venta';
        });
    });

    window.abrirConfirmarVenta = function(opts) {
        estado = {
            total: parseFloat(opts.total) || 0,
            items: opts.items || [],
            idCaja: opts.idCaja,
            clienteNombre: opts.clienteNombre,
            clienteId: opts.clienteId,
            onRegistrada: opts.onRegistrada || null
        };

        totalEl.textContent = dinero(estado.total);
        clienteEl.textContent = estado.clienteNombre || 'CLIENTES VARIOS';

        errorEl.classList.add('d-none');
        efectivoWrap.classList.add('d-none');

        var efectivo = metodos.filter(esEfectivo)[0] || null;
        var porDefecto = efectivo ? efectivo.id : (metodos.length ? metodos[0].id : null);

        if (porDefecto) {
            seleccionarMetodo(porDefecto);
        } else {
            metodosWrap.querySelectorAll('.btn-metodo-pago').forEach(function(b) {
                b.classList.remove('active');
            });
        }

        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    };
})();
</script>
@endpush
