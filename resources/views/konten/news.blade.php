@extends('layouts.frontend')

@section('title', 'News')

@section('content')
@php
    $headerPath = $settings?->header_news_path ?: $settings?->header_home_path;
    $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
@endphp
<!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4 animated slideInDown">News</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">News</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- NEWS PAGE START -->
    <div class="container-xxl py-5">
        <div class="container">
            <!-- Page Header -->
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 760px;">
                <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3">News & Insights</p>
                <h1 class="display-5 mb-3">Latest Updates & Market Perspectives</h1>
                <p class="text-muted mb-0"> Stay informed with our latest insights on capital markets, corporate actions, and M&A developments. </p>
            </div>
            <!-- Filter Bar -->
            <div class="row g-3 align-items-end mt-4 wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-2">
                        <a
                            href="{{ url('/news') }}"
                            class="btn {{ empty($activeCategory) ? 'btn-primary' : 'btn-outline-primary' }}"
                        >
                            Semua
                        </a>
                        @foreach ($categories ?? [] as $category)
                            <a
                                href="{{ url('/news?category=' . urlencode($category)) }}"
                                class="btn {{ $activeCategory === $category ? 'btn-primary' : 'btn-outline-primary' }}"
                            >
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row g-5 mt-2">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Featured News -->
                    @if (!empty($featuredPost))
                        <div class="card border-0 shadow-sm rounded-3 overflow-hidden wow fadeInUp" data-wow-delay="0.25s">
                            <div class="row g-0">
                                <div class="col-md-5">
                                    <img
                                        src="{{ $featuredPost->cover_image_path ? url($featuredPost->cover_image_path) : asset('img/news-featured.jpg') }}"
                                        class="img-fluid h-100 w-100"
                                        style="object-fit: cover;"
                                        alt="{{ $featuredPost->title }}"
                                    >
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body p-4">
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge bg-primary">{{ $featuredPost->category ?: 'News' }}</span>
                                        </div>
                                        <h3 class="card-title mb-2">{{ $featuredPost->title }}</h3>
                                        <p class="text-muted mb-3">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($featuredPost->summary ?: $featuredPost->body), 160) }}
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="text-muted">
                                                <i class="fa fa-calendar-alt me-2"></i>
                                                {{ $featuredPost->published_at ? $featuredPost->published_at->format('d M Y') : '' }}
                                            </small>
                                            <a href="{{ url('/news/' . $featuredPost->slug) }}" class="btn btn-outline-primary">
                                                Read More <i class="fa fa-arrow-right ms-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- News Grid -->
                    <div class="row g-4 mt-2">
                        @php
                            $postsCollection = $newsPosts instanceof \Illuminate\Pagination\LengthAwarePaginator
                                ? $newsPosts->getCollection()
                                : collect($newsPosts);
                            $gridPosts = $postsCollection->slice($featuredPost ? 1 : 0);
                        @endphp
                        @if ($gridPosts->isNotEmpty())
                            @foreach ($gridPosts as $index => $post)
                                <div class="col-md-6 wow fadeInUp" data-wow-delay="{{ 0.3 + ($index * 0.05) }}s">
                                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                        <img
                                            src="{{ $post->cover_image_path ? url($post->cover_image_path) : asset('img/news-featured.jpg') }}"
                                            class="card-img-top"
                                            alt="{{ $post->title }}"
                                            style="height: 210px; object-fit: cover;"
                                        >
                                        <div class="card-body p-4">
                                        <span class="badge bg-light text-dark border mb-2">{{ $post->category ?: 'News' }}</span>
                                            <h5 class="card-title mb-2">{{ $post->title }}</h5>
                                            <p class="text-muted mb-3">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($post->summary ?: $post->body), 120) }}
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <small class="text-muted">
                                                    <i class="fa fa-calendar-alt me-2"></i>
                                                    {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                                                </small>
                                                <a href="{{ url('/news/' . $post->slug) }}" class="fw-semibold text-gold">Read More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @elseif (empty($featuredPost))
                            <div class="col-12 text-center text-muted">
                                Belum ada berita.
                            </div>
                        @endif
                    </div>
                    @if (!empty($newsPosts) && method_exists($newsPosts, 'links') && $newsPosts->hasPages())
                        <div class="mt-5 wow fadeInUp" data-wow-delay="0.5s">
                            {{ $newsPosts->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Categories -->
                    <div class="border rounded-3 p-4 mb-4 wow fadeInUp" data-wow-delay="0.25s">
                        <h5 class="fw-bold mb-3">Categories</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ url('/news') }}" class="btn btn-light text-start">
                                <i class="fa fa-angle-right text-gold me-2"></i>Semua
                            </a>
                            @if (!empty($categories) && $categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <a href="{{ url('/news?category=' . urlencode($category)) }}" class="btn btn-light text-start">
                                        <i class="fa fa-angle-right text-gold me-2"></i>{{ $category }}
                                    </a>
                                @endforeach
                            @else
                                <div class="text-muted">Belum ada kategori.</div>
                            @endif
                        </div>
                    </div>
                    <!-- Recent Posts -->
                    <div class="border rounded-3 p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                        <h5 class="fw-bold mb-3">Recent Posts</h5>
                        @forelse ($recentPosts ?? [] as $post)
                            <div class="d-flex mb-3">
                                <img
                                    src="{{ $post->cover_image_path ? url($post->cover_image_path) : asset('img/news-featured.jpg') }}"
                                    class="rounded"
                                    style="width: 86px; height: 70px; object-fit: cover;"
                                    alt="{{ $post->title }}"
                                >
                                <div class="ps-3">
                                    <a href="{{ url('/news/' . $post->slug) }}" class="fw-semibold d-block text-dark">{{ $post->title }}</a>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar-alt me-2"></i>
                                        {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada berita.</div>
                        @endforelse
                    </div>
                    <!-- CTA -->
                    <div class="bg-primary rounded-3 p-4 text-white wow fadeInUp" data-wow-delay="0.35s">
                        <h5 class="fw-bold text-white mb-2">Need Advisory Support?</h5>
                        <p class="mb-3 text-white-50"> Contact us to discuss IPO, corporate actions, M&A, or restructuring requirements. </p>
                        <a href="/contact" class="btn btn-light py-3 px-4"> Contact Us <i class="fa fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- NEWS PAGE END -->
@endsection
