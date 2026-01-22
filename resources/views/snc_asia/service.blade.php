@extends('layouts.frontend')

@section('title', 'Services')

@section('content')
<!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container">
            <h1 class="display-3 mb-4 animated slideInDown">Services</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Services</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Service Start -->
    <div class="container-xxl service py-5">
        <div class="container">
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3">Our Services</p>
                <h1 class="display-5 mb-5">Comprehensive solutions for every stage of business growth.</h1>
            </div>
            <div class="row g-4 wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-lg-4">
                    <div class="nav nav-pills d-flex justify-content-between w-100 h-100 me-4">
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-4 active"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-1" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-gold me-3"></i>Capital Market & IPO</h5>
                        </button>
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-4"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-2" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-gold me-3"></i>Corporate Action</h5>
                        </button>
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-4"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-3" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-gold me-3"></i>Mergers & Acquisitions</h5>
                        </button>
                        <button class="nav-link w-100 d-flex align-items-center text-start border p-4 mb-0"
                            data-bs-toggle="pill" data-bs-target="#tab-pane-4" type="button">
                            <h5 class="m-0"><i class="fa fa-bars text-gold me-3"></i>Corporate Finance, Restructuring & Project Finance</h5>
                        </button>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="tab-content w-100">
                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="img/service-1.jpg"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Capital Market & IPO Advisory</h3>
                                    <p class="mb-4">
                                    We advise corporate clients in accessing capital markets through IPO and post-IPO transactions,
                                    including preparation, regulatory compliance, structuring, and execution, to achieve optimal
                                    outcomes for all stakeholders.
                                    </p>

                                    <p><i class="fa fa-check text-gold me-3"></i>IPO & SME IPO Advisory (Pre-IPO, IPO, Post-IPO)</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Equity Capital Market & Rights Offering</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Post-IPO business and compliance advisory</p>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-2">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="img/service-2.jpg"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Corporate Action Advisory</h3>
                                    <p class="mb-4">
                                    We provide strategic advisory and execution support for a wide range of corporate actions,
                                    ensuring regulatory compliance, stakeholder alignment, and value creation.
                                    </p>

                                    <p><i class="fa fa-check text-gold me-3"></i>Material & Affiliated Transactions</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Tender Offer & Share Repurchase</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Private Placement, MESOP & Strategic/Block Sales</p>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-3">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="img/service-3.jpg"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Mergers & Acquisitions</h3>
                                    <p class="mb-4">
                                    Our M&A advisory services support clients throughout the transaction lifecycle,
                                    from strategy formulation and due diligence to structuring and execution.
                                    </p>

                                    <p><i class="fa fa-check text-gold me-3"></i>Merger & Acquisition Advisory</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Business Consolidation & Combination</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Financial, Legal, and Business Due Diligence</p>

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-4">
                            <div class="row g-4">
                                <div class="col-md-6" style="min-height: 350px;">
                                    <div class="position-relative h-100">
                                        <img class="position-absolute rounded w-100 h-100" src="img/service-4.jpg"
                                            style="object-fit: cover;" alt="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3 class="mb-4">Corporate Finance, Restructuring & Project Finance</h3>
                                    <p class="mb-4">
                                    We design and implement efficient financial structures to strengthen business sustainability,
                                    enhance corporate value, and support long-term growth.
                                    </p>

                                    <p><i class="fa fa-check text-gold me-3"></i>Project Finance Advisory</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Internal Corporate Restructuring</p>
                                    <p><i class="fa fa-check text-gold me-3"></i>Special Situation & Value Enhancement</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Callback Start -->
    <div class="container-fluid callback mt-5 py-5">
        <div class="container pt-5">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="bg-white border rounded p-4 p-sm-5 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                            <p class="d-inline-block border rounded text-gold fw-semi-bold py-1 px-3">Get In Touch
                            </p>
                            <h1 class="display-5 mb-5">Request A Call-Back</h1>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="Your Name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="mail" placeholder="Your Email">
                                    <label for="mail">Your Email</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="mobile" placeholder="Your Mobile">
                                    <label for="mobile">Your Mobile</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message"
                                        style="height: 100px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button class="btn btn-primary w-100 py-3" type="submit">Submit Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Callback End -->
@endsection
