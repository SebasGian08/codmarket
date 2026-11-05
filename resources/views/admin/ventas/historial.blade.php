@extends('admin.layouts.app')

@section('title', 'Historial de Ventas')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Historial de Ventas</h4>
        </div>

        <a href="{{ route('admin.ventas.index') }}" class="btn btn-primary btn-round">
            <i class="fa fa-cart-plus"></i> Nueva Venta
        </a>

    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card venta-historial-filtros-card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.ventas.historial') }}" class="row g-3 align-items-end venta-historial-filtros">
                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label fw-semibold">Tienda</label>
                    <select name="id_tienda" class="form-select">
                        <option value="">Todas las tiendas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}"
                            {{ (string) request('id_tienda') === (string) $tienda->id_tienda ? 'selected' : '' }}>
                            {{ $tienda->codigo }} - {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <label class="form-label fw-semibold">Vendedor</label>
                    <select name="id_vendedor" class="form-select">
                        @if(!$vendedorUsuario)
                        <option value="">Todos los vendedores</option>
                        @endif
                        @foreach($vendedores as $vendedor)
                        <option value="{{ $vendedor->id_vendedor }}"
                            {{ ($vendedorUsuario && $vendedorUsuario->id_vendedor == $vendedor->id_vendedor) || request('id_vendedor') == $vendedor->id_vendedor ? 'selected' : '' }}>
                            {{ $vendedor->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-xl-6">
                    <label class="form-label fw-semibold">Rango de fechas</label>
                    <div class="input-group venta-historial-fechas">
                        <input type="date" name="fecha_desde" class="form-control"
                            aria-label="Fecha desde" value="{{ $fechaDesde }}">
                        <span class="input-group-text">hasta</span>
                        <input type="date" name="fecha_hasta" class="form-control"
                            aria-label="Fecha hasta" value="{{ $fechaHasta }}">
                    </div>
                </div>

                <div class="col-12 d-flex flex-wrap justify-content-xl-end gap-2 venta-historial-filtros-actions">
                    <button type="submit" class="btn btn-primary btn-round">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('admin.ventas.historial') }}" class="btn btn-light border btn-round">
                        <i class="fa fa-eraser"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Pago</th>
                            <th class="text-end">Total</th>
                            <th>Vendedor</th>
                            <th>Cobro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($ventas as $venta)
                        <tr>
                            <td><span class="badge bg-dark">{{ $venta->numero }}</span></td>
                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
                            <td class="fw-semibold">{{ $venta->nombre_cliente }}</td>
                            <td>{{ $venta->tienda->nombre }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $venta->metodoPago->nombre ?? '—' }}
                                </span>
                            </td>
                            <td class="text-end fw-bold">S/ {{ number_format($venta->total, 2) }}</td>
                            <td>{{ $venta->vendedor->nombre ?? '—' }}</td>
                            <td>
                                @if($venta->estado_cobro === 'cerrado')
                                    <span class="badge bg-success">Cerrado</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $venta->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $venta->estado ? 'Registrada' : 'Anulada' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-info btn-border btn-round btn-ver-venta"
                                    data-url="{{ route('admin.ventas.detalle', $venta->id_venta) }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @if($venta->estado)
                                    @if($venta->estado_cobro === 'cerrado')
                                        <button type="button" class="btn btn-sm btn-warning btn-round btn-reabrir"
                                            data-url="{{ route('admin.ventas.reabrir', $venta->id_venta) }}">
                                            <i class="fa fa-redo"></i> Reabrir
                                        </button>
                                    @endif

                                    <button class="btn btn-sm btn-danger btn-round btn-anular"
                                        data-url="{{ route('admin.ventas.anular', $venta->id_venta) }}">
                                        <i class="fa fa-ban"></i> Anular
                                    </button>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $ventas->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>

</div>

