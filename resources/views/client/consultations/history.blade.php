@extends('client.layout')

@section('title', 'Riwayat Konsultasi')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Riwayat Konsultasi</h2>
            <a href="{{ route('client.consultations.index') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Buat Konsultasi Baru
            </a>
        </div>

        @if ($consultations->isEmpty())
            <div class="text-center py-5">
                <img src="{{ asset('assets/images/empty-state.svg') }}" alt="Empty" class="mb-3"
                    style="max-width: 200px;">
                <p class="text-muted">Belum ada riwayat konsultasi.</p>
            </div>
        @else
            <div class="row">
                @foreach ($consultations as $consultation)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span
                                        class="badge bg-{{ $consultation->status == 'active' ? 'success' : ($consultation->status == 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($consultation->status) }}
                                    </span>
                                    <small class="text-muted">{{ $consultation->created_at->format('d M Y H:i') }}</small>
                                </div>
                                <h5 class="card-title fw-bold">{{ $consultation->question_subject }}</h5>
                                <p class="card-text text-muted text-truncate">{{ $consultation->question_text }}</p>
                                <a href="{{ route('client.consultations.show', $consultation->id) }}"
                                    class="btn btn-outline-primary btn-sm stretched-link">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $consultations->links() }}
            </div>
        @endif
    </div>
@endsection
