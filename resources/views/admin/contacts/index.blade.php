@extends('admin.layouts.app')

@section('title', 'Contactos')

@section('content')

<div class="page-inner">

    <div class="page-header d-flex align-items-center justify-content-between">

        <div class="d-flex align-items-center">
            <h4 class="page-title">Gestión de Contactos</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    <a>Contactos</a>
                </li>
            </ul>
        </div>

        <div>
            <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#modalCreateContact">
                <i class="fa fa-plus"></i> Nuevo contacto
            </button>
        </div>

    </div>

    <div class="row g-3 mt-2">

        @foreach($statuses as $status)

        <div class="col-12 col-md-6 col-lg-3">

            <div class="card border-0 shadow-sm h-100 d-flex flex-column">

                <!-- HEADER -->
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong class="text-truncate">{{ $status->nombre }}</strong>

                    <span class="badge bg-dark rounded-pill">
                        {{ count($contacts[$status->id_status] ?? []) }}
                    </span>
                </div>

                <!-- BODY -->
                <div class="card-body bg-light p-2 flex-grow-1 overflow-auto" style="max-height: 70vh;">

                    @forelse($contacts[$status->id_status] ?? [] as $contact)

                    <div class="card border-0 shadow-sm mb-2">

                        <div class="card-body p-2 d-flex flex-column">

                            <!-- INFO -->
                            <div class="fw-semibold text-dark text-truncate">
                                {{ $contact->nombres }} {{ $contact->apellidos }}
                            </div>

                            <div class="text-muted small text-truncate">
                                {{ $contact->email }}
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">
                                    {{ $contact->created_at->format('d/m/Y') }}
                                </small>

                                <span class="badge bg-warning text-dark">
                                    {{ $contact->priority->nombre ?? 'Normal' }}
                                </span>
                            </div>

                            <!-- BOTONES RESPONSIVE -->
                            <div class="mt-2">

                                <div class="d-flex flex-wrap gap-1">

                                    <button class="btn btn-sm btn-outline-primary flex-fill" data-bs-toggle="modal"
                                        data-bs-target="#view{{ $contact->id_contact }}">
                                        Ver
                                    </button>

                                    <button class="btn btn-sm btn-outline-success flex-fill" data-bs-toggle="modal"
                                        data-bs-target="#seguimiento{{ $contact->id_contact }}">
                                        Seguimiento
                                    </button>

                                    <button class="btn btn-sm btn-outline-secondary flex-fill" data-bs-toggle="modal"
                                        data-bs-target="#status{{ $contact->id_contact }}">
                                        Estado
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger flex-fill" data-bs-toggle="modal"
                                        data-bs-target="#lost{{ $contact->id_contact }}">
                                        Perdido
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                    @include('admin.contacts.modals.view')
                    @include('admin.contacts.modals.seguimiento')
                    @include('admin.contacts.modals.status')
                    @include('admin.contacts.modals.lost')

                    @empty

                    <div class="text-center text-muted small py-3">
                        Sin contactos
                    </div>

                    @endforelse

                </div>

            </div>

        </div>

        @endforeach

    </div>

</div>

@include('admin.contacts.modals.create')

@endsection