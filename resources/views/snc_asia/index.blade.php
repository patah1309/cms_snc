@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
<!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active hero-overlay">
                    <img class="w-100" src="img/carousel-1.jpg" alt="Satu Nusa Capital - Advisory">
                    <div class="carousel-caption">
                        <div class="container hero-content">
                            <div class="row justify-content-start">
                                <div class="col-lg-8">
                                    <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3 animated slideInDown hero-pill"> Capital Market - M&A - Restructuring </p>
                                    <h1 class="mb-3 animated slideInDown hero-title text-white"> Strategic Financial Advisory for Sustainable Growth </h1>
                                    <p class="mb-4 animated slideInDown hero-lead text-white" style="max-width: 760px;"> End-to-end advisory across IPO readiness, corporate actions, M&A, and restructuring-focused on value creation for clients, shareholders, and stakeholders. </p>
                                    <div class="d-flex flex-wrap gap-2 animated slideInDown">
                                        <a href="#services" class="btn btn-primary py-3 px-5"> Our Services </a>
                                        <a href="https://wa.me/62812xxxxxxx?text=Hello%20Satu%20Nusa%20Capital,%20I%20would%20like%20to%20request%20a%20consultation." class="btn btn-whatsapp py-3 px-5" target="_blank" rel="noopener">
                                            <i class="fab fa-whatsapp me-2"></i>Request Consultation </a>
                                        <a href="/contact" class="btn btn-outline-light py-3 px-5"> Contact Us </a>
                                    </div>
                                    <div class="mt-4 d-flex flex-wrap gap-3 text-white-50 small animated slideInDown">
                                        <span>
                                            <i class="fa fa-check me-2 text-gold"></i>IPO & SME IPO </span>
                                        <span>
                                            <i class="fa fa-check me-2 text-gold"></i>Corporate Action </span>
                                        <span>
                                            <i class="fa fa-check me-2 text-gold"></i>M&A Advisory </span>
                                        <span>
                                            <i class="fa fa-check me-2 text-gold"></i>Restructuring & Project Finance </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item hero-overlay">
                    <img class="w-100" src="img/carousel-2.jpg" alt="IPO Insight">
                    <div class="carousel-caption">
                        <div class="container hero-content">
                            <div class="row justify-content-start">
                                <div class="col-lg-7">
                                    <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3 animated slideInDown hero-pill"> IPO Insight </p>
                                    <h2 class="mb-3 animated slideInDown hero-title text-white"> From Pre-IPO Preparation to Post-IPO Execution </h2>
                                    <p class="mb-4 animated slideInDown hero-lead text-white" style="max-width: 720px;"> Insights covering IPO background, benefits, tax incentives, IDX listing requirements, e-IPO mechanism, restructuring preparation, and an illustrative IPO timeline. </p>
                                    <div class="d-flex flex-wrap gap-2 animated slideInDown">
                                        <a href="ipo-insight.html" class="btn btn-primary py-3 px-5"> Explore IPO Insight </a>
                                        <a href="#news" class="btn btn-outline-light py-3 px-5"> Read News </a>
                                    </div>
                                    <div class="mt-4 text-white-50 small animated slideInDown">
                                        <i class="fa fa-info-circle me-2 text-gold"></i> Practical guidance aligned with OJK/IDX process stages.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 align-items-end mb-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <img class="img-fluid rounded" src="img/about.jpg">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3">About Us</p>
                    <h1 class="display-5 mb-4">We Help Our Clients To Grow Their Business</h1>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet
                    </p>
                    <div class="border rounded p-4">
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link fw-semi-bold active" id="nav-story-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-story" type="button" role="tab" aria-controls="nav-story"
                                    aria-selected="true">Story</button>
                                <button class="nav-link fw-semi-bold" id="nav-mission-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-mission" type="button" role="tab" aria-controls="nav-mission"
                                    aria-selected="false">Mission</button>
                                <button class="nav-link fw-semi-bold" id="nav-vision-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-vision" type="button" role="tab" aria-controls="nav-vision"
                                    aria-selected="false">Vision</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-story" role="tabpanel"
                                aria-labelledby="nav-story-tab">
                                <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                    amet diam et eos labore.</p>
                                <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                    Clita erat ipsum et lorem et sit</p>
                            </div>
                            <div class="tab-pane fade" id="nav-mission" role="tabpanel"
                                aria-labelledby="nav-mission-tab">
                                <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                    amet diam et eos labore.</p>
                                <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                    Clita erat ipsum et lorem et sit</p>
                            </div>
                            <div class="tab-pane fade" id="nav-vision" role="tabpanel" aria-labelledby="nav-vision-tab">
                                <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                    amet diam et eos labore.</p>
                                <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                    Clita erat ipsum et lorem et sit</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
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
            </div>
        </div>
    </div>
    <!-- About End -->
@endsection

