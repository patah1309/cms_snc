@extends('layouts.frontend')

@section('title', 'About')

@section('content')
@php
    $headerPath = ($menuHeaderPaths['about'] ?? null) ?: $settings?->header_home_path;
    $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
@endphp
<!-- Page Header Start -->
    @php
        $headerTitle = $menuHeaderTitles['about'] ?? 'About';
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4">{{ $headerTitle }}</h1>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 align-items-end mb-4">
                <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                    <!-- <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet
                    </p> -->
                    <div class="border rounded-3 p-4 shadow-sm bg-white">
                        <nav>
                            <div class="nav nav-tabs nav-fill mb-4 border-bottom" id="nav-tab" role="tablist">
                                <button class="nav-link fw-semibold active" id="nav-story-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-story" type="button" role="tab" aria-selected="true">
                                    About Us
                                </button>
                                <button class="nav-link fw-semibold" id="nav-mission-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-mission" type="button" role="tab" aria-selected="false">
                                    Our Core Values
                                </button>
                                <button class="nav-link fw-semibold" id="nav-approach-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-approach" type="button" role="tab" aria-selected="false">
                                    Our Approach
                                </button>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">

                            <!-- ABOUT US -->
                            <div class="tab-pane fade show active" id="nav-story" role="tabpanel">
                                @if (!empty($settings?->about_us))
                                    {!! $settings->about_us !!}
                                @else
                                    <h5 class="fw-bold mb-3">Who We Are</h5>
                                    <p class="text-muted mb-0">
                                        We are professionals with extensive experience and a broad network, focusing on clients in the
                                        financial sector, particularly in capital market practice. We provide financial advisory services
                                        across various industries and business scales-from conglomerate holdings to SMEs-aimed at creating
                                        sustainable growth through well-structured and optimal strategies.
                                    </p>
                                @endif
                            </div>

                            <!-- CORE VALUES -->
                            <div class="tab-pane fade" id="nav-mission" role="tabpanel">
                                @if (!empty($settings?->core_values))
                                    {!! $settings->core_values !!}
                                @else
                                    <h5 class="fw-bold mb-3">Our Core Values</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-3">
                                            <strong>Professionalism & Integrity</strong><br>
                                            <span class="text-muted">
                                                Extensive experience in executing innovative corporate actions with the highest ethical and
                                                integrity standards in the capital market.
                                            </span>
                                        </li>
                                        <li class="mb-3">
                                            <strong>Client-Focused</strong><br>
                                            <span class="text-muted">
                                                Each strategy is designed to prioritize the best interests of our clients and shareholders,
                                                ensuring optimal outcomes.
                                            </span>
                                        </li>
                                        <li>
                                            <strong>Long-Term Oriented</strong><br>
                                            <span class="text-muted">
                                                We build long-term partnerships by supporting our clients throughout the entire engagement,
                                                from initiation to completion (end-to-end).
                                            </span>
                                        </li>
                                    </ul>
                                @endif
                            </div>

                            <!-- OUR APPROACH -->
                            <div class="tab-pane fade" id="nav-approach" role="tabpanel">
                                @if (!empty($settings?->approach))
                                    {!! $settings->approach !!}
                                @else
                                    <h5 class="fw-bold mb-3">Our Approach</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-3">
                                            <strong>Understanding the Goal</strong><br>
                                            <span class="text-muted">
                                                Aligning corporate actions with clients' objectives, core business, and long-term strategy to
                                                create sustainable value.
                                            </span>
                                        </li>
                                        <li class="mb-3">
                                            <strong>Efficiency & Optimization</strong><br>
                                            <span class="text-muted">
                                                Structuring efficient and optimized solutions to maximize value for clients and shareholders.
                                            </span>
                                        </li>
                                        <li class="mb-3">
                                            <strong>Tailor-Made & Original</strong><br>
                                            <span class="text-muted">
                                                Delivering customized and exclusive advisory solutions based on each client's unique needs.
                                            </span>
                                        </li>
                                        <li>
                                            <strong>Going Concern</strong><br>
                                            <span class="text-muted">
                                                Ensuring every restructuring and corporate action supports long-term sustainability and growth.
                                            </span>
                                        </li>
                                    </ul>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            {{-- <div class="border rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-4">
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-secondary">
                                    <i class="fa fa-times text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>No Hidden Cost</h4>
                                    <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                                </div>
                                <div class="border-end d-none d-lg-block"></div>
                            </div>
                            <div class="border-bottom mt-4 d-block d-lg-none"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-secondary">
                                    <i class="fa fa-users text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>Dedicated Team</h4>
                                    <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                                </div>
                                <div class="border-end d-none d-lg-block"></div>
                            </div>
                            <div class="border-bottom mt-4 d-block d-lg-none"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-secondary">
                                    <i class="fa fa-phone text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>24/7 Available</h4>
                                    <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- About End -->


    @if (!empty($teamMembers) && $teamMembers->isNotEmpty())
        <!-- Team Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="display-5 mb-5">Exclusive Team</h1>
                </div>
                <div class="team-carousel owl-carousel owl-theme">
                    @foreach ($teamMembers as $index => $member)
                        <div class="team-carousel-item">
                            <div class="team-card">
                                <div class="team-card-image">
                                    <img
                                        class="img-fluid"
                                        src="{{ $member->photo_path ? url($member->photo_path) : asset('img/team-1.jpg') }}"
                                        alt="{{ $member->name }}"
                                    >
                                </div>
                                <div class="team-card-body">
                                    <h4 class="mb-1">{{ $member->name }}</h4>
                                    @if (!empty($member->position))
                                        <span class="team-position">{{ $member->position }}</span>
                                    @endif
                                    @if (!empty($member->description))
                                        <span class="team-experience">{{ $member->description }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Team End -->
    @endif
@endsection
