@extends('layouts.appweb')

@section('title')
@if(isset($texto))
Resultados para "{{ $texto }}"
@else
Catálogo de Productos
@endif
@endsection

@section('content')

<section class="product_section sec_ptb_50 clearfix">
    <div class="container maxw_1600">
        <div class="row justify-content-lg-between">

            {{-- PRODUCTOS --}}
            <div class="col-lg-9 order-last">

                {{-- FILTRO SUPERIOR --}}
                <ul class="electronic_filter_bar ul_li mb_30 filtros-productos-grid">

                    {{-- GRID / LIST --}}
                    <!-- <li>
                        <ul class="layout_btns nav ul_li clearfix" role="tablist">
                            <li>
                                <a class="active" data-toggle="tab" href="#grid_layout">
                                    <i class="fas fa-th"></i>
                                </a>
                            </li>

                            <li>
                                <a data-toggle="tab" href="#list_layout">
                                    <i class="fas fa-bars"></i>
                                </a>
                            </li>
                        </ul>
                    </li> -->

                    {{-- MOSTRAR --}}
                    <li>
                        <div class="product_show option_select">

                            <form method="GET" action="{{ route('productos.index') }}">

                                <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                                <input type="hidden" name="marca" value="{{ request('marca') }}">
                                <input type="hidden" name="min" value="{{ request('min') }}">
                                <input type="hidden" name="max" value="{{ request('max') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">

                                <select name="mostrar" onchange="this.form.submit()">

                                    <option value="12" {{ request('mostrar', 12) == 12 ? 'selected' : '' }}>
                                        Mostrar: 12
                                    </option>

                                    <option value="24" {{ request('mostrar') == 24 ? 'selected' : '' }}>
                                        Mostrar: 24
                                    </option>

                                    <option value="36" {{ request('mostrar') == 36 ? 'selected' : '' }}>
                                        Mostrar: 36
                                    </option>

                                </select>

                            </form>

                        </div>
                    </li>

                    {{-- PAGINADOR ARRIBA --}}
                    <li>

                        <p class="result_text mb-0 d-flex align-items-center">

                            <span class="active_page">
                                {{ $productos->currentPage() }}
                            </span>

                            de

                            {{ $productos->lastPage() }}

                            @if($productos->hasMorePages())

                            <a class="next_btn" href="{{ $productos->nextPageUrl() }}">

                                <i class="fal fa-long-arrow-right"></i>

                            </a>

                            @endif

                        </p>

                    </li>

                </ul>

                {{-- TABS --}}
                <div class="tab-content mb_50">

                    {{-- GRID --}}
                    <div id="grid_layout" class="tab-pane active">

                        <ul class="electronic_product_columns ul_li has_4columns clearfix product_grid_bg">

                            @forelse($productos as $producto)

                            @php

                            $variante = $producto->variantes->first();

                            $imagen = null;

                            if($variante && $variante->imagenes->count()){
                            $imagen = $variante->imagenes->first()->url;
                            }

                            @endphp

                            <li>
                                <div class="medical_product_item">

                                    {{-- Marca --}}
                                    <span class="brand_badge">
                                        {{ optional($producto->marca)->nombre ?? 'Sin marca' }}
                                    </span>

                                    {{-- Imagen --}}
                                    <div class="item_image">
                                        <a href="{{ route('producto.show', $producto->slug) }}">
                                            <img src="{{ !empty($imagen) ? asset($imagen) : asset('assets/images/tienda_virtual/default.png') }}"
                                                alt="{{ $producto->nombre }}">
                                        </a>
                                    </div>

                                    <div class="item_content">

                                        {{-- TITULO --}}
                                        <h3 class="item_title">

                                            <a href="{{ route('producto.show', $producto->slug) }}">

                                                {{ $producto->nombre }}

                                            </a>

                                        </h3>

                                        {{-- CATEGORIA --}}
                                        <div class="category_text">

                                            {{ $producto->categorias->first()->nombre ?? 'Sin categoría' }}

                                        </div>

                                        {{-- PRECIO --}}
                                        <div class="price_box">

                                            @if($variante)

                                            @if($variante->precio_oferta)

                                            <span class="old_price">
                                                S/. {{ number_format($variante->precio, 2) }}
                                            </span>

                                            <span class="price_text">
                                                S/. {{ number_format($variante->precio_oferta, 2) }}
                                            </span>

                                            @else

                                            <span class="price_text">
                                                S/. {{ number_format($variante->precio, 2) }}
                                            </span>

                                            @endif

                                            @else

                                            <span class="price_text">
                                                Sin precio
                                            </span>

                                            @endif

                                        </div>

                                    </div>

                                </div>
                            </li>

                            @empty

                            <div class="col-12">
                                <div class="alert alert-warning mt-2 mb-2">
                                    No se encontraron productos.
                                </div>
                            </div>
                            @endforelse
                        </ul>
                    </div>

                    {{-- LIST --}}
                    <!--  <div id="list_layout" class="tab-pane fade">

                        <ul class="electronic_product_columns ul_li_block clearfix product_list_bg">

                            @foreach($productos as $producto)

                            @php
                            $variante = $producto->variantes->first();

                            $imagenes = $variante && $variante->imagenes->count()
                            ? $variante->imagenes->sortBy('orden')
                            : collect();

                            $imagen = $imagenes->first()
                            ? $imagenes->first()->url
                            : 'assets/images/no-image.png';

                            @endphp

                            <li>

                                <div class="electronic_product_item d-flex align-items-center">

                                    <div class="item_image mr-4">

                                        <a href="{{ route('producto.show', $producto->slug) }}">

                                            <img src="{{ $imagen ? asset($imagen) : asset('assets/images/no-image.png') }}"
                                                alt="{{ $producto->nombre }}">

                                        </a>

                                    </div>

                                    <div class="item_content">

                                        <span class="item_name">

                                            {{ optional($producto->marca)->nombre }}

                                        </span>

                                        <h3 class="item_title">
                                            <a href="{{ route('producto.show', $producto->slug) }}">
                                                {{ $producto->nombre }}
                                            </a>
                                        </h3>

                                        <p class="item_desc">
                                            {!! $producto->descripcion_corta ?? 'Sin descripción' !!}
                                        </p>

                                        <span class="item_price">

                                            S/
                                            {{ number_format($variante ? $variante->precio : 0, 2) }}

                                        </span>

                                    </div>

                                </div>

                            </li>

                            @endforeach

                        </ul>

                    </div> -->

                </div>

                {{-- PAGINADOR --}}
                <div class="abtn_wrap text-center mb_50">
                    {{ $productos->appends(request()->query())->links() }}
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-3">

                <aside class="electronic_sidebar sidebar_section">
                    {{-- BUSCADOR --}}
                    <div class="sb_widget product_search_box mb_20">

                        <form method="GET" action="{{ route('productos.index') }}" class="search_form">

                            {{-- mantener filtros activos --}}
                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            <input type="hidden" name="marca" value="{{ request('marca') }}">
                            <input type="hidden" name="min" value="{{ request('min') }}">
                            <input type="hidden" name="max" value="{{ request('max') }}">

                            <div class="search_input_group">

                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar ..." class="search_input">

                                <button type="submit" class="search_btn">
                                    <i class="fas fa-search"></i>
                                </button>

                            </div>

                        </form>

                    </div>
                    {{-- CATEGORIAS --}}
                    <div class="sb_widget sb_collapse_category">

                        <h3 class="sb_widget_title">
                            Categorías
                        </h3>

                        <div class="custom_category_sidebar">

                            @foreach($categorias as $categoria)

                            <div
                                class="category_sidebar_item {{ request('categoria') == $categoria->slug ? 'active' : '' }}">

                                <a href="{{ route('productos.index', array_merge(request()->all(), [
                                    'categoria' => $categoria->slug
                                ])) }}">
                                    <span>{{ $categoria->nombre }}</span>
                                    <!-- <small>({{ $categoria->productos_count }})</small> -->
                                </a>

                                @if(request('categoria') == $categoria->slug)
                                <a href="{{ route('productos.index', array_merge(request()->except('categoria'))) }}"
                                    class="remove_filter">
                                    ✕
                                </a>
                                @endif

                            </div>

                            @endforeach

                        </div>
                    </div>

                    {{-- PRECIO --}}
                    <div class="sb_widget sb_pricing_range">

                        <h3 class="sb_widget_title">
                            Filtrar por precio
                        </h3>

                        <form method="GET" action="{{ route('productos.index') }}">

                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            <input type="hidden" name="marca" value="{{ request('marca') }}">

                            <div class="price_filter_box">

                                <div class="price_inputs">

                                    <input type="number" name="min" placeholder="Mín" value="{{ request('min') }}">

                                    <span>-</span>

                                    <input type="number" name="max" placeholder="Máx" value="{{ request('max') }}">

                                </div>

                                <button type="submit" class="price_filter_btn">
                                    Aplicar filtro
                                </button>

                            </div>

                        </form>

                    </div>

                    {{-- MARCAS --}}
                    <div class="sb_widget sb_color_checkbox">

                        <h3 class="sb_widget_title">
                            Marcas
                        </h3>
                        <form method="GET" action="{{ route('productos.index') }}" class="brand_filter_form">

                            <input type="hidden" name="categoria" value="{{ request('categoria') }}">
                            <input type="hidden" name="min" value="{{ request('min') }}">
                            <input type="hidden" name="max" value="{{ request('max') }}">

                            <div class="brand_filter_list">

                                @foreach($marcas as $marca)

                                <label class="brand_filter_item">

                                    <input type="checkbox" name="marca" value="{{ $marca->slug }}"
                                        onchange="this.form.submit()"
                                        {{ request('marca') == $marca->slug ? 'checked' : '' }}>

                                    <span class="brand_text">
                                        {{ $marca->nombre }}
                                    </span>

                                    <span class="brand_check"></span>

                                </label>

                                @endforeach

                            </div>

                        </form>
                    </div>

                </aside>

            </div>

        </div>
    </div>
</section>

@endsection