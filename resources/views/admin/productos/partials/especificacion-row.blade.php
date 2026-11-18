<div class="row g-2 align-items-end mb-3" data-product-specification-row>
    <div class="col-md-4">
        <label class="form-label small">Nombre</label>
        <input type="text"
            name="especificaciones[{{ $index }}][nombre]"
            value="{{ $especificacion->nombre ?? '' }}"
            class="form-control"
            maxlength="150"
            placeholder="Ej: Material">
    </div>

    <div class="col-md-6">
        <label class="form-label small">Valor</label>
        <input type="text"
            name="especificaciones[{{ $index }}][valor]"
            value="{{ $especificacion->valor ?? '' }}"
            class="form-control"
            maxlength="2000"
            placeholder="Ej: Acero inoxidable">
    </div>

    <div class="col-md-2 d-grid">
        <button type="button"
            class="btn btn-outline-danger"
            data-remove-product-specification
            aria-label="Eliminar especificación">
            <i class="fa fa-trash"></i>
        </button>
    </div>
</div>
