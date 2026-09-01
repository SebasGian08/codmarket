@extends('admin.layouts.app')

@section('title', 'Cajas')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Cajas</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalAbrir">
            <i class="fa fa-folder-open"></i> Abrir Caja
        </button>

    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @php
    $abiertas = $cajas->where('estado', 1);
    $cerradas = $cajas->where('estado', 0);
    @endphp

    @if($abiertas->isEmpty())
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="fa fa-exclamation-triangle"></i>
        <span>No hay ninguna caja abierta. Abre una caja para poder registrar ventas en el punto de venta.</span>
        <button class="btn btn-sm btn-warning ms-auto" data-bs-toggle="modal" data-bs-target="#modalAbrir">
            <i class="fa fa-folder-open"></i> Abrir caja
        </button>
    </div>
    @endif

    @if($abiertas->isNotEmpty())
    <div class="card">
        <div class="card-header bg-transparent">
            <h6 class="fw-bold mb-0"><i class="fa fa-folder-open text-success me-1"></i> Cajas abiertas</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tienda</th>
                            <th>Caja</th>
                            <th>Abierta por</th>
                            <th>Vendedor</th>
                            <th class="text-end">Monto apertura</th>
                            <th class="text-end">Ventas del turno</th>
                            <th class="text-center">Ventas</th>
                            <th>Apertura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($abiertas as $caja)
                        <tr>
                            <td class="fw-semibold">
                                <span class="badge bg-dark me-1">{{ $caja->tienda->codigo }}</span>
                                {{ $caja->tienda->nombre }}
                            </td>
                            <td>{{ $caja->nombre }}</td>
                            <td>
                                {{ $caja->usuario->nombres }} {{ $caja->usuario->apellidos }}
                                @if((int) $caja->id_usuario === (int) auth()->id())
                                <span class="badge bg-info text-dark">mía</span>
                                @endif
                            </td>
                            <td>{{ $caja->vendedor->nombre ?? '—' }}</td>
                            <td class="text-end">S/ {{ number_format($caja->monto_apertura, 2) }}</td>
                            <td class="text-end fw-bold text-success">S/ {{ number_format($caja->total_ventas, 2) }}</td>
                            <td class="text-center"><span class="badge bg-primary">{{ $caja->nro_ventas }}</span></td>
                            <td>{{ $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y H:i') : '—' }}</td>
                            <td>
                                @if((int) $caja->id_usuario === (int) auth()->id())
                                <button class="btn btn-sm btn-success btn-round btn-cerrar-caja"
                                    data-id="{{ $caja->id_caja }}"
                                    data-tienda="{{ $caja->tienda->nombre }} ({{ $caja->tienda->codigo }})"
                                    data-apertura="{{ number_format($caja->monto_apertura, 2) }}"
                                    data-ventas="{{ number_format($caja->total_ventas, 2) }}"
                                    data-esperado="{{ number_format($caja->efectivo_esperado, 2) }}">
                                    <i class="fa fa-lock"></i> Cerrar
                                </button>
                                @else
                                <span class="badge bg-light border text-muted" title="Solo la persona que abrió esta caja puede cerrarla">
                                    <i class="fa fa-lock text-muted"></i> No disponible
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card mt-3">
        <div class="card-header bg-transparent">
            <h6 class="fw-bold mb-0"><i class="fa fa-folder-closed text-muted me-1"></i> Historial de cajas</h6>
        </div>
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover" id="basic-datatables">

                    <thead>
                        <tr>
                            <th>Tienda</th>
                            <th>Caja</th>
                            <th>Vendedor</th>
                            <th>Apertura</th>
                            <th>Cierre</th>
                            <th class="text-end">Monto apertura</th>
                            <th class="text-end">Monto cierre</th>
                            <th class="text-end">Ventas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cerradas as $caja)
                        <tr>
                            <td class="fw-semibold">
                                <span class="badge bg-dark me-1">{{ $caja->tienda->codigo }}</span>
                                {{ $caja->tienda->nombre }}
                            </td>
                            <td>{{ $caja->nombre }}</td>
                            <td>{{ $caja->vendedor->nombre ?? '—' }}</td>
                            <td>{{ $caja->fecha_apertura ? $caja->fecha_apertura->format('d/m/Y H:i') : '—' }}</td>
                            <td>{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : '—' }}</td>
                            <td class="text-end">S/ {{ number_format($caja->monto_apertura, 2) }}</td>
                            <td class="text-end">S/ {{ $caja->monto_cierre !== null ? number_format($caja->monto_cierre, 2) : '—' }}</td>
                            <td class="text-end">S/ {{ number_format($caja->total_ventas, 2) }}</td>
                            <td>
                                <span class="badge bg-secondary">Cerrada</span>
                            </td>
                            <td>

                                <form action="{{ route('admin.cajas.destroy', $caja->id_caja) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger btn-border btn-round btn-delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@include('admin.cajas.modals.abrir')
@include('admin.cajas.modals.cerrar')

@push('scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '.btn-cerrar-caja', function() {
            var $btn = $(this);

            $('#cerrarId').val($btn.data('id'));
            $('#cerrarInfo').html(
                'Tienda: <b>' + $btn.data('tienda') + '</b><br>' +
                'Monto apertura: <b>S/ ' + $btn.data('apertura') + '</b><br>' +
                'Efectivo esperado: <b class="text-primary">S/ ' + $btn.data('esperado') + '</b><br>' +
                'Ventas del turno: <b class="text-success">S/ ' + $btn.data('ventas') + '</b>'
            );
            $('#cerrarEsperado').val($btn.data('esperado'));
            $('#cerrarDiferencia').html('');

            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalCerrar')).show();
        });

    });
</script>
@endpush

@endsection
