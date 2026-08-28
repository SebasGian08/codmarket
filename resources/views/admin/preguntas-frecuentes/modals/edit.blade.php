<div class="modal fade" id="edit{{ $pf->id }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.preguntas.update', $pf->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Pregunta Frecuente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row">

                    <div class="col-md-8">
                        <label>Pregunta</label>
                        <input type="text" name="pregunta" value="{{ $pf->pregunta }}" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Orden</label>
                        <input type="number" name="orden" value="{{ $pf->orden }}" class="form-control">
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>Respuesta</label>
                        <textarea name="respuesta" class="form-control editor" rows="4" required>{!! $pf->respuesta !!}</textarea>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>Estado</label>
                        <select name="estado" class="form-control">
                            <option value="1" {{ $pf->estado == 1 ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ $pf->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                        </select>
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

            </div>

        </form>

    </div>
</div>
