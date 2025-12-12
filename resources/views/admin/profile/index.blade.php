@extends('admin.layout')

@section('title', 'Profil Saya')

@push('styles')
    <style>
        .profile-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #cbd5e1;
            box-shadow: 0 0 0 4px rgba(226, 232, 240, 0.5);
        }

        .btn-dark-custom {
            background-color: #0f172a;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .btn-dark-custom:hover {
            background-color: #1e293b;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    <div class="container py-5 profile-wrapper">
        <!-- Header Section -->
        <div class="d-flex align-items-center mb-5">
            <div class="me-4">
                <div class="rounded-circle overflow-hidden" style="width: 64px; height: 64px;">
                    @if ($user->image_url)
                        <img src="{{ asset($user->image_url) }}" alt="{{ $user->full_name }}" class="w-100 h-100"
                            style="object-fit: cover;">
                    @else
                        <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-lg text-secondary opacity-50"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $user->full_name }} <span class="text-muted fw-light">/
                        General</span></h4>
                <p class="text-muted mb-0 small">Update your username and manage your account</p>
            </div>
            <div class="ms-auto">
                <!-- Optional: Add a "Go Pro" or status badge here if needed, matching the reference -->
            </div>
        </div>

        <div class="row">
            <!-- Sidebar -->
            @include('admin.profile.partials.sidebar')

            <!-- Main Content -->
            <div class="col-lg-9 ps-lg-5">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 bg-success-subtle text-success mb-4"
                        role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="username" class="form-label fw-bold text-dark mb-2">Username</label>
                        <input type="text" class="form-control bg-light" id="username" value="{{ $user->username }}"
                            disabled>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold text-dark mb-2">Account Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        @if (!$user->email_verified_at)
                            <div class="mt-2">
                                <button type="button" class="btn btn-link text-warning p-0 text-decoration-none small"
                                    onclick="document.getElementById('verify-email-form').submit();">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Verify Email
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-dark-custom">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form action="{{ route('auth.sendOtp') }}" method="POST" id="verify-email-form" class="d-none">
        @csrf
        <input type="hidden" name="destination" value="{{ $user->email }}">
        <input type="hidden" name="return_url" value="{{ route('admin.profile.index') }}">
    </form>
@endsection
