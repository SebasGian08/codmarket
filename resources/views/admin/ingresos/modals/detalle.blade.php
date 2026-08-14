<div class="modal-header">
    <h5>Ingreso {{ $ingreso->numero }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <span class="small text-muted">Tipo</span>
            <div>
                @if($ingreso->tipo === 'compra')
                <span class="badge bg-primary">Compra</span>
                @else
                <span class="badge bg-info text-dark">Ajuste</span>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Tienda</span>
            <div class="fw-semibold">{{ $ingreso->tienda->nombre }}</div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Fecha</span>
            <div class="fw-semibold">{{ $ingreso->fecha->format('d/m/Y') }}</div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Proveedor</span>
            <div class="fw-semibold">{{ $ingreso->proveedor->nombre ?? '—' }}</div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Registrado por</span>
            <div class="fw-semibold">{{ $ingreso->usuario->nombres }} {{ $ingreso->usuario->apellidos }}</div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Estado</span>
            <div>
                <span class="badge {{ $ingreso->estado ? 'bg-success' : 'bg-danger' }}">
                    {{ $ingreso->estado ? 'Registrado' : 'Anulado' }}
                </span>
            </div>
        </div>
        @if($ingreso->observacion)
        <div class="col-12">
            <span class="small text-muted">Observación</span>
            <div>{{ $ingreso->observacion }}</div>
        </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-striped mb-0">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-end">Costo</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($ingreso->detalle as $detalle)
                @php
                $subtotal = $detalle->cantidad * $detalle->costo;
                $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $detalle->variante->producto->nombre ?? 'Producto' }}</td>
                    <td>{{ $detalle->variante->sku ?? '—' }}</td>
                    <td class="text-center">{{ $detalle->cantidad }}</td>
                    <td class="text-end">S/ {{ number_format($detalle->costo, 2) }}</td>
                    <td class="text-end fw-bold">S/ {{ number_format($subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total</th>
                    <th class="text-end">S/ {{ number_format($total, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
        <i class="fa fa-times"></i> Cerrar
    </button>
</div>
