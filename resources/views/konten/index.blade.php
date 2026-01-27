@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
@php
    $whatsappNumber = preg_replace('/\D+/', '', $settings?->whatsapp_number ?? '');
    $whatsappUrl = $whatsappNumber
        ? 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode('Hello Satu Nusa Capital, I would like to request a consultation.')
        : 'https://wa.me/62812xxxxxxx?text=Hello%20Satu%20Nusa%20Capital,%20I%20would%20like%20to%20request%20a%20consultation.';
    $ipoInsightLink = '/ipo-insight';
    foreach (($navMenus ?? []) as $menu) {
        $slug = $menu['href'] ?? '';
        $slug = trim(parse_url($slug, PHP_URL_PATH) ?? '', '/');
        if ($slug === 'ipo-insight' && !empty($menu['children'])) {
            $ipoInsightLink = $menu['children'][0]['href'] ?? $ipoInsightLink;
            break;
        }
    }
@endphp
<!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                @php
                    $homeSlides = $homeSlides ?? collect();
                @endphp
                @php
                    $slidesToShow = $homeSlides->values();
                    if ($slidesToShow->isEmpty()) {
                        $slidesToShow = collect([[
                            'title' => 'Strategic Financial Advisory for Sustainable Growth',
                            'description' => 'End-to-end advisory across IPO readiness, corporate actions, M&A, and restructuring-focused on value creation for clients, shareholders, and stakeholders.',
                            'image_path' => null,
                            'buttons' => [
                                ['label' => 'Our Services', 'url' => '/services'],
                                ['label' => 'Request Consultation', 'url' => $whatsappUrl],
                                ['label' => 'Contact Us', 'url' => '/contact'],
                            ],
                        ]]);
                    }
                @endphp
                @foreach ($slidesToShow as $index => $slide)
                    @php
                        $slideImage = !empty($slide['image_path'])
                            ? url($slide['image_path'])
                            : (isset($slide->image_path) && $slide->image_path ? url($slide->image_path) : asset('img/carousel-1.jpg'));
                        $slideTitle = $slide['title'] ?? $slide->title ?? 'Strategic Financial Advisory for Sustainable Growth';
                        $slideDescription = $slide['description'] ?? $slide->description ?? '';
                        $slideButtons = $slide['buttons'] ?? $slide->buttons ?? null;
                        if (empty($slideButtons) && !empty($slide->button_label) && !empty($slide->button_url)) {
                            $slideButtons = [['label' => $slide->button_label, 'url' => $slide->button_url]];
                        }
                    @endphp
                    <div class="carousel-item hero-overlay {{ $index === 0 ? 'active' : '' }}">
                        <img class="w-100" src="{{ $slideImage }}" alt="Satu Nusa Capital">
                        <div class="carousel-caption">
                            <div class="container hero-content">
                                <div class="row justify-content-start">
                                    <div class="col-lg-8">
                                        <h1 class="mb-3 hero-title text-white">{{ $slideTitle }}</h1>
                                        <div class="mb-4 hero-lead text-white" style="max-width: 760px;">
                                            {!! $slideDescription !!}
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if (!empty($slideButtons))
                                                @foreach ($slideButtons as $btnIndex => $button)
                                                    @php
                                                        $label = $button['label'] ?? '';
                                                        $url = $button['url'] ?? '#';
                                                        $btnClass = $btnIndex === 0 ? 'btn btn-primary py-3 px-5' : 'btn btn-outline-light py-3 px-5';
                                                        if (stripos($label, 'whatsapp') !== false || stripos($url, 'wa.me') !== false) {
                                                            $btnClass = 'btn btn-whatsapp py-3 px-5';
                                                        }
                                                    @endphp
                                                    <a href="{{ $url }}" class="{{ $btnClass }}" target="{{ str_starts_with($url, 'http') ? '_blank' : '_self' }}" rel="noopener">
                                                        @if (stripos($label, 'whatsapp') !== false)
                                                            <i class="fab fa-whatsapp me-2"></i>
                                                        @endif
                                                        {{ $label }}
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
    {{-- <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 align-items-end mb-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <img class="img-fluid rounded" src="img/about.jpg">
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h1 class="display-5 mb-4">We Help Our Clients To Grow Their Business</h1>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et
                        eos. Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet
                    </p>
                    <div class="border rounded p-4">
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link fw-semi-bold active" id="nav-story-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-story" type="button" role="tab" aria-controls="nav-story"
                                    aria-selected="true">About Us</button>
                                <button class="nav-link fw-semi-bold" id="nav-mission-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-mission" type="button" role="tab" aria-controls="nav-mission"
                                    aria-selected="false">Our Core Values</button>
                                <button class="nav-link fw-semi-bold" id="nav-vision-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-vision" type="button" role="tab" aria-controls="nav-vision"
                                    aria-selected="false">Our Approach</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-story" role="tabpanel"
                                aria-labelledby="nav-story-tab">
                                @if (!empty($settings?->about_us))
                                    {!! $settings->about_us !!}
                                @else
                                    <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                        amet diam et eos labore.</p>
                                    <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                        Clita erat ipsum et lorem et sit</p>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="nav-mission" role="tabpanel"
                                aria-labelledby="nav-mission-tab">
                                @if (!empty($settings?->core_values))
                                    {!! $settings->core_values !!}
                                @else
                                    <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                        amet diam et eos labore.</p>
                                    <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                        Clita erat ipsum et lorem et sit</p>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="nav-vision" role="tabpanel" aria-labelledby="nav-vision-tab">
                                @if (!empty($settings?->approach))
                                    {!! $settings->approach !!}
                                @else
                                    <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                        amet diam et eos labore.</p>
                                    <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                        Clita erat ipsum et lorem et sit</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- About End -->
@endsection
