@extends('client.layout')

@section('title', 'General Settings')

@section('content')
    <div class="container py-5">
        <!-- Header Section -->
        <div class="row mb-5 align-items-center">
            <div class="col-lg-9 d-flex align-items-center mx-auto">
                <div class="position-relative me-4">
                    <div class="rounded-circle overflow-hidden shadow-sm" style="width: 80px; height: 80px;">
                        @if ($user->image_url)
                            <img src="{{ asset($user->image_url) }}" alt="{{ $user->full_name }}" class="w-100 h-100"
                                style="object-fit: cover;">
                        @else
                            <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user fa-2x text-secondary opacity-25"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1">{{ $user->full_name }} <span class="text-muted fw-light">/
                            General</span></h4>
                    <p class="text-muted mb-0 small">Update your username and manage your account</p>
                </div>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="border rounded p-3 d-inline-block bg-white shadow-sm">
                    <span class="fw-bold text-danger">Go Pro</span>
                    <p class="mb-0 small text-muted">Get 3x more portfolio views</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            @include('client.profile.partials.sidebar')

            <!-- Main Content -->
            <div class="col-lg-7">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="username" class="form-label fw-bold text-dark">Username</label>
                        <input type="text" class="form-control bg-white border" id="username"
                            value="{{ $user->username }}" disabled>
                        <div class="form-text text-muted">Your Dribbble URL: https://dribbble.com/{{ $user->username }}
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold text-dark">Account Email</label>
                        <input type="email" class="form-control bg-white border" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="use_different_email">
                            <label class="form-check-label text-muted small" for="use_different_email">
                                Use a different email for project requests and messages
                            </label>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Google Sign-In</label>
                        <div class="border rounded p-3 d-flex align-items-center justify-content-between bg-white">
                            <div class="d-flex align-items-center">
                                <i class="fab fa-google text-danger me-3 fa-lg"></i>
                                <span class="text-muted">Google</span>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="googleSignIn" checked
                                    disabled>
                            </div>
                        </div>
                        <div class="form-text text-muted mt-2">Use Google, in addition to your username and password, to
                            access
                            your account.</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-dark d-flex align-items-center">
                            Disable ads <span class="badge bg-dark ms-2 text-uppercase"
                                style="font-size: 0.6rem;">Pro</span>
                        </label>
                        <p class="text-muted small mb-0">With a Pro account you can disable ads across the site.</p>
                    </div>

                    <div class="d-flex justify-content-end pt-4 border-top">
                        <button type="submit" class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
