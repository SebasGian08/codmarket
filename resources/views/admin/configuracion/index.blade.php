@extends('admin.layouts.app')

@section('title', 'Configuraciones')

@section('content')

<div class="page-inner">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">Configuraciones</h3>
            <small class="text-muted">Gestiona los ajustes del sistema</small>
        </div>

        <button class="btn btn-primary btn-round shadow-sm" data-bs-toggle="modal" data-bs-target="#modalConfig">

            <i class="fa fa-plus"></i> Nueva Configuración
        </button>

    </div>

    {{-- BUSCADOR --}}
    <div class="card mb-3">
        <div class="card-body">

            <input type="text" id="searchConfig" class="form-control" placeholder="Buscar configuración...">

        </div>
    </div>

    {{-- TABS --}}
    <div class="card">

        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">

                @foreach($configs as $categoria => $items)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                        href="#tab-{{ $categoria }}">
                        {{ ucfirst($categoria) }}
                    </a>
                </li>
                @endforeach

            </ul>
        </div>

        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin.config.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="tab-content">

                    @foreach($configs as $categoria => $items)

                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $categoria }}">

                        <div class="row g-3">

                            @foreach($items as $config)

                            <div class="col-md-3 config-item">

                                <div class="card border-0 shadow-sm rounded-4 h-100 config-card hover-shadow">

                                    <div class="card-body">

                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <small class="text-muted">{{ strtoupper($categoria) }}</small>

                                            <span class="badge bg-light text-dark">
                                                {{ $config->tipo }}
                                            </span>
                                        </div>

                                        <label class="fw-semibold mb-2 d-block">
                                            {{ $config->descripcion }}
                                        </label>

                                        {{-- COLOR --}}
                                        @if($config->tipo == 'color')

                                        <div class="d-flex align-items-center gap-2">

                                            <input type="color" name="{{ $config->clave }}" value="{{ $config->valor }}"
                                                class="form-control form-control-color color-input">

                                            <div class="color-preview rounded-3 border"
                                                style="width:40px;height:40px;background:{{ $config->valor }};">
                                            </div>

                                            <input type="text" class="form-control form-control-sm"
                                                value="{{ $config->valor }}" readonly>
                                        </div>

                                        {{-- TEXTAREA --}}
                                        @elseif($config->tipo == 'textarea')

                                        <textarea name="{{ $config->clave }}" class="form-control rounded-3"
                                            rows="3">{{ $config->valor }}</textarea>

                                        {{-- BOOLEAN --}}
                                        @elseif($config->tipo == 'boolean')

                                        <select name="{{ $config->clave }}" class="form-select rounded-3">
                                            <option value="1" {{ $config->valor == 1 ? 'selected' : '' }}>Sí</option>
                                            <option value="0" {{ $config->valor == 0 ? 'selected' : '' }}>No</option>
                                        </select>

                                        {{-- SELECT --}}
                                        @elseif($config->tipo == 'select')

                                        <select name="{{ $config->clave }}" class="form-select rounded-3">
                                            @foreach(explode(',', $config->opciones ?? '') as $opcion)
                                            <option value="{{ $opcion }}"
                                                {{ $config->valor == $opcion ? 'selected' : '' }}>
                                                {{ ucfirst($opcion) }}
                                            </option>
                                            @endforeach
                                        </select>

                                        {{-- IMAGE --}}
                                        @elseif($config->tipo == 'image')

                                        <input type="file" name="{{ $config->clave }}" class="form-control rounded-3">

                                        @if($config->valor)
                                        <img src="{{ asset($config->valor) }}" class="img-fluid rounded mt-2"
                                            style="max-height: 90px;">
                                        @endif

                                        {{-- NUMBER --}}
                                        @elseif($config->tipo == 'number')

                                        <input type="number" name="{{ $config->clave }}" value="{{ $config->valor }}"
                                            class="form-control rounded-3">

                                        {{-- DEFAULT --}}
                                        @else

                                        <input type="text" name="{{ $config->clave }}" value="{{ $config->valor }}"
                                            class="form-control rounded-3">

                                        @endif

                                    </div>
                                </div>

                            </div>

                            @endforeach

                        </div>

                    </div>

                    @endforeach

                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-success btn-lg px-5 rounded-3 shadow-sm">
                        <i class="fa fa-save me-1"></i> Guardar cambios
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>


{{-- FILTRO JS --}}
<script>
document.getElementById('searchConfig').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll('.config-item').forEach(el => {
        el.style.display = el.innerText.toLowerCase().includes(value) ? '' : 'none';
    });
});
</script>

@include('admin.configuracion.modals.create')

@endsection