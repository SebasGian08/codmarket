<div class="table-responsive">
    <table class="table table-striped table-hover admin-data-table" id="tabla-roles">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $rol)
            <tr>
                <td>{{ $rol->id_rol }}</td>
                <td>{{ $rol->nombre }}</td>
                <td>
                    <span class="badge {{ $rol->estado ? 'bg-success' : 'bg-danger' }}">
                        {{ $rol->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>{{ $rol->created_at ? $rol->created_at->format('d/m/Y') : '-' }}</td>
                <td>
                    <a href="{{ route('admin.roles.permisos', $rol->id_rol) }}"
                        class="btn btn-sm mt-2 btn-success btn-border btn-round"
                        aria-label="Asignar permisos al rol {{ $rol->nombre }}">
                        <i class="fa fa-key"></i>
                    </a>
                    <button class="btn btn-sm mt-2 btn-primary btn-border btn-round"
                        data-bs-toggle="modal" data-bs-target="#editRol{{ $rol->id_rol }}"
                        aria-label="Editar rol {{ $rol->nombre }}">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.roles.destroy', $rol->id_rol) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm mt-2 btn-danger btn-border btn-round btn-delete"
                            data-id="{{ $rol->id_rol }}" data-name="{{ $rol->nombre }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($roles as $rol)
    @include('admin.roles.modals.edit', ['rol' => $rol])
@endforeach

@include('admin.roles.modals.create')
