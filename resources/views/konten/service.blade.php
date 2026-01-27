@extends('layouts.frontend')

@section('title', 'Services')

@section('content')
@php
    $headerPath = ($menuHeaderPaths['services'] ?? null) ?: $settings?->header_home_path;
    $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
@endphp
<!-- Page Header Start -->
    @php
        $headerTitle = $menuHeaderTitles['services'] ?? 'Services';
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4">{{ $headerTitle }}</h1>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Service Start -->
    <div class="container-xxl service py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                {{-- <h1 class="display-5 mb-5">Comprehensive solutions for every stage of business growth.</h1> --}}
            </div>
            @php
                $serviceItems = $serviceItems ?? collect();
            @endphp
            @if ($serviceItems->isNotEmpty())
                <div class="row g-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="col-lg-4">
                        <div class="nav nav-pills d-flex justify-content-between w-100 h-100 me-4">
                            @foreach ($serviceItems as $index => $item)
                                <button
                                    class="nav-link w-100 d-flex align-items-center text-start border p-4 {{ $index === 0 ? 'mb-4 active' : 'mb-4' }}"
                                    data-bs-toggle="pill"
                                    data-bs-target="#tab-pane-{{ $item->id }}"
                                    type="button"
                                >
                                    <h5 class="m-0">
                                        <i class="fa fa-bars text-gold me-3"></i>{{ $item->title }}
                                    </h5>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="tab-content w-100">
                            @foreach ($serviceItems as $index => $item)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="tab-pane-{{ $item->id }}">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="position-relative service-image-frame">
                                                <img
                                                    class="position-absolute rounded w-100 h-100"
                                                    src="{{ $item->cover_image_path ? url($item->cover_image_path) : asset('img/service-1.jpg') }}"
                                                    style="object-fit: cover;"
                                                    alt="{{ $item->title }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h3 class="mb-4">{{ $item->title }}</h3>
                                            <div class="mb-4 text-muted service-desc">
                                                {!! $item->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-muted">No services available.</div>
            @endif
        </div>
    </div>
    <!-- Service End -->


@endsection
