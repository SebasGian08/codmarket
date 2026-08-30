@extends('admin.layouts.app')

@section('title', 'Ingresos Económicos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center">
            <h4 class="page-title">Ingresos Económicos</h4>
        </div>
        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateIngresoEco">
            <i class="fa fa-plus"></i> Nuevo Ingreso
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
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Destino</th>
                            <th class="text-end">Monto</th>
                            <th>Fecha</th>
                            <th>Registrado por</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingresos as $ingreso)
                        <tr>
                            <td><span class="badge bg-dark">{{ $ingreso->numero }}</span></td>
                            <td>
                                <span class="badge bg-primary">{{ $ingreso->tipoIngresoEconomico->nombre }}</span>
                            </td>
                            <td class="fw-semibold">{{ $ingreso->descripcion }}</td>
                            <td>
                                @if($ingreso->id_caja)
                                <span class="badge bg-success">Caja: {{ $ingreso->caja->nombre ?? '—' }}</span>
                                @else
                                <span class="badge bg-info text-dark">Cuenta: {{ $ingreso->cuentaBancaria->nombre_banco ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="fw-bold text-success">S/ {{ number_format($ingreso->monto, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($ingreso->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $ingreso->usuario->nombres }} {{ $ingreso->usuario->apellidos }}</td>
                            <td>
                                <span class="badge {{ $ingreso->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $ingreso->estado ? 'Registrado' : 'Anulado' }}
                                </span>
                            </td>
                            <td>
                                @if($ingreso->estado)
                                <button class="btn btn-sm btn-danger btn-round btn-anular-ingreso-eco"
                                    data-url="{{ route('admin.ingresos-economicos.anular', $ingreso->id_ingreso_economico) }}">
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

@include('admin.ingresos-economicos.modals.create')

@push('scripts')
<script>
    $(document).ready(function() {

        /* ============ CARGAR CAJAS POR TIENDA ============ */
        var CAJAS = @json($cajasAbiertas);

        function cargarCajasIngresoEco() {
            var idTienda = $('#tiendaIngresoEco').val();
            var select = $('#cajaIngresoEco');
            select.html('<option value="">Sin caja</option>');
            if (!idTienda) return;
            CAJAS.forEach(function(c) {
                if (c.id_tienda == idTienda) {
                    select.append('<option value="' + c.id_caja + '">' + c.nombre + '</option>');
                }
            });
        }

        $('#tiendaIngresoEco').on('change', cargarCajasIngresoEco);

        /* ============ SUBMIT ============ */
        $('#formIngresoEco').on('submit', function() {
            $('#btnGuardarIngresoEco').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
        });

        /* ============ ANULAR ============ */
        $(document).on('click', '.btn-anular-ingreso-eco', function() {
            var url = $(this).data('url');

            Swal.fire({
                title: '¿Anular ingreso económico?',
                text: 'Se retirará el dinero del destino. Esta acción no se puede deshacer.',
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
                            Swal.fire({ icon: 'success', title: 'Anulado', timer: 1200, showConfirmButton: false });
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
