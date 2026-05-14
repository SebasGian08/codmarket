@extends('layouts.appweb')

@section('title', $blog->title)

@section('content')

<section class="details_section blog_details sec_ptb_140 clearfix" style="padding-top: 120px;">
    <div class="container">
        <div class="row justify-content-lg-between">

            <!-- CONTENT -->
            <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">

                <!-- IMAGE -->
                <div class="details_image mb_30">
                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}">
                </div>

                <!-- META -->
                <div class="row mb_15 align-items-center justify-content-lg-between">

                    <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                        <ul class="post_meta ul_li clearfix">
                            <li>
                                {{ $blog->created_at->format('d M Y') }}
                            </li>

                            <li>
                                <a href="#!">
                                    {{ $blog->category->name ?? 'General' }}
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                        <ul class="post_meta ul_li_right clearfix">
                            <li><a href="#!"><i class="fas fa-heart"></i> 0</a></li>
                            <li><a href="#!"><i class="fas fa-comment-alt-lines"></i> 0</a></li>
                        </ul>
                    </div>

                </div>

                <!-- TITLE -->
                <h2 class="item_title mb_30">{{ $blog->title }}</h2>

                <!-- EXCERPT -->
                <p class="mb_30">
                    {{ $blog->excerpt }}
                </p>

                <!-- CONTENT -->
                <div class="mb_30">
                    {!! strip_tags($blog->content, '<p><strong><br>
                            <ul>
                                <li>
                                    <h1>
                                        <h2>
                                            <h3>
                                                <h4>') !!}
                </div>

                <!-- TAGS (opcional dinámico si luego lo tienes) -->
                <ul class="item_tag_list ul_li clearfix">
                    <li>
                        <h4 class="list_title text-uppercase mb-0">Tags:</h4>
                    </li>
                    <li><a href="#!">{{ $blog->category->name ?? 'General' }}</a></li>
                </ul>

                <!-- SHARE -->
                <div class="post_share_box">

                    <div class="share_content">

                        <div class="share_text">
                            <span class="share_label">
                                Compartir artículo
                            </span>

                            <h4>
                                Difunde este contenido
                            </h4>
                        </div>

                        @php
                        $url = urlencode(Request::url());
                        $titulo = urlencode($blog->titulo ?? 'Mira este artículo');
                        @endphp

                        <ul class="circle_social_links">

                            <!-- FACEBOOK -->
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank"
                                    class="facebook">

                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </li>

                            <!-- TWITTER / X -->
                            <li>
                                <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $titulo }}"
                                    target="_blank" class="twitter">

                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>

                            <!-- LINKEDIN -->
                            <li>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $url }}" target="_blank"
                                    class="linkedin">

                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>

                            <!-- WHATSAPP -->
                            <li>
                                <a href="https://wa.me/?text={{ $titulo }}%20{{ $url }}" target="_blank"
                                    class="whatsapp">

                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>
                <style>
                .post_share_box {
                    background: linear-gradient(135deg, #1f1f1f, #2d2d2d);
                    border-radius: 18px;
                    padding: 28px 35px;
                    margin-bottom: 50px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
                    overflow: hidden;
                    position: relative;
                }

                .post_share_box::before {
                    content: '';
                    position: absolute;
                    top: -60px;
                    right: -60px;
                    width: 180px;
                    height: 180px;
                    background: rgba(255, 255, 255, .04);
                    border-radius: 50%;
                }

                .share_content {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 205px;
                    flex-wrap: wrap;
                    position: relative;
                    z-index: 2;
                }

                .share_label {
                    display: inline-block;
                    color: rgba(255, 255, 255, .65);
                    font-size: 13px;
                    font-weight: 600;
                    letter-spacing: 1px;
                    text-transform: uppercase;
                    margin-bottom: 6px;
                }

                .share_text h4 {
                    color: #fff;
                    font-size: 28px;
                    font-weight: 700;
                    margin: 0;
                    line-height: 1.2;
                }

                .circle_social_links {
                    display: flex;
                    align-items: center;
                    gap: 14px;
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }

                .circle_social_links li a {
                    width: 52px;
                    height: 52px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    font-size: 18px;
                    text-decoration: none;
                    transition: .3s ease;
                    background: rgba(255, 255, 255, .08);
                    border: 1px solid rgba(255, 255, 255, .08);
                    backdrop-filter: blur(6px);
                }

                .circle_social_links li a:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
                }

                .circle_social_links .facebook:hover {
                    background: #1877f2;
                }

                .circle_social_links .twitter:hover {
                    background: #1da1f2;
                }

                .circle_social_links .linkedin:hover {
                    background: #0a66c2;
                }

                .circle_social_links .instagram:hover {
                    background: linear-gradient(135deg, #f58529, #dd2a7b, #8134af);
                }

                @media(max-width: 768px) {

                    .share_content {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    .share_text h4 {
                        font-size: 22px;
                    }

                }
                </style>
                <!-- RECENT / RELATED -->
                <div class="related_post_carousel position-relative mb_100">
                    <h3 class="title_text text-uppercase mb_30">Artículos Relacionados</h3>

                    <div class="slideshow3_slider" data-slick='{"dots": false}'>

                        @foreach($recentBlogs as $item)
                        <div class="item">
                            <div class="blog_grid">

                                <a class="blog_image" href="{{ route('blog.show', $item->slug) }}">
                                    <img src="{{ asset($item->image) }}" alt="{{ $item->title }}">
                                </a>

                                <div class="blog_content">

                                    <ul class="post_meta ul_li clearfix">
                                        <li>{{ $item->created_at->format('d M Y') }}</li>
                                        <li>By Admin</li>
                                    </ul>

                                    <h3 class="blog_title">
                                        <a href="{{ route('blog.show', $item->slug) }}">
                                            {{ $item->title }}
                                        </a>
                                    </h3>

                                    <a class="text_btn" href="{{ route('blog.show', $item->slug) }}">
                                        <span>Read More</span>
                                    </a>

                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">

                <aside class="sidebar_section clearfix">

                    <!-- SEARCH -->
                    <div class="sb_widget sb_search">
                        <form method="GET" action="{{ route('blog.index') }}">
                            <div class="form_item mb-0">
                                <input type="search" name="q" placeholder="Search..." value="{{ request('q') }}">
                                <button type="submit" class="submit_btn">
                                    <i class="fal fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- RECENT POSTS -->
                    <div class="sb_widget sb_recent_post">
                        <h3 class="sb_widget_title">Recent Posts</h3>

                        @foreach($recentBlogs as $item)
                        <div class="small_blog">
                            <a href="{{ route('blog.show', $item->slug) }}" class="item_image">
                                <img src="{{ asset($item->image) }}" alt="">
                            </a>
                            <div class="item_content">
                                <h3 class="item_title">
                                    <a href="{{ route('blog.show', $item->slug) }}">
                                        {{ Str::limit($item->title, 50) }}
                                    </a>
                                </h3>
                                <span class="post_date">
                                    {{ $item->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        @endforeach

                    </div>

                    <!-- CATEGORIES -->
                    <div class="sb_widget sb_category">
                        <h3 class="sb_widget_title">Category</h3>
                        <ul class="ul_li_block clearfix">
                            <li>
                                <a href="#!">
                                    {{ $blog->category->name ?? 'General' }}
                                </a>
                            </li>
                        </ul>
                    </div>

                </aside>

            </div>

        </div>
    </div>
</section>

@include('sections.contact')

@endsection