@include('admin.ventas.modals.detalle_modal')

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

    /* ============ VER DETALLE ============ */
    $(document).on('click', '.btn-ver-venta', function() {
        var url = $(this).data('url');

        $.getJSON(url, function(res) {
            var v = res.venta;
            var itemsHtml = v.detalle.map(function(d) {
                var variante = d.variante || {};
                var atributos = (variante.atributos || []).map(function(atributo) {
                    var nombre = atributo.atributo ? atributo.atributo.nombre : 'Atributo';
                    return '<span class="venta-historial-atributo"><span>' + escapeHtml(nombre) + '</span>' +
                        escapeHtml(atributo.valor) + '</span>';
                }).join('');

                return '<tr>' +
                    '<td><div class="venta-historial-producto">' + escapeHtml(variante.producto ? variante.producto.nombre : 'Producto no disponible') + '</div>' +
                    (atributos ? '<div class="venta-historial-atributos">' + atributos + '</div>' : '') +
                    '<div class="venta-historial-sku">SKU: ' + escapeHtml(variante.sku || '—') + '</div></td>' +
                    '<td class="text-center"><span class="venta-historial-cantidad">' + escapeHtml(d.cantidad) + '</span></td>' +
                    '<td class="text-end">' + moneda(d.precio) + '</td>' +
                    '<td class="text-end fw-semibold">' + moneda(d.subtotal) + '</td>' +
                    '</tr>';
            }).join('');

            $('#detalleVentaNumero').text(v.numero);
            $('#detalleVentaBody').html(
                '<div class="venta-historial-detalle">' +
                    '<section class="venta-historial-resumen">' +
                        '<div class="venta-historial-resumen-cabecera"><div><span class="venta-historial-eyebrow">Comprobante</span>' +
                            '<h6>Resumen de venta</h6></div><div class="venta-historial-estados">' +
                            '<span class="badge ' + (v.estado ? 'bg-success' : 'bg-danger') + '">' + (v.estado ? 'Registrada' : 'Anulada') + '</span>' +
                            '<span class="badge ' + (v.estado_cobro === 'cerrado' ? 'bg-primary' : 'bg-warning text-dark') + '">' +
                                (v.estado_cobro === 'cerrado' ? 'Cobro cerrado' : 'Cobro pendiente') + '</span></div></div>' +
                        '<div class="venta-historial-info-grid">' +
                            '<div class="venta-historial-info-item"><span>Cliente</span><strong>' + escapeHtml(v.nombre_cliente || '—') + '</strong></div>' +
                            '<div class="venta-historial-info-item"><span>Tienda</span><strong>' + escapeHtml(v.tienda ? v.tienda.nombre : '—') + '</strong></div>' +
                            '<div class="venta-historial-info-item"><span>Fecha</span><strong>' + escapeHtml(res.fecha_formateada || '—') + '</strong></div>' +
                            '<div class="venta-historial-info-item"><span>Caja</span><strong>' + escapeHtml(v.caja ? v.caja.nombre : '—') + '</strong></div>' +
                            '<div class="venta-historial-info-item"><span>Método de pago</span><strong>' + escapeHtml(v.metodo_pago ? v.metodo_pago.nombre : '—') + '</strong></div>' +
                            '<div class="venta-historial-info-item"><span>Vendedor</span><strong>' + escapeHtml(v.vendedor ? v.vendedor.nombre : '—') + '</strong></div>' +
                            '<div class="venta-historial-info-item"><span>Registrado por</span><strong>' +
                                escapeHtml(v.usuario ? [v.usuario.nombres, v.usuario.apellidos].filter(Boolean).join(' ') : '—') + '</strong></div>' +
                        '</div>' +
                        (v.observaciones ? '<div class="venta-historial-observaciones"><span>Observaciones</span><p>' + escapeHtml(v.observaciones).replace(/\n/g, '<br>') + '</p></div>' : '') +
                    '</section>' +
                    '<section class="venta-historial-productos">' +
                        '<div class="venta-historial-productos-titulo"><div><span class="venta-historial-eyebrow">Detalle</span><h6>Productos vendidos</h6></div>' +
                            '<span class="venta-historial-items-count">' + v.detalle.length + (v.detalle.length === 1 ? ' producto' : ' productos') + '</span></div>' +
                        '<div class="table-responsive"><table class="table venta-historial-tabla mb-0">' +
                            '<thead><tr><th>Producto</th><th class="text-center">Cant.</th><th class="text-end">Precio unitario</th><th class="text-end">Subtotal</th></tr></thead>' +
                            '<tbody>' + itemsHtml + '</tbody>' +
                        '</table></div>' +
                        '<div class="venta-historial-total"><span>Total de la venta</span><strong>' + moneda(v.total) + '</strong></div>' +
                    '</section>' +
                '</div>'
            );

            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleVenta')).show();
        });
    });

    /* ============ REABRIR ============ */
    $(document).on('click', '.btn-reabrir', function() {
        var url = $(this).data('url');

        Swal.fire({
            title: '¿Reabrir venta?',
            text: '¿Deseas reabrir esta venta para volver a trabajarla?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, reabrir',
            cancelButtonText: 'Cancelar'
        }).then(function(r) {
            if (r.isConfirmed) {
                $.post(url, { _token: '{{ csrf_token() }}' })
                    .done(function() {
                        Swal.fire({ icon: 'success', title: 'Venta reabierta', timer: 1400, showConfirmButton: false });
                        setTimeout(function() { location.reload(); }, 1000);
                    })
                    .fail(function(xhr) {
                        Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo reabrir la venta', 'error');
                    });
            }
        });
    });

    /* ============ ANULAR ============ */
    $(document).on('click', '.btn-anular', function() {
        var url = $(this).data('url');

        Swal.fire({
            title: '¿Anular venta?',
            text: 'El stock de los productos se restablecerá. Esta acción no se puede deshacer.',
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
                        Swal.fire({ icon: 'success', title: 'Anulada', timer: 1200, showConfirmButton: false });
                        setTimeout(function() { location.reload(); }, 800);
                    })
                    .fail(function(xhr) {
                        Swal.fire('Error', (xhr.responseJSON && xhr.responseJSON.message) || 'No se pudo anular', 'error');
                    });
            }
        });
    });
</script>
@endpush

@endsection
