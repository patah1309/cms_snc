    <!-- Navbar Start -->
    <div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
        <nav class="navbar navbar-expand-lg navbar-primary py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
            <a href="/" class="navbar-brand ms-4 ms-lg-0 d-flex align-items-center">
                @if (!empty($settings?->logo_path))
                    <img src="{{ url($settings->logo_path) }}" alt="{{ $settings?->company_name ?? 'Company' }}" height="42" class="navbar-logo">
                @else
                    <img src="{{ asset('img/logo.png') }}" alt="{{ $settings?->company_name ?? 'Company' }}" height="42" class="navbar-logo">
                @endif
            </a>

            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    @forelse ($navMenus ?? [] as $menu)
                        @if (!empty($menu['children']))
                            <div class="nav-item dropdown">
                                <a
                                    href="#"
                                    class="nav-link dropdown-toggle {{ !empty($menu['is_active']) ? 'active' : '' }}"
                                    data-bs-toggle="dropdown"
                                >
                                    {{ $menu['title'] }}
                                </a>
                                <div class="dropdown-menu border-light m-0">
                                    @foreach ($menu['children'] as $child)
                                        <a href="{{ $child['href'] }}" class="dropdown-item">
                                            {{ $child['title'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $menu['href'] }}" class="nav-item nav-link {{ !empty($menu['is_active']) ? 'active' : '' }}">
                                {{ $menu['title'] }}
                            </a>
                        @endif
                    @empty
                        <a href="/" class="nav-item nav-link active">Home</a>
                    @endforelse
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <a class="btn btn-light btn-sm-square rounded-circle ms-3" href="mailto:{{ $settings?->email ?? 'info@example.com' }}">
                        <small class="fa fa-envelope text-gold"></small>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
