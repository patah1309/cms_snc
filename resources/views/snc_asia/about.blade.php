@extends('layouts.frontend')

@section('title', 'About')

@section('content')
<!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <h1 class="display-3 mb-4 animated slideInDown">About</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 align-items-end mb-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <img class="img-fluid rounded" src="img/about.jpg">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3">About Us</p>
                    <h1 class="display-5 mb-4">The foundation of professionalism for client success</h1>
                    <!-- <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet
                    </p> -->
                    <div class="border rounded-3 p-4 shadow-sm bg-white">
                        <nav>
                            <div class="nav nav-tabs mb-4 border-bottom" id="nav-tab" role="tablist">
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
                                <h5 class="fw-bold mb-3">Who We Are</h5>
                                <p class="text-muted mb-0">
                                    We are professionals with extensive experience and a broad network, focusing on clients in the
                                    financial sector, particularly in capital market practice. We provide financial advisory services
                                    across various industries and business scales-from conglomerate holdings to SMEs-aimed at creating
                                    sustainable growth through well-structured and optimal strategies.
                                </p>
                            </div>

                            <!-- CORE VALUES -->
                            <div class="tab-pane fade" id="nav-mission" role="tabpanel">
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
                            </div>

                            <!-- OUR APPROACH -->
                            <div class="tab-pane fade" id="nav-approach" role="tabpanel">
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


    <!-- Facts Start -->
    <div class="container-fluid facts my-5 py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.1s">
                    <i class="fa fa-users fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                    <span class="fs-5 text-white">Happy Clients</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.3s">
                    <i class="fa fa-check fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                    <span class="fs-5 text-white">Projects Completed</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.5s">
                    <i class="fa fa-users-cog fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                    <span class="fs-5 text-white">Dedicated Staff</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
                <div class="col-sm-6 col-lg-3 text-center wow fadeIn" data-wow-delay="0.7s">
                    <i class="fa fa-award fa-3x text-white mb-3"></i>
                    <h1 class="display-4 text-white" data-toggle="counter-up">1234</h1>
                    <span class="fs-5 text-white">Awards Achieved</span>
                    <hr class="bg-white w-25 mx-auto mb-0">
                </div>
            </div>
        </div>
    </div>
    <!-- Facts End -->


    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3">Our Team</p>
                <h1 class="display-5 mb-5">Exclusive Team</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item">
                        <img class="img-fluid rounded" src="img/team-1.jpg" alt="">
                        <div class="team-text">
                            <h4 class="mb-0">Kate Winslet</h4>
                            <div class="team-social d-flex">
                                <a class="btn btn-square rounded-circle mx-1" href=""><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square rounded-circle mx-1" href=""><i
                                        class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="team-item">
                        <img class="img-fluid rounded" src="img/team-2.jpg" alt="">
                        <div class="team-text">
                            <h4 class="mb-0">Jac Jacson</h4>
                            <div class="team-social d-flex">
                                <a class="btn btn-square rounded-circle mx-1" href=""><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square rounded-circle mx-1" href=""><i
                                        class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="team-item">
                        <img class="img-fluid rounded" src="img/team-3.jpg" alt="">
                        <div class="team-text">
                            <h4 class="mb-0">Doris Jordan</h4>
                            <div class="team-social d-flex">
                                <a class="btn btn-square rounded-circle mx-1" href=""><i
                                        class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-square rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-square rounded-circle mx-1" href=""><i
                                        class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->
@endsection

