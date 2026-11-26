@extends('admin.layouts.app')

@section('title', 'Descuentos')

@section('content')

<div class="page-inner">
    @include('admin.partials.page_heading', [
        'icon' => 'fas fa-percent',
        'title' => 'Descuentos',
        'description' => 'Administra tipos de venta, motivos y reglas de descuento desde un solo módulo.',
    ])

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

    <div class="card admin-module-tabs-card">
        <div class="card-header">
            <ul class="nav nav-tabs admin-module-tabs descuentos-tabs" role="tablist">
                @if($puedeVerTiposVenta)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'tipos-venta' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#descuentos-tipos-venta" role="tab"
                        data-tab-value="tipos-venta"
                        aria-controls="descuentos-tipos-venta"
                        aria-selected="{{ $pestanaActiva === 'tipos-venta' ? 'true' : 'false' }}">
                        <i class="fas fa-receipt" aria-hidden="true"></i>
                        <span>Tipos de Venta</span>
                    </a>
                </li>
                @endif

                @if($puedeVerMotivos)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'motivos-descuento' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#descuentos-motivos" role="tab"
                        data-tab-value="motivos-descuento"
                        aria-controls="descuentos-motivos"
                        aria-selected="{{ $pestanaActiva === 'motivos-descuento' ? 'true' : 'false' }}">
                        <i class="fas fa-percent" aria-hidden="true"></i>
                        <span>Motivos de Descuento</span>
                    </a>
                </li>
                @endif

                @if($puedeVerReglas)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'reglas-descuento' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#descuentos-reglas" role="tab"
                        data-tab-value="reglas-descuento"
                        aria-controls="descuentos-reglas"
                        aria-selected="{{ $pestanaActiva === 'reglas-descuento' ? 'true' : 'false' }}">
                        <i class="fas fa-sliders-h" aria-hidden="true"></i>
                        <span>Reglas de Descuento</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content">
                @if($puedeVerTiposVenta)
                <div class="tab-pane fade {{ $pestanaActiva === 'tipos-venta' ? 'show active' : '' }}"
                    id="descuentos-tipos-venta" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                            data-bs-target="#modalCreateTipoVenta">
                            <i class="fa fa-plus"></i> Nuevo Tipo de Venta
                        </button>
                    </div>
                    @include('admin.tipos-venta.partials.table', ['dataTableId' => 'tabla-tipos-venta'])
                </div>
                @endif

                @if($puedeVerMotivos)
                <div class="tab-pane fade {{ $pestanaActiva === 'motivos-descuento' ? 'show active' : '' }}"
                    id="descuentos-motivos" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                            data-bs-target="#modalCreateMotivoDescuento">
                            <i class="fa fa-plus"></i> Nuevo Motivo de Descuento
                        </button>
                    </div>
                    @include('admin.motivos-descuento.partials.table', ['dataTableId' => 'tabla-motivos-descuento'])
                </div>
                @endif

                @if($puedeVerReglas)
                <div class="tab-pane fade {{ $pestanaActiva === 'reglas-descuento' ? 'show active' : '' }}"
                    id="descuentos-reglas" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                            data-bs-target="#modalCreateRegla">
                            <i class="fa fa-plus"></i> Nueva Regla de Descuento
                        </button>
                    </div>
                    @include('admin.reglas-descuento.partials.table', ['dataTableId' => 'tabla-reglas-descuento'])
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('shown.bs.tab', '.descuentos-tabs [data-bs-toggle="tab"]', function(event) {
        var target = $(event.target).attr('href');
        var activeTab = $(event.target).data('tab-value');
        var currentUrl = new URL(window.location.href);

        currentUrl.searchParams.set('tab', activeTab);
        window.history.replaceState({}, '', currentUrl.toString());

        $(target).find('.admin-data-table').each(function() {
            if ($.fn.DataTable.isDataTable(this)) {
                $(this).DataTable().columns.adjust();
            }
        });
    });
</script>
@endpush

@endsection
