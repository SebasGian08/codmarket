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

    <div class="card">
        <div class="card-body">

            <form method="GET" class="row g-2 align-items-end mb-3">

                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Tienda</label>
                    <select name="id_tienda" class="form-control">
                        <option value="">Todas las tiendas</option>
                        @foreach($tiendas as $tienda)
                        <option value="{{ $tienda->id_tienda }}"
                            {{ request('id_tienda') == $tienda->id_tienda ? 'selected' : '' }}>
                            {{ $tienda->codigo }} - {{ $tienda->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Vendedor</label>
                    <select name="id_vendedor" class="form-control">
                        <option value="">Todos los vendedores</option>
                        @foreach($vendedores as $vendedor)
                        <option value="{{ $vendedor->id_vendedor }}"
                            {{ request('id_vendedor') == $vendedor->id_vendedor ? 'selected' : '' }}>
                            {{ $vendedor->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                    <a href="{{ route('admin.ventas.historial') }}" class="btn btn-light border">Limpiar</a>
                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Tienda</th>
                            <th>Pago</th>
                            <th class="text-end">Total</th>
                            <th>Vendedor</th>
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

            <div class="d-flex justify-content-end">
                {{ $ventas->links() }}
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
                return '<tr>' +
                    '<td>' + escapeHtml(d.variante.producto.nombre) + '</td>' +
                    '<td>' + escapeHtml(d.variante.sku || '—') + '</td>' +
                    '<td class="text-center">' + d.cantidad + '</td>' +
                    '<td class="text-end">' + moneda(d.precio) + '</td>' +
                    '<td class="text-end">' + moneda(d.subtotal) + '</td>' +
                    '</tr>';
            }).join('');

            $('#detalleVentaNumero').text(v.numero);
            $('#detalleVentaBody').html(
                '<div class="row g-2 mb-3">' +
                '<div class="col-md-4"><span class="small text-muted">Cliente</span><div class="fw-semibold">' + escapeHtml(v.nombre_cliente) + '</div></div>' +
                '<div class="col-md-4"><span class="small text-muted">Tienda</span><div class="fw-semibold">' + escapeHtml(v.tienda.nombre) + '</div></div>' +
                '<div class="col-md-4"><span class="small text-muted">Fecha</span><div class="fw-semibold">' + escapeHtml(v.created_at) + '</div></div>' +
                '<div class="col-md-4"><span class="small text-muted">Caja</span><div class="fw-semibold">' + escapeHtml(v.caja.nombre) + '</div></div>' +
                '<div class="col-md-4"><span class="small text-muted">Pago</span><div class="fw-semibold">' + escapeHtml(v.metodo_pago ? v.metodo_pago.nombre : '—') + '</div></div>' +
                '<div class="col-md-4"><span class="small text-muted">Vendedor</span><div class="fw-semibold">' + escapeHtml(v.vendedor ? v.vendedor.nombre : '—') + '</div></div>' +
                '<div class="col-md-4"><span class="small text-muted">Registrado por</span><div class="fw-semibold">' + escapeHtml(v.usuario.nombres + ' ' + v.usuario.apellidos) + '</div></div>' +
                '</div>' +
                '<div class="table-responsive">' +
                '<table class="table table-sm table-striped mb-0">' +
                '<thead><tr><th>Producto</th><th>SKU</th><th class="text-center">Cant.</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr></thead>' +
                '<tbody>' + itemsHtml + '</tbody>' +
                '<tfoot><tr><th colspan="4" class="text-end">TOTAL</th><th class="text-end">' + moneda(v.total) + '</th></tr></tfoot>' +
                '</table></div>'
            );

            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleVenta')).show();
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
