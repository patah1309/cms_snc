@extends('layouts.frontend')

@section('title', 'Team')

@section('content')
@php
    $headerPath = ($menuHeaderPaths['team'] ?? null) ?: $settings?->header_home_path;
    $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
@endphp
<!-- Page Header Start -->
    @php
        $headerTitle = $menuHeaderTitles['team'] ?? 'Team Member';
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4">{{ $headerTitle }}</h1>
        </div>
    </div>
    <!-- Page Header End -->


    @if (!empty($teamMembers) && $teamMembers->isNotEmpty())
        <!-- Team Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="display-5 mb-5">Exclusive Team</h1>
                </div>
                <div class="row g-4">
                    @foreach ($teamMembers as $index => $member)
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.2) }}s">
                            <div class="team-item">
                                <img
                                    class="img-fluid rounded"
                                    src="{{ $member->photo_path ? url($member->photo_path) : asset('img/team-1.jpg') }}"
                                    alt="{{ $member->name }}"
                                >
                                <div class="team-text">
                                    <h4 class="mb-0">{{ $member->name }}</h4>
                                    @if (!empty($member->position))
                                        <span class="text-muted d-block mb-2">{{ $member->position }}</span>
                                    @endif
                                    <div class="team-social d-flex">
                                        @if (!empty($member->facebook_url))
                                            <a class="btn btn-square rounded-circle mx-1" href="{{ $member->facebook_url }}" target="_blank" rel="noopener">
                                                <i class="fab fa-facebook-f"></i>
                                            </a>
                                        @endif
                                        @if (!empty($member->twitter_url))
                                            <a class="btn btn-square rounded-circle mx-1" href="{{ $member->twitter_url }}" target="_blank" rel="noopener">
                                                <i class="fab fa-twitter"></i>
                                            </a>
                                        @endif
                                        @if (!empty($member->instagram_url))
                                            <a class="btn btn-square rounded-circle mx-1" href="{{ $member->instagram_url }}" target="_blank" rel="noopener">
                                                <i class="fab fa-instagram"></i>
                                            </a>
                                        @endif
                                    </div>
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
