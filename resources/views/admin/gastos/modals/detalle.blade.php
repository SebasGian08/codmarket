<div class="modal-header">
    <h5>Detalle del Gasto</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <div class="row g-3 mb-3">

        <div class="col-md-4">
            <span class="small text-muted d-block">Número</span>
            <span class="fw-bold">{{ $gasto->numero }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Tipo</span>
            <span class="badge bg-warning text-dark">{{ $gasto->tipoGasto->nombre }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Estado</span>
            <span class="badge {{ $gasto->estado ? 'bg-success' : 'bg-danger' }}">
                {{ $gasto->estado ? 'Registrado' : 'Anulado' }}
            </span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Tienda</span>
            <span class="fw-semibold">{{ $gasto->tienda->nombre }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Cuenta Bancaria</span>
            <span class="fw-semibold">{{ $gasto->cuentaBancaria->nombre ?? '—' }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Fecha</span>
            <span class="fw-semibold">{{ $gasto->fecha->format('d/m/Y') }}</span>
        </div>

        <div class="col-md-12">
            <span class="small text-muted d-block">Descripción</span>
            <span class="fw-semibold">{{ $gasto->descripcion }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Monto</span>
            <span class="fw-bold text-danger h5">S/ {{ number_format($gasto->monto, 2) }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Moneda</span>
            <span class="fw-semibold">{{ $gasto->moneda }}</span>
        </div>

        <div class="col-md-4">
            <span class="small text-muted d-block">Registrado por</span>
            <span class="fw-semibold">{{ $gasto->usuario->nombres }} {{ $gasto->usuario->apellidos }}</span>
        </div>

        @if($gasto->observacion)
        <div class="col-md-12">
            <span class="small text-muted d-block">Observación</span>
            <span>{{ $gasto->observacion }}</span>
        </div>
        @endif

    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
        <i class="fa fa-times"></i> Cerrar
    </button>
</div>
