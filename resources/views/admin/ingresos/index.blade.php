@extends('admin.layouts.app')

@section('title', 'Ingresos de Stock')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Ingresos</h4>
        </div>

        <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreate">
            <i class="fa fa-plus"></i> Nuevo Ingreso
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
                            <th>Tipo</th>
                            <th>Tienda</th>
                            <th>Proveedor</th>
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
                                @if($ingreso->tipo === 'compra')
                                <span class="badge bg-primary">Compra</span>
                                @else
                                <span class="badge bg-info text-dark">Ajuste</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $ingreso->tienda->nombre }}</td>
                            <td>{{ $ingreso->proveedor->nombre ?? '—' }}</td>
                            <td>{{ $ingreso->fecha->format('d/m/Y') }}</td>
                            <td>{{ $ingreso->usuario->nombres }} {{ $ingreso->usuario->apellidos }}</td>
                            <td>
                                <span class="badge {{ $ingreso->estado ? 'bg-success' : 'bg-danger' }}">
                                    {{ $ingreso->estado ? 'Registrado' : 'Anulado' }}
                                </span>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-info btn-border btn-round btn-ver-detalle"
                                    data-url="{{ route('admin.ingresos.detalle', $ingreso->id_ingreso) }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                @if($ingreso->estado)
                                <button class="btn btn-sm btn-danger btn-round btn-anular"
                                    data-url="{{ route('admin.ingresos.anular', $ingreso->id_ingreso) }}">
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

@include('admin.ingresos.modals.create')
@include('admin.ingresos.modals.detalle_modal')

