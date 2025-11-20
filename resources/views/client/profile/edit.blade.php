@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Edit Profil Saya
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Kesalahan!</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4 text-center">
                                <div class="mb-3">
                                    @if ($user->image_url)
                                        <img id="profilePreview" src="{{ asset($user->image_url) }}"
                                            alt="{{ $user->full_name }}" class="rounded-circle" width="120"
                                            height="120" style="object-fit: cover;">
                                    @else
                                        <div id="profilePreview" class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 120px; height: 120px;">
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <label for="image" class="form-label">Ubah Foto Profil</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-2">JPG, PNG, GIF maksimal 2MB</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="full_name" class="form-label fw-bold">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                    id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                                    required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="phone_number" class="form-label fw-bold">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                    id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', $user->phone_number) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan nomor telepon dengan format: 08xxxxxxxxxx</small>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Catatan:</strong> Untuk mengubah username dan password, gunakan menu yang tersedia di halaman profil.
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePreview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="rounded-circle" width="120" height="120" style="object-fit: cover;">';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
