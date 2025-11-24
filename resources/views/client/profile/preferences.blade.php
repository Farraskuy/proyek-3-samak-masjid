@extends('client.layout')

@section('title', 'Email Notifications')

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
                            Notifications</span></h4>
                    <p class="text-muted mb-0 small">Manage your email notification preferences</p>
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

                <form action="{{ route('profile.update-preferences') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <h6 class="fw-bold text-dark mb-3">Email Activities</h6>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="notifications_email"
                                name="notifications_email" value="1" {{ $user->notifications_email ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="notifications_email">
                                <span class="d-block fw-bold text-dark">Activity emails</span>
                                <small class="text-muted">Receive emails about your consultation answers and
                                    messages.</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1"
                                {{ $user->newsletter ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="newsletter">
                                <span class="d-block fw-bold text-dark">Newsletter</span>
                                <small class="text-muted">Receive weekly newsletter with islamic content.</small>
                            </label>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h6 class="fw-bold text-dark mb-3">Privacy</h6>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="public_profile" name="public_profile"
                                value="1" {{ $user->public_profile ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="public_profile">
                                <span class="d-block fw-bold text-dark">Public Profile</span>
                                <small class="text-muted">Allow others to see your basic profile information.</small>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-4 border-top">
                        <button type="submit" class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Save Preferences</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
