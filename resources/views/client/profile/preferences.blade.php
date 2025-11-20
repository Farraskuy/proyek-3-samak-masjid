@extends('layouts.app')

@section('title', 'Preferensi')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-cog"></i> Preferensi Akun
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update-preferences') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <h5 class="fw-bold mb-3">Notifikasi & Komunikasi</h5>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="notifications_email"
                                        name="notifications_email" value="1">
                                    <label class="form-check-label" for="notifications_email">
                                        <strong>Notifikasi Email</strong>
                                        <br>
                                        <small class="text-muted">Terima notifikasi email untuk jawaban konsultasi, pesan baru, dan update penting lainnya</small>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="newsletter"
                                        name="newsletter" value="1">
                                    <label class="form-check-label" for="newsletter">
                                        <strong>Newsletter</strong>
                                        <br>
                                        <small class="text-muted">Berlangganan newsletter mingguan dengan konten islami dan artikel inspiratif</small>
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <h5 class="fw-bold mb-3">Privasi</h5>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="public_profile"
                                        name="public_profile" value="1">
                                    <label class="form-check-label" for="public_profile">
                                        <strong>Profil Publik</strong>
                                        <br>
                                        <small class="text-muted">Izinkan orang lain melihat informasi profil dasar Anda</small>
                                    </label>
                                </div>
                            </div>

                            <hr>

                            <h5 class="fw-bold mb-3">Lainnya</h5>

                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Data Anda:</strong>
                                <ul class="mb-0 mt-2 small">
                                    <li>Anda dapat mengunduh data pribadi Anda kapan saja</li>
                                    <li>Anda memiliki hak untuk menghapus akun Anda secara permanen</li>
                                </ul>
                            </div>

                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-download"></i> Unduh Data Saya
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteAccountModal">
                                    <i class="fas fa-trash"></i> Hapus Akun
                                </button>
                            </div>

                            <hr>

                            <div class="d-flex gap-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Preferensi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Hapus Akun
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">Apakah Anda yakin ingin menghapus akun ini?</p>
                    <p class="text-muted small mb-0">
                        Tindakan ini bersifat permanen dan tidak dapat dibatalkan. Semua data Anda akan dihapus selamanya.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="alert('Fitur ini akan segera tersedia')">
                        <i class="fas fa-trash"></i> Hapus Akun
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
