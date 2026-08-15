<div class="modal fade" id="edit{{ $v->id_variante }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.variantes.update', $v->id_variante) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Variante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- IDENTIFICACIÓN --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label small">
                                SKU *
                            </label>

                            <input type="text"
                                   name="sku"
                                   value="{{ $v->sku }}"
                                   class="form-control"
                                   placeholder="Ej: ZAP-NEG-42"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label small">
                                Código de barras
                            </label>

                            <input type="text"
                                   name="codigo_barras"
                                   value="{{ $v->codigo_barras }}"
                                   class="form-control"
                                   placeholder="Ej: 775412547845">
                        </div>

                    </div>

                    {{-- PRECIOS --}}
                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <label class="form-label small">
                                Precio *
                            </label>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="precio"
                                   value="{{ $v->precio }}"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label small">
                                Precio oferta
                            </label>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="precio_oferta"
                                   value="{{ $v->precio_oferta }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label small">
                                Costo
                            </label>

                            <input type="number"
                                   step="0.01"
                                   min="0"
                                   name="costo"
                                   value="{{ $v->costo }}"
                                   class="form-control">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label class="form-label small">
                                Stock *
                            </label>

                            <input type="number"
                                   min="0"
                                   name="stock"
                                   value="{{ $v->stock }}"
                                   class="form-control"
                                   required>
                            <small class="text-muted">Al guardar genera un movimiento interno</small>
                        </div>

                    </div>

                    {{-- ESTADO --}}
                    <div class="mb-4">
                        <label class="form-label small">
                            Estado
                        </label>

                        <select name="estado" class="form-select">
                            <option value="1" {{ $v->estado ? 'selected' : '' }}>
                                Activo
                            </option>

                            <option value="0" {{ !$v->estado ? 'selected' : '' }}>
                                Inactivo
                            </option>
                        </select>
                    </div>

                    <hr>

                    {{-- ATRIBUTOS --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Atributos de la variante
                        </label>

                        <small class="text-muted d-block">
                            Selecciona color, talla u otras características
                        </small>
                    </div>

                    <div class="row">

                        @foreach($valores as $val)

                        <div class="col-md-4 mb-2">

                            <label class="attribute-check">

                                <input type="checkbox"
                                       name="valores[]"
                                       value="{{ $val->id_valor }}"
                                       {{ $v->atributos->contains('id_valor', $val->id_valor) ? 'checked' : '' }}>

                                <div class="attribute-box">

                                    <small class="d-block text-muted">
                                        {{ $val->atributo->nombre }}
                                    </small>

                                    <strong>
                                        {{ $val->valor }}
                                    </strong>

                                </div>

                            </label>

                        </div>

                        @endforeach

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

<style>
.attribute-check {
    width: 100%;
    cursor: pointer;
}

.attribute-check input {
    display: none;
}

.attribute-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px;
    transition: .2s;
    background: #fff;
}

.attribute-check input:checked + .attribute-box {
    border-color: #198754;
    background: rgba(25,135,84,.08);
}
</style>