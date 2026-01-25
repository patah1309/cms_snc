@extends('layouts.frontend')

@section('title', $post->title ?? 'News')

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
                    <li class="breadcrumb-item"><a href="/news">News</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <img
                            src="{{ $post->cover_image_path ? url($post->cover_image_path) : asset('img/news-featured.jpg') }}"
                            class="img-fluid w-100"
                            style="max-height: 380px; object-fit: cover;"
                            alt="{{ $post->title }}"
                        >
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary">{{ $post->category ?: 'News' }}</span>
                            </div>
                            <h1 class="mb-3">{{ $post->title }}</h1>
                            <div class="text-muted mb-4">
                                <i class="fa fa-calendar-alt me-2"></i>
                                {{ $post->published_at ? $post->published_at->format('d M Y') : '' }}
                            </div>
                            @if (!empty($post->summary))
                                <div class="mb-4 text-muted fw-semibold">
                                    {!! $post->summary !!}
                                </div>
                            @endif
                            <div class="content-body">
                                {!! $post->body !!}
                            </div>
                            <div class="mt-4">
                                <a href="/news" class="btn btn-outline-primary">
                                    <i class="fa fa-arrow-left me-2"></i> Back to News
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="border rounded-3 p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        <h5 class="fw-bold mb-3">Recent Posts</h5>
                        @forelse ($recentPosts ?? [] as $item)
                            <div class="d-flex mb-3">
                                <img
                                    src="{{ $item->cover_image_path ? url($item->cover_image_path) : asset('img/news-featured.jpg') }}"
                                    class="rounded"
                                    style="width: 86px; height: 70px; object-fit: cover;"
                                    alt="{{ $item->title }}"
                                >
                                <div class="ps-3">
                                    <a href="{{ url('/news/' . $item->slug) }}" class="fw-semibold d-block text-dark">
                                        {{ $item->title }}
                                    </a>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar-alt me-2"></i>
                                        {{ $item->published_at ? $item->published_at->format('d M Y') : '' }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada berita.</div>
                        @endforelse
                    </div>
                    <div class="bg-primary rounded-3 p-4 text-white wow fadeInUp" data-wow-delay="0.25s">
                        <h5 class="fw-bold text-white mb-2">Need Advisory Support?</h5>
                        <p class="mb-3 text-white-50">
                            Contact us to discuss IPO, corporate actions, M&A, or restructuring requirements.
                        </p>
                        <a href="/contact" class="btn btn-light py-3 px-4">
                            Contact Us <i class="fa fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
