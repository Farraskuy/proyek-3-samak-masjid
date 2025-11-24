@extends('client.profile.layout')

@section('profile-content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 bg-white rounded-3 p-4">
        <form action="{{ route('profile.change-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="current_password" class="form-label fw-semibold">Current Password</label>
                <input type="password" class="form-control input-lg bg-white border" id="current_password"
                    name="current_password" required>
                @error('current_password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">New Password</label>
                <input type="password" class="form-control input-lg bg-white border" id="password" name="password" required
                    minlength="8">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                <input type="password" class="form-control input-lg bg-white border" id="password_confirmation"
                    name="password_confirmation" required minlength="8">
            </div>

            <div class="d-flex justify-content-end pt-4 border-top">
                <button type="submit" class="btn btn-primary-custom rounded-pill px-4 py-2 fw-semibold">Update
                    Password</button>
            </div>
        </form>
    </div>
@endsection
