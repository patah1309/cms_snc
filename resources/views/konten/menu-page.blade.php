@extends('layouts.frontend')

@section('title', $page->title ?: ($menu->title ?? 'Page'))

@section('content')
    @php
        $menuSlug = $menu?->slug ?? null;
        $headerPath = ($menuSlug && !empty($menuHeaderPaths[$menuSlug])) ? $menuHeaderPaths[$menuSlug] : $settings?->header_home_path;
        $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
        $headerTitle = ($menuSlug && !empty($menuHeaderTitles[$menuSlug])) ? $menuHeaderTitles[$menuSlug] : ($page->title ?: ($menu->title ?? 'Page'));
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4">{{ $headerTitle }}</h1>
        </div>
    </div>

    <div class="container-fluid py-5">
        <div class="container-fluid px-4 px-lg-5">
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
@endsection
