@extends('layouts.frontend')

@section('title', 'Contact')

@section('content')
@php
    $headerPath = ($menuHeaderPaths['contact'] ?? null) ?: $settings?->header_home_path;
    $headerUrl = $headerPath ? url($headerPath) : asset('img/header.jpg');
@endphp
<!-- Page Header Start -->
    @php
        $headerTitle = $menuHeaderTitles['contact'] ?? 'Contact';
    @endphp
    <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s" style="background-image: url('{{ $headerUrl }}');">
        <div class="container">
            <h1 class="display-3 mb-4">{{ $headerTitle }}</h1>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 justify-content-center">
                <div class="col-lg-8 wow fadeIn" data-wow-delay="0.1s">
                    <h1 class="display-5 mb-4">If You Have Any Query, Please Contact Us</h1>
                    <p class="mb-4">Please send your questions or needs using the form below.</p>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">Please review your form and try again.</div>
                    @endif
                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Your Name" required>
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Subject">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" name="message"
                                        style="height: 100px" required>{{ old('message') }}</textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary py-3 px-5" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
@endsection
