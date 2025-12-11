@extends('client.profile.layout')

@section('profile-content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 bg-white rounded-3 p-4">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Image Upload (Top) -->
            <div class="mb-5">
                <label class="form-label fw-semibold d-block mb-3">Profile Photo</label>
                <div class="d-flex gap-3 align-items-center">
                    <div id="current-image-container" class="mb-3 {{ $user->image_url ? '' : 'd-none' }}">
                        <div style="width: 150px; height: 150px; position: relative;">
                            <img src="{{ $user->image_url ? asset($user->image_url) : '' }}" alt="Current Profile"
                                class="w-100 h-100 rounded-circle shadow-sm"
                                style="object-fit: cover; border: 3px solid #fff;">
                        </div>
                    </div>
                    <div id="image-preview-container" class="mb-3">
                        <img id="image-preview" src="#" alt="Preview">
                        <button type="button" id="remove-image-btn" title="Remove image">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div>
                        <label for="image" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
                            <i class="fas fa-camera me-2"></i> Change Photo
                            <input type="file" class="d-none" id="image" name="image" accept="image/*"
                                onchange="previewImage(event)">
                        </label>
                        <div class="form-text mt-2">Allowed JPG, GIF or PNG. Max size of 2MB</div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="full_name" class="form-label fw-semibold">Full Name</label>
                <input type="text" class="form-control input-lg bg-white border" id="full_name" name="full_name"
                    value="{{ old('full_name', $user->full_name) }}" required>
                @error('full_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">Email</label>
                <div class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="email" class="form-control input-lg bg-white border" id="email" name="email"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    @if (!$user->email_verified_at)
                        <button type="button" class="btn btn-warning btn-sm fw-semibold"
                            onclick="document.getElementById('verify-email-form').submit();">
                            <i class="fas fa-exclamation-triangle me-1"></i> Verify
                        </button>
                    @endif
                </div>
                @if ($user->email_verified_at)
                    <div class="text-success mt-2 small">
                        <i class="fas fa-check-circle me-1"></i> Email verified
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label for="phone_number" class="form-label fw-semibold">Phone Number</label>
                <input type="tel" class="form-control input-lg bg-white border" id="phone_number" name="phone_number"
                    value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end pt-4 border-top">
                <button type="submit" class="btn btn-primary-custom rounded-pill px-4 py-2 fw-semibold">Simpan
                    Perubahan</button>
            </div>
        </form>
    </div>

    <!-- Hidden form for verification -->
    <form id="verify-email-form" action="{{ route('auth.resendVerification') }}" method="POST" class="d-none">
        @csrf
        <input type="hidden" name="destination" value="{{ $user->email }}">
        <input type="hidden" name="return_url" value="{{ route('profile.show') }}">
    </form>

    @push('scripts')
        <script>
            const currentImageContainer = document.getElementById('current-image-container');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImageElement = document.getElementById('image-preview');
            const fileInput = document.getElementById('image');
            const removeBtn = document.getElementById('remove-image-btn');

            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImageElement.src = e.target.result;
                        previewContainer.style.display = 'block';
                        if (currentImageContainer) currentImageContainer.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            }

            removeBtn.addEventListener('click', function() {
                fileInput.value = '';
                previewContainer.style.display = 'none';
                if (currentImageContainer && "{{ $user->image_url }}") {
                    currentImageContainer.style.display = 'block';
                }
            });
        </script>
    @endpush
@endsection
