<div class="modal fade" id="editFooterLogo{{ $logo->id }}" tabindex="-1">
    <div class="modal-dialog">

        <form method="POST" action="{{ route('admin.footer-logos.update', $logo->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-edit me-1"></i> Editar Logo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="text-center mb-2">
                        <img src="{{ asset($logo->imagen) }}" alt="{{ $logo->titulo ?? 'Logo' }}"
                            style="max-height: 80px; max-width: 100%;">
                    </div>

                    <label>Imagen (dejar vacío para conservar)</label>
                    <input type="file" name="imagen" class="form-control mb-2" accept="image/*">

                    <label>Título</label>
                    <input name="titulo" class="form-control mb-2" maxlength="150"
                        value="{{ $logo->titulo }}">

                    <label>Enlace (opcional)</label>
                    <input name="url" type="url" class="form-control mb-2"
                        value="{{ $logo->url }}">

                    <label>Orden</label>
                    <input name="orden" type="number" class="form-control mb-2"
                        value="{{ $logo->orden }}" min="0">

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> Guardar
                    </button>
                </div>

            </div>

        </form>

    </div>
</div>
