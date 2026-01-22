    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Our Office</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>{{ $settings?->address ?? '123 Street, New York, USA' }}</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>{{ $settings?->phone ?? '+012 345 67890' }}</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>{{ $settings?->email ?? 'info@example.com' }}</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light rounded-circle me-2" href=""><i
                                class="fab fa-twitter"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-2" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-2" href=""><i
                                class="fab fa-youtube"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-2" href=""><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Services</h4>
                    <a class="btn btn-link" href="">Capital Market</a>
                    <a class="btn btn-link" href="">Corporate Action</a>
                    <a class="btn btn-link" href="">Mergers & Acquisitions</a>
                    <a class="btn btn-link" href="">Corporate Finance, Restructuring & Project Finance</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4">Quick Links</h4>
                    @php
                        $quickLinks = [];
                        foreach (($navMenus ?? []) as $menu) {
                            if (!empty($menu['children'])) {
                                foreach ($menu['children'] as $child) {
                                    $quickLinks[] = $child;
                                }
                            } else {
                                $quickLinks[] = $menu;
                            }
                        }
                    @endphp
                    @forelse ($quickLinks as $item)
                        <a class="btn btn-link" href="{{ $item['href'] }}">{{ $item['title'] }}</a>
                    @empty
                        <a class="btn btn-link" href="/">Home</a>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">{{ $settings?->company_name ?? 'SNC Asia' }}</a>, All Right Reserved.
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i
            class="bi bi-arrow-up"></i></a>
