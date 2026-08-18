@extends('admin.layouts.app')

@section('title', 'Transferencias')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Transferencias</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nueva Transferencia
        </button>

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
                            <th>N°</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transferencias as $trf)
                        <tr>
                            <td><span class="badge bg-dark">{{ $trf->numero }}</span></td>
                            <td>
                                <span class="badge bg-info text-dark me-1">{{ $trf->tiendaOrigen->codigo }}</span>
                                {{ $trf->tiendaOrigen->nombre }}
                            </td>
                            <td>
                                <span class="badge bg-info text-dark me-1">{{ $trf->tiendaDestino->codigo }}</span>
                                {{ $trf->tiendaDestino->nombre }}
                            </td>
                            <td>{{ $trf->fecha->format('d/m/Y') }}</td>
                            <td>{{ $trf->usuario->nombres }} {{ $trf->usuario->apellidos }}</td>
                            <td>
                                @if($trf->estado === 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($trf->estado === 'en_transito')
                                <span class="badge bg-info">En tránsito</span>
                                @elseif($trf->estado === 'recibida')
                                <span class="badge bg-success">Recibida</span>
                                @else
                                <span class="badge bg-danger">Anulada</span>
                                @endif
                            </td>
                            <td>

                                <button class="btn btn-sm btn-info btn-border btn-round btn-ver-detalle"
                                    data-url="{{ route('admin.transferencias.detalle', $trf->id_transferencia) }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @if($trf->estado === 'pendiente')
                                <button class="btn btn-sm btn-primary btn-round btn-enviar"
                                    data-url="{{ route('admin.transferencias.enviar', $trf->id_transferencia) }}">
                                    <i class="fa fa-paper-plane"></i> Enviar
                                </button>
                                @endif

                                @if($trf->estado === 'en_transito')
                                <button class="btn btn-sm btn-success btn-round btn-recibir"
                                    data-url="{{ route('admin.transferencias.recibir', $trf->id_transferencia) }}">
                                    <i class="fa fa-boxes"></i> Recibir
                                </button>
                                @endif

                                @if($trf->estado !== 'recibida' && $trf->estado !== 'anulada')
                                <button class="btn btn-sm btn-danger btn-round btn-anular"
                                    data-url="{{ route('admin.transferencias.anular', $trf->id_transferencia) }}">
                                    <i class="fa fa-ban"></i>
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

@include('admin.transferencias.modals.create')
@include('admin.transferencias.modals.detalle_modal')

@push('scripts')
<script>
    var VARIANTES_TRF = @json($variantes);
    var STOCK_TRF = @json($stockPorTienda);

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function stockEnOrigen(idVariante) {
        var tienda = $('#tiendaOrigen').val();
        if (!tienda) return 0;

        var m = STOCK_TRF[idVariante];
        return m ? (parseInt(m[tienda]) || 0) : 0;
    }

    /* ============ BÚSQUEDA ============ */
    $('#varianteInputTrf').on('input', function() {
        var t = $(this).val().trim().toLowerCase();
        var lista = $('#varianteResultadosTrf');

        if (!t) {
            lista.addClass('d-none').html('');
            return;
        }

        var matches = VARIANTES_TRF.filter(function(v) {
            return (v.producto || '').toLowerCase().indexOf(t) > -1 ||
                (v.sku || '').toLowerCase().indexOf(t) > -1;
        });

        if (!matches.length) {
            lista.html('<div class="trf-resultado py-2 text-muted">Sin resultados</div>');
        } else {
            lista.html(matches.slice(0, 12).map(function(v) {
                var attrs = (v.atributos || []).map(function(a) {
                    return escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor);
                }).join(', ');
                return '<div class="trf-resultado" data-id="' + v.id + '">' +
                    '<div class="fw-semibold">' + escapeHtml(v.producto) + '</div>' +
                    '<div class="small text-muted">' + escapeHtml(v.sku || 'Sin SKU') +
                    (attrs ? ' · ' + attrs : '') +
                    ' · Stock en origen: ' + stockEnOrigen(v.id) + '</div>' +
                    '</div>';
            }).join(''));
        }

        lista.removeClass('d-none');
    });

    $(document).on('mousedown', '#varianteResultadosTrf .trf-resultado', function(e) {
        e.preventDefault();

        var id = parseInt($(this).data('id'));
        var v = VARIANTES_TRF.find(function(x) { return x.id === id; });

        if (!v) return;

        agregarFilaTrf(v);

        $('#varianteInputTrf').val('');
        $('#varianteResultadosTrf').addClass('d-none').html('');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.trf-buscador').length) {
            $('#varianteResultadosTrf').addClass('d-none');
        }
    });

    /* ============ FILAS ============ */
    function agregarFilaTrf(v) {
        var filas = $('#trfFilas');

        if (filas.find('input[name="items[' + v.id + '][id_variante]"]').length) {
            Swal.fire('Atención', 'El producto ya está en el detalle', 'warning');
            return;
        }

        var attrs = (v.atributos || []).map(function(a) {
            return escapeHtml(a.atributo) + ': ' + escapeHtml(a.valor);
        }).join(', ');

        var fila = $(`
            <tr data-variante="${v.id}">
                <td class="fw-semibold">${escapeHtml(v.producto)}<br>
                    <span class="small text-muted">${escapeHtml(v.sku || 'Sin SKU')}${attrs ? ' · ' + attrs : ''}</span>
                </td>
                <td style="width:140px">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control trf-cantidad" value="1" min="1" required>
                        <input type="hidden" name="items[${v.id}][id_variante]" value="${v.id}">
                        <input type="hidden" name="items[${v.id}][cantidad]" value="1">
                    </div>
                    <div class="small text-muted mt-1">Stock origen: <span class="trf-stock">${stockEnOrigen(v.id)}</span></div>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-border btn-round btn-quitar-trf">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        filas.append(fila);
    }

    $(document).on('click', '.btn-quitar-trf', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('input', '.trf-cantidad', function() {
        var fila = $(this).closest('tr');
        fila.find('input[name^="items["][name$="[cantidad]"]').val($(this).val());

        var stock = parseInt($(this).closest('td').find('.trf-stock').text()) || 0;
        var cant = parseInt($(this).val()) || 0;

        if (stock > 0 && cant > stock) {
            $(this).val(stock);
            fila.find('input[name^="items["][name$="[cantidad]"]').val(stock);
            Swal.fire('Stock insuficiente', 'Stock disponible en origen: ' + stock, 'warning');
        }
    });

    $('#tiendaOrigen').on('change', function() {
        // actualiza el stock disponible mostrado en cada fila
        $('#trfFilas tr').each(function() {
            var id = $(this).data('variante');
            $(this).find('.trf-stock').text(stockEnOrigen(id));

            var stock = stockEnOrigen(id);
            var cantInput = $(this).find('.trf-cantidad');
            if (stock > 0 && (parseInt(cantInput.val()) || 0) > stock) {
                cantInput.val(stock);
                cantInput.closest('td').find('input[name^="items["][name$="[cantidad]"]').val(stock);
            }
        });
    });

    /* ============ SUBMIT ============ */
    $('#formTransferencia').on('submit', function(e) {
        var filas = $('#trfFilas tr').length;

        if (!filas) {
            e.preventDefault();
            Swal.fire('Atención', 'Agrega al menos un producto al detalle', 'warning');
            return;
        }

        // Validación de stock de origen
        var error = false;
        $('#trfFilas tr').each(function() {
            var id = $(this).data('variante');
            var cant = parseInt($(this).find('.trf-cantidad').val()) || 0;
            var stock = stockEnOrigen(id);

            if (stock <= 0 || cant > stock) {
                error = true;
            }
        });

        if (error) {
            e.preventDefault();
            Swal.fire('Stock insuficiente', 'Uno o más productos superan el stock de la tienda origen', 'warning');
            return;
        }

        $('#btnGuardarTrf').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    });

    /* ============ VER DETALLE ============ */
    $(document).on('click', '.btn-ver-detalle', function() {
        var url = $(this).data('url');

        $.get(url, function(html) {
            $('#modalDetalleTrf .modal-content').html(html);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleTrf')).show();
        });
    });

    /* ============ ENVIAR / RECIBIR / ANULAR ============ */
    function confirmarAccion(url, titulo, texto, color) {
        Swal.fire({
            title: titulo,
            text: texto,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: color || '#3085d6',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.post(url, { _token: '{{ csrf_token() }}' })
                    .done(function() {
                        Swal.fire({ icon: 'success', title: 'Completado', timer: 1200, showConfirmButton: false });
                        setTimeout(function() { location.reload(); }, 800);
                    })
                    .fail(function(xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) ||
                            (xhr.responseText) || 'No se pudo completar la acción';
                        Swal.fire('Error', msg, 'error');
                    });
            }
        });
    }

    $(document).on('click', '.btn-enviar', function() {
        confirmarAccion($(this).data('url'), '¿Enviar transferencia?',
            'Se descontará el stock de la tienda origen', '#3085d6');
    });

    $(document).on('click', '.btn-recibir', function() {
        confirmarAccion($(this).data('url'), '¿Recibir transferencia?',
            'El stock se sumará a la tienda destino', '#28a745');
    });

    $(document).on('click', '.btn-anular', function() {
        confirmarAccion($(this).data('url'), '¿Anular transferencia?',
            'Si estaba en tránsito, el stock se devolverá a la tienda origen', '#d33');
    });
</script>
@endpush

<style>
    .trf-resultados {
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

    html[data-theme="dark"] .trf-resultados {
        background: var(--ka-surface, #273243);
        border-color: var(--ka-border, #3a4658);
    }

    .trf-resultado {
        padding: .55rem .9rem;
        cursor: pointer;
        border-bottom: 1px dashed rgba(0, 0, 0, .06);
    }

    .trf-resultado:last-child {
        border-bottom: 0;
    }

    .trf-resultado:hover {
        background: rgba(0, 0, 0, .04);
    }

    html[data-theme="dark"] .trf-resultado:hover {
        background: rgba(255, 255, 255, .06);
    }
</style>

@endsection
