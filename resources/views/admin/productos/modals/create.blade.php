<div class="modal fade" id="modalCreate">
    <div class="modal-dialog modal-xl">

        <form action="{{ route('admin.productos.store') }}" method="POST">
            @csrf

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body pt-3">

                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-lg-8">

                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">

                                    <h6 class="fw-bold mb-3">
                                        Información general
                                    </h6>

                                    <!-- NOMBRE + MARCA -->
                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">
                                                Nombre *
                                            </label>

                                            <input type="text" name="nombre" class="form-control"
                                                placeholder="Ej: Zapatilla Nike Air" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">
                                                Marca
                                            </label>

                                            <select name="id_marca" class="form-select">
                                                <option value="">
                                                    Seleccionar marca
                                                </option>

                                                @foreach($marcas as $m)
                                                <option value="{{ $m->id_marca }}">
                                                    {{ $m->nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <!-- PROVEEDOR + PESO -->
                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">
                                                Proveedor
                                            </label>

                                            <select name="id_proveedor" class="form-select">

                                                <option value="">
                                                    Seleccionar proveedor
                                                </option>

                                                @foreach($proveedores as $pr)
                                                <option value="{{ $pr->id_proveedor }}">
                                                    {{ $pr->nombre }}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">
                                                Peso
                                            </label>

                                            <input type="text" name="peso" class="form-control"
                                                placeholder="Ej: 1.2 kg">
                                        </div>

                                    </div>

                                    <!-- DIMENSIONES -->
                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Dimensiones
                                        </label>

                                        <input type="text" name="dimensiones" class="form-control"
                                            placeholder="Ej: 30x20x10 cm">

                                    </div>

                                    <!-- CATEGORÍAS -->
                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Categorías
                                        </label>

                                        <select name="categorias[]" class="form-select" multiple size="8">

                                            @foreach($categorias as $c)
                                            <option value="{{ $c->id_categoria }}" class="fw-bold">
                                                {{ $c->nombre }}
                                            </option>
                                            @if($c->hijos && $c->hijos->count())
                                            @foreach($c->hijos as $hijo)
                                            <option value="{{ $hijo->id_categoria }}">
                                                ↳ {{ $hijo->nombre }}
                                            </option>
                                            @endforeach
                                            @endif
                                            @endforeach

                                        </select>

                                    </div>

                                    <!-- DESCRIPCIONES -->
                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Descripción corta
                                        </label>
                                        <textarea name="descripcion_corta" class="form-control editor" rows="2"></textarea>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label small">
                                            Descripción
                                        </label>
                                            <textarea name="descripcion" class="form-control editor" rows="4"></textarea>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-lg-4">

                            <!-- VARIANTE -->
                            <div class="card border-0 shadow-sm mb-3">

                                <div class="card-body">

                                    <h6 class="fw-bold mb-3">
                                        Variante principal
                                    </h6>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            SKU *
                                        </label>

                                        <input type="text" name="sku_inicial" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Código de barras
                                        </label>

                                        <input type="text" name="codigo_barras" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Precio *
                                        </label>

                                        <input type="number" step="0.01" name="precio_inicial" class="form-control"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Precio oferta
                                        </label>

                                        <input type="number" step="0.01" name="precio_oferta_inicial"
                                            class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Costo
                                        </label>

                                        <input type="number" step="0.01" name="costo_inicial" class="form-control">
                                    </div>

                                </div>

                            </div>

                            <!-- ESTADO -->
                            <div class="card border-0 shadow-sm">

                                <div class="card-body">

                                    <h6 class="fw-bold mb-3">
                                        Configuración
                                    </h6>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Destacado
                                        </label>

                                        <select name="destacado" class="form-select">
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Nuevo
                                        </label>

                                        <select name="nuevo" class="form-select">
                                            <option value="0">No</option>
                                            <option value="1">Sí</option>
                                        </select>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label small">
                                            Estado
                                        </label>

                                        <select name="estado" class="form-select">
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

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