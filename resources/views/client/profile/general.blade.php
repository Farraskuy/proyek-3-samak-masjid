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
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold text-dark">Account Email</label>
                        <input type="email" class="form-control bg-white border" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end pt-4 border-top">
                        <button type="submit" class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
