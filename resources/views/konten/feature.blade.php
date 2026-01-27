@extends('layouts.frontend')

@section('title', 'Feature')

@section('content')
@php
    $headerPath = ($menuHeaderPaths['feature'] ?? null) ?: $settings?->header_home_path;
    $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
@endphp
<!-- Page Header Start -->
    @php
        $headerTitle = $menuHeaderTitles['feature'] ?? 'Feature';
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4">{{ $headerTitle }}</h1>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Features Start -->
    <div class="container-xxl feature py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="display-5 mb-4">Few Reasons Why People Choosing Us!</h1>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet
                    </p>
                    <a class="btn btn-primary py-3 px-5" href="">Explore More</a>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4 align-items-center">
                        <div class="col-md-6">
                            <div class="row g-4">
                                <div class="col-12 wow fadeIn" data-wow-delay="0.3s">
                                    <div class="feature-box border rounded p-4">
                                        <i class="fa fa-check fa-3x text-gold mb-3"></i>
                                        <h4 class="mb-3">Fast Executions</h4>
                                        <p class="mb-3">Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo
                                            justo erat amet</p>
                                        <a class="fw-semi-bold" href="">Read More <i
                                                class="fa fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                                <div class="col-12 wow fadeIn" data-wow-delay="0.5s">
                                    <div class="feature-box border rounded p-4">
                                        <i class="fa fa-check fa-3x text-gold mb-3"></i>
                                        <h4 class="mb-3">Guide & Support</h4>
                                        <p class="mb-3">Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo
                                            justo erat amet</p>
                                        <a class="fw-semi-bold" href="">Read More <i
                                                class="fa fa-arrow-right ms-1"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 wow fadeIn" data-wow-delay="0.7s">
                            <div class="feature-box border rounded p-4">
                                <i class="fa fa-check fa-3x text-gold mb-3"></i>
                                <h4 class="mb-3">Financial Secure</h4>
                                <p class="mb-3">Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo
                                    erat amet</p>
                                <a class="fw-semi-bold" href="">Read More <i class="fa fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Features End -->
@endsection
