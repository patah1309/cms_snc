@extends('layouts.frontend')

@section('title', $page->title ?: ($menu->title ?? 'Page'))

@section('content')
    @php
        $headerPath = $settings?->header_home_path;
        $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4 animated slideInDown">{{ $page->title ?: $menu->title }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title ?: $menu->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-12">
                    <div class="border rounded-3 p-4 shadow-sm bg-white">
                        <h2 class="mb-3 text-center">{{ $page->title ?: $menu->title }}</h2>
                        @if (!empty($page->image_path))
                            <img
                                src="{{ url($page->image_path) }}"
                                class="img-fluid rounded mb-4 d-block mx-auto"
                                alt="{{ $page->title ?: $menu->title }}"
                            >
                        @endif
                        <div class="text-muted">
                            {!! $page->body !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