@push('scripts')
<script>
    var VARIANTES = @json($variantes);

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    /* ============ BÚSQUEDA DE VARIANTE ============ */
    $('#varianteInputIngreso').on('input', function() {
        var t = $(this).val().trim().toLowerCase();
        var lista = $('#varianteResultadosIngreso');

        if (!t) {
            lista.addClass('d-none').html('');
            return;
        }

        var matches = VARIANTES.filter(function(v) {
            return (v.producto || '').toLowerCase().indexOf(t) > -1 ||
                (v.sku || '').toLowerCase().indexOf(t) > -1 ||
                (v.atributos || '').toLowerCase().indexOf(t) > -1;
        });

        if (!matches.length) {
            lista.html('<div class="ingreso-resultado py-2 text-muted">Sin resultados</div>');
        } else {
            lista.html(matches.slice(0, 12).map(function(v) {
                var attrLine = v.atributos
                    ? '<div class="small text-primary">' + escapeHtml(v.atributos) + '</div>'
                    : '';
                return '<div class="ingreso-resultado" data-id="' + v.id + '">' +
                    '<div class="fw-semibold">' + escapeHtml(v.producto) + '</div>' +
                    attrLine +
                    '<div class="small text-muted">' + escapeHtml(v.sku || 'Sin SKU') + ' · Costo: S/ ' +
                    parseFloat(v.costo || 0).toFixed(2) + '</div>' +
                    '</div>';
            }).join(''));
        }

        lista.removeClass('d-none');
    });

    $(document).on('mousedown', '#varianteResultadosIngreso .ingreso-resultado', function(e) {
        e.preventDefault();

        var id = parseInt($(this).data('id'));
        var v = VARIANTES.find(function(x) { return x.id === id; });

        if (!v) return;

        agregarFilaIngreso(v);

        $('#varianteInputIngreso').val('');
        $('#varianteResultadosIngreso').addClass('d-none').html('');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ingreso-buscador').length) {
            $('#varianteResultadosIngreso').addClass('d-none');
        }
    });

    /* ============ FILAS DEL DETALLE ============ */
    function agregarFilaIngreso(v, cantidad, costo) {
        var filas = $('#ingresoFilas');

        if (filas.find('input[name="items[' + v.id + '][id_variante]"]').length) {
            Swal.fire('Atención', 'El producto ya está en el detalle', 'warning');
            return;
        }

        var attrHtml = v.atributos
            ? '<br><span class="small text-primary">' + escapeHtml(v.atributos) + '</span>'
            : '';

        var fila = $(`
            <tr data-variante="${v.id}">
                <td class="fw-semibold">${escapeHtml(v.producto)}${attrHtml}<br>
                    <span class="small text-muted">${escapeHtml(v.sku || 'Sin SKU')}</span>
                </td>
                <td style="width:120px">
                    <input type="number" class="form-control form-control-sm ingreso-cantidad" value="${cantidad || 1}"
                        min="1" required>
                    <input type="hidden" name="items[${v.id}][id_variante]" value="${v.id}">
                </td>
                <td style="width:130px">
                    <input type="number" class="form-control form-control-sm ingreso-costo" value="${costo != null ? costo : v.costo}"
                        min="0" step="0.01" required>
                    <input type="hidden" name="items[${v.id}][cantidad]" value="${cantidad || 1}">
                    <input type="hidden" name="items[${v.id}][costo]" value="${costo != null ? costo : v.costo}">
                </td>
                <td class="text-end ingreso-subtotal fw-bold">S/ 0.00</td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-border btn-round btn-quitar-ingreso">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);

        filas.append(fila);
        actualizarFilaIngreso(fila);
    }

    $(document).on('click', '.btn-quitar-ingreso', function() {
        $(this).closest('tr').remove();
        actualizarTotalIngreso();
    });

    $(document).on('input', '.ingreso-cantidad, .ingreso-costo', function() {
        var fila = $(this).closest('tr');
        var nombre = $(this).hasClass('ingreso-cantidad') ? 'cantidad' : 'costo';
        fila.find('input[name^="items["][name$="[' + nombre + ']"]').val($(this).val());
        actualizarFilaIngreso(fila);
    });

    function actualizarFilaIngreso(fila) {
        var cant = parseFloat(fila.find('.ingreso-cantidad').val()) || 0;
        var costo = parseFloat(fila.find('.ingreso-costo').val()) || 0;
        fila.find('.ingreso-subtotal').text('S/ ' + (cant * costo).toFixed(2));
        actualizarTotalIngreso();
    }

    function actualizarTotalIngreso() {
        var total = 0;
        $('#ingresoFilas tr').each(function() {
            var cant = parseFloat($(this).find('.ingreso-cantidad').val()) || 0;
            var costo = parseFloat($(this).find('.ingreso-costo').val()) || 0;
            total += cant * costo;
        });
        $('#ingresoTotal').text('S/ ' + total.toFixed(2));
    }

    /* ============ TIPO (compra / ajuste) ============ */
    function cambiarTipoIngreso() {
        var tipo = $('#tipoIngreso').val();

        if (tipo === 'compra') {
            $('#proveedorWrapIngreso').removeClass('d-none');
            $('#proveedorIngreso').prop('required', true);
        } else {
            $('#proveedorWrapIngreso').addClass('d-none');
            $('#proveedorIngreso').prop('required', false).val('');
        }
    }

    $('#tipoIngreso').on('change', cambiarTipoIngreso);

    /* ============ SUBMIT ============ */
    $('#formIngreso').on('submit', function(e) {
        var filas = $('#ingresoFilas tr').length;

        if (!filas) {
            e.preventDefault();
            Swal.fire('Atención', 'Agrega al menos un producto al detalle', 'warning');
            return;
        }

        $('#btnGuardarIngreso').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando...');
    });

    /* ============ VER DETALLE ============ */
    $(document).on('click', '.btn-ver-detalle', function() {
        var url = $(this).data('url');

        $.get(url, function(html) {
            $('#modalDetalleIngreso .modal-content').html(html);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalDetalleIngreso')).show();
        });
    });

    /* ============ ANULAR ============ */
    $(document).on('click', '.btn-anular', function() {
        var url = $(this).data('url');

        Swal.fire({
            title: '¿Anular ingreso?',
            text: 'Se restará el stock ingresado. Esta acción no se puede deshacer.',
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

    cambiarTipoIngreso();
</script>
@endpush

<style>
    .ingreso-resultados {
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

    html[data-theme="dark"] .ingreso-resultados {
        background: var(--ka-surface, #273243);
        border-color: var(--ka-border, #3a4658);
    }

    .ingreso-resultado {
        padding: .55rem .9rem;
        cursor: pointer;
        border-bottom: 1px dashed rgba(0, 0, 0, .06);
    }

    .ingreso-resultado:last-child {
        border-bottom: 0;
    }

    .ingreso-resultado:hover {
        background: rgba(0, 0, 0, .04);
    }

    html[data-theme="dark"] .ingreso-resultado:hover {
        background: rgba(255, 255, 255, .06);
    }
</style>

@endsection
