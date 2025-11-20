@extends('layouts.app')

@section('title', 'Buat Konsultasi Baru')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-pen-fancy"></i> Buat Konsultasi Baru
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

                        <form action="{{ route('client.consultations.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="question_subject" class="form-label fw-bold">
                                    Subjek Pertanyaan <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('question_subject') is-invalid @enderror"
                                    id="question_subject" name="question_subject" placeholder="Contoh: Cara Berpuasa yang Benar"
                                    value="{{ old('question_subject') }}" required>
                                @error('question_subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Jelaskan secara singkat topik pertanyaan Anda</small>
                            </div>

                            <div class="mb-4">
                                <label for="question_text" class="form-label fw-bold">
                                    Pertanyaan Anda <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('question_text') is-invalid @enderror" id="question_text"
                                    name="question_text" rows="8" placeholder="Tuliskan pertanyaan Anda secara detail dan jelas..."
                                    required>{{ old('question_text') }}</textarea>
                                @error('question_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 10 karakter. Semakin detail pertanyaan, semakin baik jawaban yang diberikan.</small>
                            </div>

                            <div class="mb-4 form-check form-switch">
                                <input type="hidden" name="is_anonymous" value="0">
                                <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous"
                                    value="1" {{ old('is_anonymous') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_anonymous">
                                    Kirim sebagai pertanyaan anonim
                                </label>
                                <small class="d-block text-muted">Jika dicentang, nama Anda tidak akan ditampilkan di halaman konsultasi publik</small>
                            </div>

                            <div class="alert alert-info mb-4" role="alert">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informasi Penting:</strong>
                                <ul class="mb-0 small mt-2">
                                    <li>Pertanyaan akan dijawab oleh ustadz kami dalam waktu 1-3 hari kerja</li>
                                    <li>Pertanyaan yang bersifat negatif atau tidak sopan tidak akan dijawab</li>
                                    <li>Anda dapat melihat riwayat konsultasi Anda di halaman "Riwayat Konsultasi"</li>
                                </ul>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('client.consultations.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Kirim Pertanyaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
