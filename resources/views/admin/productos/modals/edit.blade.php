<div class="modal fade" id="edit{{ $p->id_producto }}">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('admin.productos.update', $p->id_producto) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-content">

                <div class="modal-header">
                    <h5>Editar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body pt-3">

                    @php
                    $primeraVariante = $p->variantes->first();
                    @endphp

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

                                            <input type="text" name="nombre" value="{{ $p->nombre }}"
                                                class="form-control" placeholder="Nombre del producto" required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label small">
                                                Marca
                                            </label>

                                            <select name="id_marca" class="form-select">

                                                @foreach($marcas as $m)
                                                <option value="{{ $m->id_marca }}"
                                                    {{ $p->id_marca == $m->id_marca ? 'selected' : '' }}>

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

                                                @foreach($proveedores as $pr)
                                                <option value="{{ $pr->id_proveedor }}"
                                                    {{ $p->id_proveedor == $pr->id_proveedor ? 'selected' : '' }}>

                                                    {{ $pr->nombre }}

                                                </option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label small">
                                                Peso
                                            </label>

                                            <input type="text" name="peso" value="{{ $p->peso }}" class="form-control"
                                                placeholder="Ej: 1.2 kg">

                                        </div>

                                    </div>

                                    <!-- DIMENSIONES -->
                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Dimensiones
                                        </label>

                                        <input type="text" name="dimensiones" value="{{ $p->dimensiones }}"
                                            class="form-control" placeholder="Ej: 30x20x10 cm">

                                    </div>

                                    <!-- CATEGORÍAS -->
                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Categorías
                                        </label>

                                        <select name="categorias[]" class="form-select" multiple>

                                            @foreach($categorias as $c)

                                            <option value="{{ $c->id_categoria }}"
                                                {{ $p->categorias->contains($c->id_categoria) ? 'selected' : '' }}>

                                                {{ $c->nombre }}

                                            </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <!-- DESCRIPCIONES -->
                                    <div class="mb-3">
                                        <label class="form-label small">
                                            Descripción corta
                                        </label>
                                        <textarea name="descripcion_corta" class="form-control editor" rows="2">{{ $p->descripcion_corta }}</textarea>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label small">
                                            Descripción
                                        </label>
                                        <textarea name="descripcion" class="form-control editor" rows="4">{!! $p->descripcion !!}</textarea>
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

                                        <input type="text" name="sku_principal" class="form-control"
                                            value="{{ $primeraVariante->sku ?? '' }}" required>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Código de barras
                                        </label>

                                        <input type="text" name="codigo_barras" class="form-control"
                                            value="{{ $primeraVariante->codigo_barras ?? '' }}">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Precio *
                                        </label>

                                        <input type="number" step="0.01" name="precio_principal" class="form-control"
                                            value="{{ $primeraVariante->precio ?? 0 }}" required>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Precio oferta
                                        </label>

                                        <input type="number" step="0.01" name="precio_oferta_principal"
                                            class="form-control" value="{{ $primeraVariante->precio_oferta ?? '' }}">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Costo
                                        </label>

                                        <input type="number" step="0.01" name="costo_principal" class="form-control"
                                            value="{{ $primeraVariante->costo ?? '' }}">

                                    </div>

                                    <div class="mb-0">

                                        <label class="form-label small">
                                            Stock
                                        </label>

                                        <input type="number" name="stock_principal" class="form-control"
                                            value="{{ $primeraVariante->stock ?? 0 }}">

                                    </div>

                                </div>

                            </div>

                            <!-- CONFIG -->
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

                                            <option value="0" {{ $p->destacado == 0 ? 'selected' : '' }}>
                                                No
                                            </option>

                                            <option value="1" {{ $p->destacado == 1 ? 'selected' : '' }}>
                                                Sí
                                            </option>

                                        </select>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label small">
                                            Nuevo
                                        </label>

                                        <select name="nuevo" class="form-select">

                                            <option value="0" {{ $p->nuevo == 0 ? 'selected' : '' }}>
                                                No
                                            </option>

                                            <option value="1" {{ $p->nuevo == 1 ? 'selected' : '' }}>
                                                Sí
                                            </option>

                                        </select>

                                    </div>

                                    <div class="mb-0">

                                        <label class="form-label small">
                                            Estado
                                        </label>

                                        <select name="estado" class="form-select">

                                            <option value="1" {{ $p->estado == 1 ? 'selected' : '' }}>
                                                Activo
                                            </option>

                                            <option value="0" {{ $p->estado == 0 ? 'selected' : '' }}>
                                                Inactivo
                                            </option>

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
                    <button type="submit" class="btn btn-warning"><i class="fa fa-edit"></i> Actualizar</button>
                </div>
            </div>

        </form>

    </div>
</div>