@extends('client.layout')

@section('title', 'Profil Saya')

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold">Profil Saya</h2>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($user->image_url)
                                <img src="{{ asset($user->image_url) }}" alt="{{ $user->full_name }}" class="rounded-circle"
                                    width="120" height="120" style="object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 120px; height: 120px;">
                                    <i class="fas fa-user fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->full_name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        <p class="text-muted small">{{ $user->phone_number ?? 'Nomor telepon tidak diatur' }}</p>

                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Profil
                            </a>
                            <a href="{{ route('profile.preferences') }}" class="btn btn-secondary">
                                <i class="fas fa-cog"></i> Preferensi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Username</small>
                            <p class="fw-bold mb-0">{{ $user->username }}</p>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Role</small>
                            <p class="fw-bold mb-0">
                                @if ($user->role === 'admin')
                                    <span class="badge bg-danger">Administrator</span>
                                @elseif ($user->role === 'ustadz')
                                    <span class="badge bg-info">Ustadz</span>
                                @else
                                    <span class="badge bg-primary">Jamaah</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <small class="text-muted">Akun Dibuat</small>
                            <p class="fw-bold mb-0">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Statistics -->
                <div class="row mb-4 g-3">
                    <div class="col-md-6">
                        <div class="card border-left-primary shadow">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-xs font-weight-bold text-primary mb-1">Total Konsultasi</div>
                                    <div class="h3 mb-0 font-weight-bold">
                                        {{ \App\Models\Consultation::where('user_id', $user->id)->count() }}
                                    </div>
                                </div>
                                <i class="fas fa-comments fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-success shadow">
                            <div class="card-body d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="text-xs font-weight-bold text-success mb-1">Konsultasi Terjawab</div>
                                    <div class="h3 mb-0 font-weight-bold">
                                        {{ \App\Models\Consultation::where('user_id', $user->id)->where('status', 'answered')->count() }}
                                    </div>
                                </div>
                                <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Menu Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            {{-- <div class="col-md-6">
                                <a href="{{ route('client.consultations.index') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-history"></i> Riwayat Konsultasi
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('client.consultations.create') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-plus"></i> Buat Konsultasi
                                </a>
                            </div> --}}
                            <div class="col-md-6">
                                <a href="{{ route('client.tentang-kami') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-info-circle"></i> Tentang Kami
                                </a>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline-block w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Keamanan</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Ubah password akun Anda secara berkala untuk menjaga keamanan</p>
                        <a href="#changePasswordModal" data-bs-toggle="modal" class="btn btn-warning">
                            <i class="fas fa-lock"></i> Ubah Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-lock"></i> Ubah Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Saat Ini <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required minlength="8">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password <span
                                    class="text-danger">*</span></label>
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" required minlength="8">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
