@php
$mostrarPrecio = $config['producto_mostrar_precio'] ?? 1;
$mostrarMarca = $config['producto_mostrar_marca'] ?? 1;
@endphp

<section class="bestseller_section sec_ptb_50 pb-0 clearfix">
    <div class="container maxw_1460">
        <div class="row mb_50 align-items-center justify-content-between">

            <!-- TÍTULO -->
            @if(($config['seccion_destacados_mostrar_encabezado'] ?? 1) == 1)
            <div class="col-lg-4 col-md-12 mb-3 mb-lg-0">

                <div class="section_heading mb-0">

                    <div class="section_heading_title justify-content-start">

                        <span></span>

                        <small>
                            {{ $config['seccion_destacados_titulo'] ?? 'Más vendidos' }}
                        </small>

                        <span></span>

                    </div>

                </div>

            </div>
            @endif


            <!-- CATEGORÍAS -->
            <div class="{{ ($config['seccion_destacados_mostrar_encabezado'] ?? 1) == 1 ? 'col-lg-8' : 'col-12' }}">

                <ul class="supermarket_tab_nav ul_li_right nav clearfix flex-wrap" role="tablist">

                    <li>
                        <a class="active" data-toggle="tab" href="#top_tab">
                            Todos
                        </a>
                    </li>

                    @foreach($categoriasProductos as $categoria)

                    <li>
                        <a data-toggle="tab" href="#categoria_{{ $categoria->id_categoria }}">
                            {{ $categoria->nombre }}
                        </a>
                    </li>

                    @endforeach

                </ul>

            </div>

        </div>
        <div class="tab-content">

            <!-- TODOS -->
            <div id="top_tab" class="tab-pane active">
                <ul class="catalogo-productos-grid catalogo-mas-vendidos-grid">
                    @foreach($productos as $producto)
                    @include('sections.partials.catalog-product-card', [
                        'producto' => $producto,
                        'mostrarPrecio' => $mostrarPrecio,
                        'mostrarMarca' => $mostrarMarca,
                    ])
                    @endforeach
                </ul>
            </div>

            <!-- CATEGORÍAS (Mismo diseño premium que el "Todos") -->
            @foreach($categoriasProductos as $categoria)
            <div id="categoria_{{ $categoria->id_categoria }}" class="tab-pane fade">
                <ul class="catalogo-productos-grid catalogo-mas-vendidos-grid">
                    @forelse($categoria->productos as $producto)
                    @include('sections.partials.catalog-product-card', [
                        'producto' => $producto,
                        'mostrarPrecio' => $mostrarPrecio,
                        'mostrarMarca' => $mostrarMarca,
                    ])
                    @empty
                    <li class="catalogo-productos-vacio text-center p-5 text-muted">
                        No hay productos en esta categoría
                    </li>
                    @endforelse
                </ul>
            </div>
            @endforeach

        </div>
    </div>
</section>
