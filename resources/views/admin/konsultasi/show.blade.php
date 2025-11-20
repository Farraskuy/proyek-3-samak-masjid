@extends('layouts.admin')

@section('title', 'Detail Konsultasi')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Detail Konsultasi</h2>
                    <a href="{{ route('konsultasi') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Question Section --}}
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle"></i> Pertanyaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="form-label fw-bold">Subjek Pertanyaan</p>
                            <p class="form-control-plaintext">{{ $consultation->question_subject }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="form-label fw-bold">Isi Pertanyaan</p>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($consultation->question_text)) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="form-label fw-bold">Dari</p>
                                <p class="form-control-plaintext">
                                    @if ($consultation->is_anonymous)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-user-secret"></i> Anonim
                                        </span>
                                    @else
                                        {{ $consultation->question_from }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="form-label fw-bold">Tanggal Ditanya</p>
                                <p class="form-control-plaintext">
                                    {{ $consultation->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="form-label fw-bold">Status</p>
                            <p class="form-control-plaintext">
                                @if ($consultation->status === 'pending')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-clock"></i> Menunggu Jawaban
                                    </span>
                                @elseif ($consultation->status === 'in_progress')
                                    <span class="badge bg-info">
                                        <i class="fas fa-hourglass-half"></i> Sedang Diproses
                                    </span>
                                @elseif ($consultation->status === 'answered')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Sudah Dijawab
                                    </span>
                                @elseif ($consultation->status === 'closed')
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-lock"></i> Ditutup
                                    </span>
                                @elseif ($consultation->status === 'rejected')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban"></i> Ditolak
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Sidebar --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-cog"></i> Aksi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($consultation->status === 'pending')
                            <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#answerModal">
                                <i class="fas fa-reply"></i> Beri Jawaban
                            </button>
                            <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle"></i> Tolak
                            </button>
                            <button class="btn btn-info w-100" data-bs-toggle="modal"
                                data-bs-target="#statusModal">
                                <i class="fas fa-edit"></i> Ubah Status
                            </button>
                        @elseif ($consultation->status === 'in_progress')
                            <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#answerModal">
                                <i class="fas fa-reply"></i> Beri Jawaban
                            </button>
                            <button class="btn btn-warning w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle"></i> Tolak
                            </button>
                        @elseif ($consultation->status === 'answered')
                            <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#closeModal">
                                <i class="fas fa-check"></i> Tandai Selesai
                            </button>
                            <button class="btn btn-warning w-100" data-bs-toggle="modal"
                                data-bs-target="#editAnswerModal">
                                <i class="fas fa-edit"></i> Edit Jawaban
                            </button>
                        @elseif ($consultation->status === 'closed')
                            <p class="text-muted small">Konsultasi telah ditutup</p>
                        @elseif ($consultation->status === 'rejected')
                            <button class="btn btn-primary w-100" data-bs-toggle="modal"
                                data-bs-target="#answerModal">
                                <i class="fas fa-reply"></i> Ubah Jawaban
                            </button>
                        @endif

                        <hr>
                        <form action="{{ route('konsultasi.destroy', $consultation->consultation_id) }}"
                            method="POST" class="d-inline-block w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus konsultasi ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Informasi
                        </h5>
                    </div>
                    <div class="card-body small">
                        <div class="mb-2">
                            <strong>Input oleh:</strong>
                            <br>
                            {{ $consultation->inputter ? $consultation->inputter->name : '-' }}
                        </div>
                        @if ($consultation->answerer)
                            <div class="mb-2">
                                <strong>Dijawab oleh:</strong>
                                <br>
                                {{ $consultation->answerer->name }}
                            </div>
                        @endif
                        @if ($consultation->answered_at)
                            <div class="mb-2">
                                <strong>Waktu Dijawab:</strong>
                                <br>
                                {{ $consultation->answered_at->format('d M Y H:i') }}
                            </div>
                        @endif
                        @if ($consultation->closed_at)
                            <div>
                                <strong>Waktu Ditutup:</strong>
                                <br>
                                {{ $consultation->closed_at->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Answer Section --}}
        @if ($consultation->answer_text)
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle"></i> Jawaban
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($consultation->answer_text)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Rejection Reason Section --}}
        @if ($consultation->rejection_reason)
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-ban"></i> Alasan Penolakan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($consultation->rejection_reason)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Conclusion Section --}}
        @if ($consultation->conclusion)
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bookmark"></i> Kesimpulan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($consultation->conclusion)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Answer Modal --}}
    <div class="modal fade" id="answerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-reply"></i> Beri Jawaban
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('konsultasi.answer', $consultation->consultation_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jawaban <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('answer_text') is-invalid @enderror" name="answer_text"
                                rows="6" required placeholder="Masukkan jawaban atas pertanyaan...">{{ old('answer_text', $consultation->answer_text) }}</textarea>
                            @error('answer_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle"></i> Tolak Konsultasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('konsultasi.reject', $consultation->consultation_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('rejection_reason') is-invalid @enderror"
                                name="rejection_reason" rows="6" required placeholder="Jelaskan alasan penolakan...">{{ old('rejection_reason', $consultation->rejection_reason) }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-ban"></i> Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Close Modal --}}
    <div class="modal fade" id="closeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check"></i> Tandai Selesai
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('konsultasi.close', $consultation->consultation_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kesimpulan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('conclusion') is-invalid @enderror" name="conclusion"
                                rows="6" required placeholder="Tuliskan kesimpulan konsultasi...">{{ old('conclusion', $consultation->conclusion) }}</textarea>
                            @error('conclusion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Answer Modal --}}
    <div class="modal fade" id="editAnswerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Edit Jawaban
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('konsultasi.answer', $consultation->consultation_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jawaban <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('answer_text') is-invalid @enderror" name="answer_text"
                                rows="6" required>{{ old('answer_text', $consultation->answer_text) }}</textarea>
                            @error('answer_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Status Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i> Ubah Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('konsultasi.status', $consultation->consultation_id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Baru <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="pending" {{ $consultation->status === 'pending' ? 'selected' : '' }}>
                                    Menunggu Jawaban
                                </option>
                                <option value="in_progress" {{ $consultation->status === 'in_progress' ? 'selected' : '' }}>
                                    Sedang Diproses
                                </option>
                                <option value="answered" {{ $consultation->status === 'answered' ? 'selected' : '' }}>
                                    Sudah Dijawab
                                </option>
                                <option value="closed" {{ $consultation->status === 'closed' ? 'selected' : '' }}>
                                    Ditutup
                                </option>
                                <option value="rejected" {{ $consultation->status === 'rejected' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

