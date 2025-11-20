@extends('layouts.app')

@section('title', 'Detail Konsultasi')

@section('content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="fw-bold">Detail Konsultasi</h2>
                    <a href="{{ route('client.consultations.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle"></i> Pertanyaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3">{{ $consultation->question_subject }}</h4>
                        <p class="lead mb-4">{{ $consultation->question_text }}</p>

                        <div class="row text-center border-top pt-3">
                            <div class="col-md-3">
                                <p class="text-muted small mb-0">Dari</p>
                                <p class="fw-bold">
                                    @if ($consultation->is_anonymous)
                                        <span class="badge bg-secondary">Anonim</span>
                                    @else
                                        {{ $consultation->question_from }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted small mb-0">Status</p>
                                <p>
                                    @if ($consultation->status === 'pending')
                                        <span class="badge bg-warning">Menunggu Jawaban</span>
                                    @elseif($consultation->status === 'answered')
                                        <span class="badge bg-success">Sudah Dijawab</span>
                                    @elseif($consultation->status === 'closed')
                                        <span class="badge bg-info">Selesai</span>
                                    @elseif($consultation->status === 'rejected')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted small mb-0">Tanggal</p>
                                <p class="fw-bold">{{ $consultation->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted small mb-0">Dijawab</p>
                                <p class="fw-bold">
                                    @if ($consultation->answered_at)
                                        {{ $consultation->answered_at->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Answer Section -->
                @if ($consultation->answer_text)
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle"></i> Jawaban
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border-left border-success">
                                {{ $consultation->answer_text }}
                            </div>
                            @if ($consultation->answerer)
                                <p class="text-muted small mb-0">Dijawab oleh: <strong>{{ $consultation->answerer->full_name }}</strong></p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Rejection Section -->
                @if ($consultation->rejection_reason)
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-ban"></i> Alasan Penolakan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border-left border-danger">
                                {{ $consultation->rejection_reason }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Conclusion Section -->
                @if ($consultation->conclusion)
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bookmark"></i> Kesimpulan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light border-left border-info">
                                {{ $consultation->conclusion }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cog"></i> Aksi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($consultation->status !== 'closed' && $consultation->status !== 'rejected')
                            <form action="{{ route('client.consultations.close', $consultation->consultation_id) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Tutup konsultasi ini?')">
                                    <i class="fas fa-lock"></i> Tutup Konsultasi
                                </button>
                            </form>
                        @endif

                        @if ($consultation->status === 'pending')
                            <form action="{{ route('client.consultations.delete', $consultation->consultation_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Hapus konsultasi ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card shadow-sm mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informasi
                        </h5>
                    </div>
                    <div class="card-body">
                        <small>
                            <p class="mb-2">
                                <strong>Status Konsultasi:</strong>
                                @if ($consultation->status === 'pending')
                                    <span class="badge bg-warning">Belum Dijawab</span>
                                @elseif($consultation->status === 'answered')
                                    <span class="badge bg-success">Sudah Ada Jawaban</span>
                                @elseif($consultation->status === 'closed')
                                    <span class="badge bg-info">Ditutup</span>
                                @elseif($consultation->status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </p>
                            <p class="mb-0 text-muted">
                                Anda dapat melihat riwayat pesan dengan ustadz di halaman riwayat konsultasi
                            </p>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
