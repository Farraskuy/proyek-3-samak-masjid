@extends('client.layout')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Header -->
                <div class="mb-4" data-aos="fade-down" data-aos-duration="600">
                    <a href="/jadwal-kegiatan" class="btn btn-outline-primary rounded-pill px-4 mb-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Jadwal Kegiatan
                    </a>

                    <div class="text-center mb-5">
                        <div class="d-inline-block position-relative">
                            <h1 class="display-5 fw-bold mb-2" style="color: #175C9E;">
                                <i class="fas fa-history me-3" style="color: #F6C948;"></i>
                                Histori Pendaftaran
                                <i class="fas fa-history ms-3" style="color: #F6C948;"></i>
                            </h1>
                            <div class="position-absolute bottom-0 start-50 translate-middle-x"
                                style="width: 60%; height: 4px; background: linear-gradient(90deg, transparent, #F6C948, transparent); border-radius: 10px;">
                            </div>
                        </div>
                        <p class="text-muted mt-3 mb-0">Daftar kegiatan yang pernah Anda ikuti</p>
                    </div>
                </div>

                @if ($registrations->isEmpty())
                    <!-- Empty State -->
                    <div class="text-center py-5" data-aos="fade-up">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list fa-5x text-muted opacity-50"></i>
                        </div>
                        <h4 class="text-muted">Belum Ada Riwayat Pendaftaran</h4>
                        <p class="text-muted mb-4">Anda belum mendaftar ke kegiatan apapun.</p>
                        <a href="/jadwal-kegiatan" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-calendar-alt me-2"></i>Lihat Jadwal Kegiatan
                        </a>
                    </div>
                @else
                    <!-- Registration List -->
                    <div class="row g-4">
                        @foreach ($registrations as $registration)
                            <div class="col-12" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <div class="d-flex align-items-start">
                                                    @if ($registration->event->poster)
                                                        <img src="{{ asset('storage/' . $registration->event->poster) }}" 
                                                            alt="{{ $registration->event->event_name }}"
                                                            class="rounded-3 me-3"
                                                            style="width: 80px; height: 80px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-3 me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 80px; height: 80px; background: linear-gradient(135deg, #175C9E, #1a4d7a);">
                                                            <i class="fas fa-calendar-alt text-white fa-2x"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h5 class="fw-bold mb-1" style="color: #175C9E;">
                                                            {{ $registration->event->event_name }}
                                                        </h5>
                                                        <p class="text-muted mb-2">
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $registration->event->location }}
                                                        </p>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                                <i class="fas fa-calendar me-1"></i>
                                                                {{ date('d M Y', strtotime($registration->event->start_time)) }}
                                                            </span>
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                                <i class="fas fa-clock me-1"></i>
                                                                Didaftarkan {{ $registration->created_at->diffForHumans() }}
                                                            </span>
                                                            @if (now()->gt($registration->event->end_time))
                                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                                    <i class="fas fa-check-circle me-1"></i>Selesai
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning bg-opacity-25 text-dark">
                                                                    <i class="fas fa-hourglass-half me-1"></i>Akan Datang
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                                <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                                                    {{-- Tombol Lihat Detail --}}
                                                    <a href="{{ route('jadwal.detail', $registration->event->event_id) }}" 
                                                        class="btn btn-outline-primary rounded-pill px-3">
                                                        <i class="fas fa-eye me-1"></i>Detail
                                                    </a>
                                                    
                                                    {{-- Tombol Kuesioner (hanya muncul jika event selesai & ada closing form) --}}
                                                    @if (now()->gt($registration->event->end_time) && $registration->event->has_closing_form && $registration->event->closingForm)
                                                        @if ($registration->event->questionnaire_enabled ?? true)
                                                            <a href="{{ route('form.fill', $registration->event->closingForm->slug) }}" 
                                                                class="btn btn-success rounded-pill px-3">
                                                                <i class="fas fa-clipboard-check me-1"></i>Isi Kuesioner
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-secondary rounded-pill px-3" disabled title="Kuesioner belum dibuka">
                                                                <i class="fas fa-lock me-1"></i>Kuesioner
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(23, 92, 158, 0.15) !important;
        }
        
        @media (max-width: 768px) {
            .display-5 {
                font-size: 1.8rem !important;
            }
        }
    </style>
@endpush
