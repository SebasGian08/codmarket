<div class="modal fade" id="modalEdit{{ $user->id_usuario }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.users.update', $user->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div
                                class="form-group {{ $errors->has('nombres') ? 'has-error' : (old('nombres') ? 'has-success' : '') }}">
                                <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres" class="form-control"
                                    value="{{ old('nombres', $user->nombres) }}" required>
                                @error('nombres')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div
                                class="form-group {{ $errors->has('apellidos') ? 'has-error' : (old('apellidos') ? 'has-success' : '') }}">
                                <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="apellidos" class="form-control"
                                    value="{{ old('apellidos', $user->apellidos) }}" required>
                                @error('apellidos')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group {{ $errors->has('id_rol') ? 'has-error' : '' }}">
                                <label>Rol <span class="text-danger">*</span></label>
                                <select name="id_rol" class="form-control" required>
                                    @foreach($roles as $rol)
                                    <option value="{{ $rol->id_rol }}"
                                        {{ old('id_rol', $user->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                                        {{ $rol->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div
                                class="form-group {{ $errors->has('email') ? 'has-error' : (old('email') ? 'has-success' : '') }}">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div
                                class="form-group {{ $errors->has('password') ? 'has-error' : (old('password') ? 'has-success' : '') }} has-feedback">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i
                                                class="fa fa-lock {{ $errors->has('password') ? 'text-danger' : '' }}"></i>
                                        </span>
                                    </div>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Nueva contraseña...">
                                </div>

                                @error('password')
                                <small class="form-text text-danger">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </small>
                                @else
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Opcional: Dejar en blanco para mantener la
                                    actual.
                                </small>
                                @enderror
                            </div>

                            <div
                                class="form-group {{ $errors->has('telefono') ? 'has-error' : (old('telefono') ? 'has-success' : '') }}">
                                <label>Teléfono</label>
                                <input type="text" name="telefono" class="form-control"
                                    value="{{ old('telefono', $user->telefono) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>