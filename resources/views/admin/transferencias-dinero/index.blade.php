@extends('admin.layouts.app')

@section('title', 'Transferencias de Dinero')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Transferencias de Dinero</h4>
        </div>
        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateTransferenciaDinero">
            <i class="fa fa-plus"></i> Nueva Transferencia
        </button>
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="basic-datatables">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th class="text-end">Monto</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transferencias as $t)
                        <tr>
                            <td><span class="badge bg-dark">{{ $t->numero }}</span></td>
                            <td>
                                @if($t->id_caja_origen)
                                <span class="badge bg-success">Caja: {{ $t->cajaOrigen->nombre ?? '—' }}</span>
                                @else
                                <span class="badge bg-info text-dark">Cuenta: {{ $t->cuentaOrigen->nombre_banco ?? '—' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($t->id_caja_destino)
                                <span class="badge bg-success">Caja: {{ $t->cajaDestino->nombre ?? '—' }}</span>
                                @else
                                <span class="badge bg-info text-dark">Cuenta: {{ $t->cuentaDestino->nombre_banco ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="fw-bold text-primary">S/ {{ number_format($t->monto, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $t->usuario->nombres }} {{ $t->usuario->apellidos }}</td>
                            <td>
                                <span class="badge {{ $t->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $t->estado ? 'Realizada' : 'Anulada' }}
                                </span>
                            </td>
                            <td>
                                @if($t->estado)
                                <button class="btn btn-sm btn-danger btn-round btn-anular-transferencia-dinero"
                                    data-url="{{ route('admin.transferencias-dinero.anular', $t->id_transferencia_dinero) }}">
                                    <i class="fa fa-ban"></i> Anular
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

@include('admin.transferencias-dinero.modals.create')

@push('scripts')
<script>
    $(document).ready(function() {

        var CAJAS = @json($cajasAbiertas);
        var CUENTAS = @json($cuentasBancarias);

        /* ============ CARGAR ORIGEN (cajas por tienda) ============ */
        function cargarCajasOrigen() {
            var idTienda = $('#tiendaTransferencia').val();
            var select = $('#cajaOrigen');
            select.html('<option value="">Selecciona caja</option>');
            if (!idTienda) return;
            CAJAS.forEach(function(c) {
                if (c.id_tienda == idTienda) {
                    select.append('<option value="' + c.id_caja + '">' + c.nombre + '</option>');
                }
            });
        }
        $('#tiendaTransferencia').on('change', cargarCajasOrigen);

        /* ============ SWITCH ORIGEN CAJA/CUENTA ============ */
        $('input[name="origen_tipo"]').on('change', function() {
            var esCaja = $(this).val() === 'caja';
            $('#cajaOrigen').prop('disabled', !esCaja);
            $('#cuentaOrigen').prop('disabled', esCaja);
        });

        /* ============ SWITCH DESTINO CAJA/CUENTA ============ */
        $('input[name="destino_tipo"]').on('change', function() {
            var esCaja = $(this).val() === 'caja';
            $('#cajaDestino').prop('disabled', !esCaja);
            $('#cuentaDestino').prop('disabled', esCaja);
        });

        /* ============ SUBMIT ============ */
        $('#formTransferenciaDinero').on('submit', function() {
            $('#btnGuardarTransferencia').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        });

        /* ============ ANULAR ============ */
        $(document).on('click', '.btn-anular-transferencia-dinero', function() {
            var url = $(this).data('url');

            Swal.fire({
                title: '¿Anular transferencia de dinero?',
                text: 'Se devolverá el dinero a su origen. Esta acción no se puede deshacer.',
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

    });
</script>
@endpush

@endsection