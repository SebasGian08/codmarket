@extends('admin.layouts.app')

@section('title', 'Blog')

@section('content')

<div class="page-inner">
    @include('admin.partials.page_heading', [
        'icon' => 'fas fa-newspaper',
        'title' => 'Blog',
        'description' => 'Administra publicaciones, categorías y etiquetas desde un solo módulo.',
    ])

    @if(session('success') || session('delete'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') ?? session('delete') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card admin-module-tabs-card mt-2">
        <div class="card-header py-4">
            <ul class="nav nav-tabs admin-module-tabs blog-admin-tabs" role="tablist">
                @if($puedeVerBlogs)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'blogs' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#blog-panel-publicaciones" role="tab"
                        data-tab-value="blogs" aria-controls="blog-panel-publicaciones"
                        aria-selected="{{ $pestanaActiva === 'blogs' ? 'true' : 'false' }}">
                        <i class="fas fa-newspaper" aria-hidden="true"></i>
                        <span>Blogs</span>
                    </a>
                </li>
                @endif

                @if($puedeVerCategorias)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'categorias' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#blog-panel-categorias" role="tab"
                        data-tab-value="categorias" aria-controls="blog-panel-categorias"
                        aria-selected="{{ $pestanaActiva === 'categorias' ? 'true' : 'false' }}">
                        <i class="fas fa-folder-open" aria-hidden="true"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                @endif

                @if($puedeVerTags)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $pestanaActiva === 'tags' ? 'active' : '' }}"
                        data-bs-toggle="tab" href="#blog-panel-tags" role="tab"
                        data-tab-value="tags" aria-controls="blog-panel-tags"
                        aria-selected="{{ $pestanaActiva === 'tags' ? 'true' : 'false' }}">
                        <i class="fas fa-tags" aria-hidden="true"></i>
                        <span>Tags</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content">
                @if($puedeVerBlogs)
                <div class="tab-pane fade {{ $pestanaActiva === 'blogs' ? 'show active' : '' }}"
                    id="blog-panel-publicaciones" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary btn-round"
                            data-bs-toggle="modal" data-bs-target="#modalCreateBlog">
                            <i class="fa fa-plus"></i> Nuevo Blog
                        </button>
                    </div>
                    @include('admin.blog.partials.table', ['dataTableId' => 'tabla-blogs'])
                </div>
                @endif

                @if($puedeVerCategorias)
                <div class="tab-pane fade {{ $pestanaActiva === 'categorias' ? 'show active' : '' }}"
                    id="blog-panel-categorias" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary btn-round"
                            data-bs-toggle="modal" data-bs-target="#modalCreateBlogCategory">
                            <i class="fa fa-plus"></i> Nueva Categoría
                        </button>
                    </div>
                    @include('admin.blog-categories.partials.table', ['dataTableId' => 'tabla-categorias-blog'])
                </div>
                @endif

                @if($puedeVerTags)
                <div class="tab-pane fade {{ $pestanaActiva === 'tags' ? 'show active' : '' }}"
                    id="blog-panel-tags" role="tabpanel">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary btn-round"
                            data-bs-toggle="modal" data-bs-target="#modalCreateBlogTag">
                            <i class="fa fa-plus"></i> Nuevo Tag
                        </button>
                    </div>
                    @include('admin.tags.partials.table', ['dataTableId' => 'tabla-tags-blog'])
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

@if($puedeVerBlogs)
@include('admin.blog.modals.create')
@foreach($blogs as $blog)
    @include('admin.blog.modals.edit')
@endforeach
@endif

@if($puedeVerCategorias)
@include('admin.blog-categories.modals.create')
@foreach($categories as $category)
    @include('admin.blog-categories.modals.edit')
@endforeach
@endif

@if($puedeVerTags)
@include('admin.tags.modals.create')
@foreach($tags as $tag)
    @include('admin.tags.modals.edit')
@endforeach
@endif

@push('scripts')
<script>
function previewCreate(event) {
    updateBlogImagePreview(event, 'previewCreateImg');
}

function previewEdit(event, blogId) {
    updateBlogImagePreview(event, 'previewEditImg' + blogId);
}

function updateBlogImagePreview(event, previewId) {
    var file = event.target.files && event.target.files[0];
    var preview = document.getElementById(previewId);

    if (!file || !preview) {
        return;
    }

    var reader = new FileReader();
    reader.onload = function(loadEvent) {
        preview.src = loadEvent.target.result;
        preview.style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
}

$(document).on('shown.bs.tab', '.blog-admin-tabs [data-bs-toggle="tab"]', function(event) {
    var $tab = $(event.target);
    var target = $tab.attr('href');
    var currentUrl = new URL(window.location.href);

    currentUrl.searchParams.set('tab', $tab.data('tab-value'));
    window.history.replaceState({}, '', currentUrl.toString());

    $(target).find('.admin-data-table').each(function() {
        if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().columns.adjust();
        }
    });
});
</script>
@endpush

@endsection
