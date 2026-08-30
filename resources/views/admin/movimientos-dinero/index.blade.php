@extends('admin.layouts.app')

@section('title', 'Movimientos de Dinero')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Movimientos de Dinero</h4>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.movimientos-dinero.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-1">Tipo de movimiento</label>
                    <select name="tipo" class="form-control">
                        <option value="">Todos</option>
                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_movimiento_dinero }}" {{ request('tipo') == $tipo->id_tipo_movimiento_dinero ? 'selected' : '' }}>
                            {{ $tipo->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Destino</label>
                    <select name="destino" class="form-control">
                        <option value="">Todos</option>
                        <option value="caja" {{ request('destino') == 'caja' ? 'selected' : '' }}>Caja</option>
                        <option value="cuenta" {{ request('destino') == 'cuenta' ? 'selected' : '' }}>Cuenta bancaria</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-round">
                        <i class="fa fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.movimientos-dinero.index') }}" class="btn btn-secondary btn-round">
                        <i class="fa fa-eraser"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="basic-datatables">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Tipo</th>
                            <th>Destino</th>
                            <th class="text-end">Monto</th>
                            <th>Método de pago</th>
                            <th>Referencia</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimientos as $m)
                        <tr>
                            <td><span class="badge bg-dark">{{ $m->id_movimiento_dinero }}</span></td>
                            <td>{{ $m->tipoMovimientoDinero->nombre ?? '—' }}</td>
                            <td>
                                @if($m->id_caja)
                                <span class="badge bg-success">Caja: {{ $m->caja->nombre ?? '—' }}</span>
                                @elseif($m->id_cuenta_bancaria)
                                <span class="badge bg-info text-dark">Cuenta: {{ $m->cuentaBancaria->nombre_banco ?? '—' }}</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-bold {{ $m->monto_signed >= 0 ? 'text-success' : 'text-danger' }}">
                                S/ {{ number_format($m->monto_signed, 2) }}
                            </td>
                            <td>{{ $m->metodoPago->nombre ?? '—' }}</td>
                            <td>
                                @if($m->referencia)
                                <span class="text-muted">{{ class_basename(get_class($m->referencia)) }} #{{ $m->id_referencia }}</span>
                                @else
                                <span class="text-muted">#{{ $m->id_referencia }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y H:i') }}</td>
                            <td>{{ $m->usuarioRegistro ? $m->usuarioRegistro->nombres . ' ' . $m->usuarioRegistro->apellidos : '—' }}</td>
                            <td>
                                <span class="badge {{ $m->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $m->estado ? 'Activo' : 'Anulado' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No hay movimientos de dinero registrados</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $movimientos->links() }}
            </div>
        </div>
    </div>

</div>

@endsection
