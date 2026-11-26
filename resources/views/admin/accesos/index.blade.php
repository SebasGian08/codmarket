@extends('admin.layouts.app')

@section('title', 'Usuarios y Accesos')

@section('content')

<div class="page-inner">
    @include('admin.partials.page_heading', [
        'icon' => 'fas fa-user-lock',
        'title' => 'Usuarios y Accesos',
        'description' => 'Administra usuarios, roles y permisos desde un solo módulo.',
    ])

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('delete'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('delete') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Revisa los datos ingresados:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card admin-module-tabs-card">
        <div class="card-header py-4">
            <ul class="nav nav-tabs admin-module-tabs accesos-tabs" role="tablist">
                @if($puedeVerUsuarios)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'usuarios' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#accesos-usuarios" role="tab"
                        data-tab-value="usuarios" aria-controls="accesos-usuarios"
                        aria-selected="{{ $pestanaActiva === 'usuarios' ? 'true' : 'false' }}">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                @endif

                @if($puedeVerRoles)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'roles' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#accesos-roles" role="tab"
                        data-tab-value="roles" aria-controls="accesos-roles"
                        aria-selected="{{ $pestanaActiva === 'roles' ? 'true' : 'false' }}">
                        <i class="fas fa-user-shield" aria-hidden="true"></i>
                        <span>Roles</span>
                    </a>
                </li>
                @endif

                @if($puedeVerPermisos)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'permisos' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#accesos-permisos" role="tab"
                        data-tab-value="permisos" aria-controls="accesos-permisos"
                        aria-selected="{{ $pestanaActiva === 'permisos' ? 'true' : 'false' }}">
                        <i class="fas fa-key" aria-hidden="true"></i>
                        <span>Permisos</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content">
                @if($puedeVerUsuarios)
                <div class="tab-pane fade {{ $pestanaActiva === 'usuarios' ? 'show active' : '' }}"
                    id="accesos-usuarios" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                            data-bs-target="#modalCreateUsuario">
                            <i class="fa fa-plus"></i> Nuevo Usuario
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @include('admin.accesos.partials.usuarios')
                        </div>
                    </div>
                </div>
                @endif

                @if($puedeVerRoles)
                <div class="tab-pane fade {{ $pestanaActiva === 'roles' ? 'show active' : '' }}"
                    id="accesos-roles" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                            data-bs-target="#modalCreateRol">
                            <i class="fa fa-plus"></i> Nuevo Rol
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @include('admin.accesos.partials.roles')
                        </div>
                    </div>
                </div>
                @endif

                @if($puedeVerPermisos)
                <div class="tab-pane fade {{ $pestanaActiva === 'permisos' ? 'show active' : '' }}"
                    id="accesos-permisos" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary btn-round" data-bs-toggle="modal"
                            data-bs-target="#modalCreatePermiso">
                            <i class="fa fa-plus"></i> Nuevo Permiso
                        </button>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            @include('admin.accesos.partials.permisos')
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('shown.bs.tab', '.accesos-tabs [data-bs-toggle="tab"]', function(event) {
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
