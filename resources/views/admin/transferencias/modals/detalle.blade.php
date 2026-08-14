<div class="modal-header">
    <h5>Transferencia {{ $transferencia->numero }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <span class="small text-muted">Origen</span>
            <div class="fw-semibold">
                <span class="badge bg-info text-dark me-1">{{ $transferencia->tiendaOrigen->codigo }}</span>
                {{ $transferencia->tiendaOrigen->nombre }}
            </div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Destino</span>
            <div class="fw-semibold">
                <span class="badge bg-info text-dark me-1">{{ $transferencia->tiendaDestino->codigo }}</span>
                {{ $transferencia->tiendaDestino->nombre }}
            </div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Fecha</span>
            <div class="fw-semibold">{{ $transferencia->fecha->format('d/m/Y') }}</div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Registrado por</span>
            <div class="fw-semibold">{{ $transferencia->usuario->nombres }} {{ $transferencia->usuario->apellidos }}</div>
        </div>
        <div class="col-md-4">
            <span class="small text-muted">Estado</span>
            <div>
                @if($transferencia->estado === 'pendiente')
                <span class="badge bg-warning text-dark">Pendiente</span>
                @elseif($transferencia->estado === 'en_transito')
                <span class="badge bg-info">En tránsito</span>
                @elseif($transferencia->estado === 'recibida')
                <span class="badge bg-success">Recibida</span>
                @else
                <span class="badge bg-danger">Anulada</span>
                @endif
            </div>
        </div>
        @if($transferencia->observacion)
        <div class="col-12">
            <span class="small text-muted">Observación</span>
            <div>{{ $transferencia->observacion }}</div>
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
                </tr>
            </thead>
            <tbody>
                @foreach($transferencia->detalle as $detalle)
                <tr>
                    <td>{{ $detalle->variante->producto->nombre ?? 'Producto' }}</td>
                    <td>{{ $detalle->variante->sku ?? '—' }}</td>
                    <td class="text-center">{{ $detalle->cantidad }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-end">Total de unidades</th>
                    <th class="text-center">{{ $transferencia->detalle->sum('cantidad') }}</th>
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
