@extends('admin.layout')

@section('title', 'Edit Profil | Admin')

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
                        <img id="headerProfilePreview" src="{{ asset($user->image_url) }}" alt="{{ $user->full_name }}"
                            class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="bg-light w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user fa-lg text-secondary opacity-50"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $user->full_name }} <span class="text-muted fw-light">/
                        Edit Profile</span></h4>
                <p class="text-muted mb-0 small">Update your personal details</p>
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

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Profile Image Upload -->
                    <div class="mb-5">
                        <label class="form-label fw-bold text-dark mb-3">Profile Photo</label>
                        <div class="d-flex align-items-center gap-4">
                            <div class="position-relative">
                                <div class="rounded-circle overflow-hidden bg-light" style="width: 100px; height: 100px;">
                                    <img id="image-preview"
                                        src="{{ $user->image_url ? asset($user->image_url) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&background=random' }}"
                                        alt="Preview" class="w-100 h-100 object-fit-cover">
                                </div>
                            </div>
                            <div>
                                <div class="d-flex gap-2 mb-2">
                                    <label for="image" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                        Change Photo
                                        <input type="file" class="d-none" id="image" name="image" accept="image/*"
                                            onchange="previewImage(event)">
                                    </label>
                                    @if ($user->image_url)
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                            Remove
                                        </button>
                                    @endif
                                </div>
                                <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 2MB</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="full_name" class="form-label fw-bold text-dark mb-2">Full Name</label>
                        <input type="text" class="form-control" id="full_name" name="full_name"
                            value="{{ old('full_name', $user->full_name) }}" required>
                        @error('full_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="phone_number" class="form-label fw-bold text-dark mb-2">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                            value="{{ old('phone_number', $user->phone_number) }}">
                        @error('phone_number')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-5">
                        <button type="submit" class="btn btn-dark-custom">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('image-preview').src = e.target.result;
                        // Also update header preview if exists
                        const headerPreview = document.getElementById('headerProfilePreview');
                        if (headerPreview) headerPreview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        </script>
    @endpush
@endsection
