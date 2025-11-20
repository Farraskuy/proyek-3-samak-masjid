@extends('layouts.app')

@section('title', 'Buat Konsultasi Baru')

@push('styles')
    <style>
        .section-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card-modern {
            border: 0 !important;
            background: #fff;
            border-radius: 1rem !important;
        }

        .input-lg {
            padding: .85rem 1rem !important;
            font-size: .95rem !important;
            border-radius: 0.75rem !important;
        }

        .btn-submit {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border: none;
            padding: .85rem 2rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.02) 100%);
            border-left: 4px solid #0d6efd;
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .header-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .header-section a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            background: #f8f9fa;
            color: #666;
            transition: all 0.3s ease;
        }

        .header-section a:hover {
            background: #e9ecef;
            color: #333;
        }

        .header-section h2 {
            margin: 0;
            font-weight: 700;
            color: #1a1a1a;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .form-check-label {
            cursor: pointer;
            user-select: none;
        }

        .section-title {
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <section class="p-3 section-wrapper py-5">

        {{-- Header --}}
        <div class="header-section mb-4">
            <a href="{{ route('client.consultations.index') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left fa-lg"></i>
            </a>
            <div>
                <h2>Buat Konsultasi Baru</h2>
                <small class="text-muted">Ajukan pertanyaan Anda kepada ustadz kami</small>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <div class="fw-semibold mb-2">
                    <i class="fas fa-exclamation-circle me-2"></i>Terjadi Kesalahan
                </div>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('client.consultations.store') }}" method="POST">
            @csrf

            <div class="row g-4">

                {{-- Main Content Column --}}
                <div class="col-lg-8">

                    {{-- Subjek Section --}}
                    <div class="card-modern shadow-sm p-4 mb-4">
                        <label for="question_subject" class="form-label">
                            <i class="fas fa-heading me-2 text-primary"></i>Subjek Pertanyaan
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control input-lg @error('question_subject') is-invalid @enderror"
                            id="question_subject"
                            name="question_subject"
                            placeholder="Contoh: Bagaimana cara ibadah yang benar?"
                            value="{{ old('question_subject') }}"
                            required>
                        @error('question_subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-lightbulb me-1"></i>Jelaskan secara singkat topik pertanyaan Anda
                        </small>
                    </div>

                    {{-- Pertanyaan Section --}}
                    <div class="card-modern shadow-sm p-4 mb-4">
                        <label for="question_text" class="form-label">
                            <i class="fas fa-pen me-2 text-primary"></i>Detail Pertanyaan
                            <span class="text-danger">*</span>
                        </label>
                        <textarea
                            class="form-control input-lg @error('question_text') is-invalid @enderror"
                            id="question_text"
                            name="question_text"
                            rows="8"
                            placeholder="Tuliskan pertanyaan Anda secara detail dan jelas..."
                            required>{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>Minimal 10 karakter. Semakin detail pertanyaan, semakin baik jawaban yang diberikan
                        </small>
                    </div>

                    {{-- Info Box --}}
                    <div class="info-box mb-4">
                        <div class="fw-semibold text-primary mb-2">
                            <i class="fas fa-info-circle me-2"></i>Informasi Penting
                        </div>
                        <ul class="mb-0 small">
                            <li class="mb-1">✓ Pertanyaan akan dijawab oleh ustadz kami dalam waktu 1-3 hari kerja</li>
                            <li class="mb-1">✓ Pertanyaan yang bersifat sopan dan konstruktif akan dijawab</li>
                            <li>✓ Anda dapat melihat riwayat konsultasi Anda kapan saja</li>
                        </ul>
                    </div>

                </div>

                {{-- Sidebar Column --}}
                <div class="col-lg-4">

                    {{-- Pengaturan Section --}}
                    <div class="card-modern shadow-sm p-4 mb-4">
                        <div class="section-title">Pengaturan</div>

                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="is_anonymous" value="0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="is_anonymous"
                                name="is_anonymous"
                                value="1"
                                {{ old('is_anonymous') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_anonymous">
                                <span class="fw-medium">Kirim Anonim</span>
                                <small class="d-block text-muted">Nama Anda tidak akan ditampilkan</small>
                            </label>
                        </div>
                    </div>

                    {{-- Buttons Section --}}
                    <div class="card-modern shadow-sm p-4">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pertanyaan
                            </button>
                            <a href="{{ route('client.consultations.index') }}" class="btn btn-light">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>

                        <div class="alert alert-light mt-3 mb-0" role="alert">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Privasi Anda terlindungi. Data pertanyaan disimpan dengan aman.
                            </small>
                        </div>
                    </div>

                </div>

            </div>

        </form>

    </section>
@endsection
