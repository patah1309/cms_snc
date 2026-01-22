@extends('layouts.frontend')

@section('title', 'News')

@section('content')
<!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
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
                <div class="col-lg-4">
                    <label class="form-label fw-semibold">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fa fa-search text-gold"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search articles...">
                    </div>
                </div>
                <div class="col-lg-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select class="form-select">
                        <option selected>All Categories</option>
                        <option>Capital Market</option>
                        <option>Corporate Action</option>
                        <option>Mergers & Acquisitions</option>
                        <option>Restructuring</option>
                        <option>Tax & Accounting</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label class="form-label fw-semibold">Sort</label>
                    <select class="form-select">
                        <option selected>Newest</option>
                        <option>Oldest</option>
                        <option>Most Popular</option>
                    </select>
                </div>
                <div class="col-lg-2 d-grid">
                    <button class="btn btn-primary py-3">
                        <i class="fa fa-filter me-2"></i>Apply </button>
                </div>
            </div>
            <div class="row g-5 mt-2">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Featured News -->
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden wow fadeInUp" data-wow-delay="0.25s">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <img src="img/news-featured.jpg" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="Featured News">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body p-4">
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <span class="badge bg-primary">Capital Market</span>
                                        <span class="badge bg-light text-dark border">IPO Insight</span>
                                    </div>
                                    <h3 class="card-title mb-2">Key Considerations Before Going Public in Indonesia</h3>
                                    <p class="text-muted mb-3"> A practical overview of IPO readiness, regulatory requirements, and value-enhancement steps to support a successful listing. </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar-alt me-2"></i>18 Jul 2024 </small>
                                        <a href="news-detail.html" class="btn btn-outline-primary"> Read More <i class="fa fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- News Grid -->
                    <div class="row g-4 mt-2">
                        <!-- Card 1 -->
                        <div class="col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="img/news-featured.jpg" class="card-img-top" alt="News 1" style="height: 210px; object-fit: cover;">
                                <div class="card-body p-4">
                                    <span class="badge bg-light text-dark border mb-2">Corporate Action</span>
                                    <h5 class="card-title mb-2">Understanding Material & Affiliated Transactions</h5>
                                    <p class="text-muted mb-3"> Key points on structuring, governance, and disclosure considerations to support compliant execution. </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar-alt me-2"></i>12 Jul 2024 </small>
                                        <a href="news-detail.html" class="fw-semibold text-gold">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 2 -->
                        <div class="col-md-6 wow fadeInUp" data-wow-delay="0.35s">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="img/news-featured.jpg" class="card-img-top" alt="News 2" style="height: 210px; object-fit: cover;">
                                <div class="card-body p-4">
                                    <span class="badge bg-light text-dark border mb-2">Mergers & Acquisitions</span>
                                    <h5 class="card-title mb-2">M&A Process: From Strategy to Execution</h5>
                                    <p class="text-muted mb-3"> A high-level view of due diligence, transaction structuring, and negotiation phases in M&A. </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar-alt me-2"></i>05 Jul 2024 </small>
                                        <a href="news-detail.html" class="fw-semibold text-gold">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 3 -->
                        <div class="col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="img/news-featured.jpg" class="card-img-top" alt="News 3" style="height: 210px; object-fit: cover;">
                                <div class="card-body p-4">
                                    <span class="badge bg-light text-dark border mb-2">Restructuring</span>
                                    <h5 class="card-title mb-2">Pre-IPO Value Enhancement Checklist</h5>
                                    <p class="text-muted mb-3"> Operational and financial readiness actions to strengthen the investment story before listing. </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar-alt me-2"></i>28 Jun 2024 </small>
                                        <a href="news-detail.html" class="fw-semibold text-gold">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Card 4 -->
                        <div class="col-md-6 wow fadeInUp" data-wow-delay="0.45s">
                            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                                <img src="img/news-featured.jpg" class="card-img-top" alt="News 4" style="height: 210px; object-fit: cover;">
                                <div class="card-body p-4">
                                    <span class="badge bg-light text-dark border mb-2">Tax & Accounting</span>
                                    <h5 class="card-title mb-2">IPO Tax Incentives: What Companies Should Know</h5>
                                    <p class="text-muted mb-3"> Overview of tax incentive considerations and compliance points to evaluate during IPO planning. </p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar-alt me-2"></i>20 Jun 2024 </small>
                                        <a href="news-detail.html" class="fw-semibold text-gold">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <nav class="mt-5 wow fadeInUp" data-wow-delay="0.5s" aria-label="News pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Categories -->
                    <div class="border rounded-3 p-4 mb-4 wow fadeInUp" data-wow-delay="0.25s">
                        <h5 class="fw-bold mb-3">Categories</h5>
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-light text-start">
                                <i class="fa fa-angle-right text-gold me-2"></i>Capital Market </a>
                            <a href="#" class="btn btn-light text-start">
                                <i class="fa fa-angle-right text-gold me-2"></i>Corporate Action </a>
                            <a href="#" class="btn btn-light text-start">
                                <i class="fa fa-angle-right text-gold me-2"></i>Mergers & Acquisitions </a>
                            <a href="#" class="btn btn-light text-start">
                                <i class="fa fa-angle-right text-gold me-2"></i>Restructuring </a>
                            <a href="#" class="btn btn-light text-start">
                                <i class="fa fa-angle-right text-gold me-2"></i>Tax & Accounting </a>
                        </div>
                    </div>
                    <!-- Recent Posts -->
                    <div class="border rounded-3 p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                        <h5 class="fw-bold mb-3">Recent Posts</h5>
                        <div class="d-flex mb-3">
                            <img src="img/news-featured.jpg" class="rounded" style="width: 86px; height: 70px; object-fit: cover;" alt="">
                            <div class="ps-3">
                                <a href="news-detail.html" class="fw-semibold d-block text-dark">Material & Affiliated Transactions</a>
                                <small class="text-muted">
                                    <i class="fa fa-calendar-alt me-2"></i>12 Jul 2024 </small>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <img src="img/news-featured.jpg" class="rounded" style="width: 86px; height: 70px; object-fit: cover;" alt="">
                            <div class="ps-3">
                                <a href="news-detail.html" class="fw-semibold d-block text-dark">M&A Process: From Strategy to Execution</a>
                                <small class="text-muted">
                                    <i class="fa fa-calendar-alt me-2"></i>05 Jul 2024 </small>
                            </div>
                        </div>
                        <div class="d-flex">
                            <img src="img/news-featured.jpg" class="rounded" style="width: 86px; height: 70px; object-fit: cover;" alt="">
                            <div class="ps-3">
                                <a href="news-detail.html" class="fw-semibold d-block text-dark">Pre-IPO Value Enhancement Checklist</a>
                                <small class="text-muted">
                                    <i class="fa fa-calendar-alt me-2"></i>28 Jun 2024 </small>
                            </div>
                        </div>
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
