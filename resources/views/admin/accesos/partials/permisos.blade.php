<div class="table-responsive">
    <table class="table table-hover admin-data-table" id="tabla-permisos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permisos as $permiso)
            <tr>
                <td>{{ $permiso->id_permiso }}</td>
                <td>{{ $permiso->nombre }}</td>
                <td><span class="badge bg-info">{{ $permiso->codigo }}</span></td>
                <td>{{ $permiso->descripcion }}</td>
                <td>
                    <span class="badge {{ $permiso->estado ? 'bg-success' : 'bg-danger' }}">
                        {{ $permiso->estado ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary btn-round"
                        data-bs-toggle="modal" data-bs-target="#editPermiso{{ $permiso->id_permiso }}"
                        aria-label="Editar permiso {{ $permiso->nombre }}">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.permisos.destroy', $permiso->id_permiso) }}"
                        method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger btn-round btn-delete"
                            data-id="{{ $permiso->id_permiso }}" data-name="{{ $permiso->nombre }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@foreach($permisos as $permiso)
    @include('admin.permisos.modals.edit', ['permiso' => $permiso])
@endforeach

@include('admin.permisos.modals.create')
