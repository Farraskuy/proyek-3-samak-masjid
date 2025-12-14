@extends('admin.layout')

@section('title', 'Kontrol Kegiatan - ' . $event->event_name)

@push('styles')
    <style>
        .control-card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .event-header {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #2563eb;
        }

        .event-poster-container {
            width: 160px;
            height: 160px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            flex-shrink: 0;
        }

        .event-poster-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-poster-placeholder {
            width: 100%;
            height: 100%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #9ca3af;
        }

        .toggle-switch {
            position: relative;
            width: 56px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d5db;
            transition: 0.3s;
            border-radius: 28px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
        }

        input:checked+.toggle-slider {
            background-color: #16a34a;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(28px);
        }

        .status-badge-lg {
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 600;
        }

        .stat-box {
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .action-btn-lg {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .action-btn-lg:hover {
            transform: translateY(-1px);
        }

        .control-item {
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .control-item:last-child {
            border-bottom: none;
        }

        .section-title {
            font-weight: 700;
            font-size: 15px;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: #6b7280;
        }
    </style>
@endpush

@section('content')
    <section class="p-4 p-lg-5">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.pj.index') }}" class="text-decoration-none">
                        <i class="fa-regular fa-clipboard-user me-1"></i>Dashboard PJ
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($event->event_name, 40) }}</li>
            </ol>
        </nav>

        {{-- Event Header Card --}}
        <div class="event-header mb-4">
            <div class="d-flex gap-4 flex-wrap flex-lg-nowrap">
                {{-- Poster --}}
                <div class="event-poster-container">
                    @if ($event->poster)
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->event_name }}">
                    @else
                        <div class="event-poster-placeholder">
                            <i class="fa-duotone fa-calendar-star"></i>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-grow-1">
                    @php
                        $now = now();
                        $startTime = \Carbon\Carbon::parse($event->start_time);
                        $endTime = $event->end_time ? \Carbon\Carbon::parse($event->end_time) : null;

                        if ($endTime && $now > $endTime) {
                            $status = 'ended';
                            $statusLabel = 'Selesai';
                            $statusClass = 'bg-secondary';
                        } elseif ($now >= $startTime) {
                            $status = 'ongoing';
                            $statusLabel = 'Sedang Berlangsung';
                            $statusClass = 'bg-success';
                        } else {
                            $status = 'upcoming';
                            $statusLabel = 'Akan Datang';
                            $statusClass = 'bg-primary';
                        }
                    @endphp

                    <div class="d-flex gap-2 align-items-center mb-2">
                        <span class="status-badge-lg {{ $statusClass }}">
                            <i class="fa-solid fa-circle fa-xs me-1"></i>{{ $statusLabel }}
                        </span>
                    </div>

                    <h3 class="fw-bold mb-2 text-dark">{{ $event->event_name }}</h3>

                    @if ($event->theme)
                        <p class="text-muted mb-3">{{ $event->theme }}</p>
                    @endif

                    <div class="row g-3">
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-regular fa-calendar fa-lg text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Mulai</small>
                                    <span class="fw-semibold">{{ $startTime->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        @if ($endTime)
                            <div class="col-auto">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-regular fa-calendar-check fa-lg text-success"></i>
                                    <div>
                                        <small class="text-muted d-block">Selesai</small>
                                        <span class="fw-semibold">{{ $endTime->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-auto">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-regular fa-location-dot fa-lg text-danger"></i>
                                <div>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <span class="fw-semibold">{{ $event->location ?? 'Belum ditentukan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Control Panel --}}
            <div class="col-lg-8">
                <div class="card control-card h-100">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <i class="fa-duotone fa-sliders"></i>Panel Kontrol
                        </div>

                        {{-- Form Pendaftaran Control --}}
                        @if ($event->has_registration_form)
                            <div class="control-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        <i class="fa-regular fa-clipboard-list text-success me-2"></i>Form Pendaftaran
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Buka/tutup form pendaftaran untuk kegiatan ini
                                    </p>
                                </div>
                                <form action="{{ route('admin.pj.toggle-registration', $event->event_id) }}" method="POST"
                                    class="d-flex align-items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <span
                                        class="badge {{ $event->registration_enabled ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                        {{ $event->registration_enabled ? 'DIBUKA' : 'DITUTUP' }}
                                    </span>
                                    <label class="toggle-switch mb-0">
                                        <input type="checkbox" name="registration_enabled" value="1"
                                            {{ $event->registration_enabled ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </form>
                            </div>
                        @else
                            <div class="control-item d-flex justify-content-between align-items-center opacity-50">
                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        <i class="fa-regular fa-clipboard-list me-2"></i>Form Pendaftaran
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        <i class="fa-solid fa-info-circle me-1"></i>Kegiatan ini tidak memiliki form
                                        pendaftaran
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Kuesioner Control --}}
                        @if ($event->has_closing_form)
                            <div class="control-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        <i class="fa-regular fa-ballot-check text-info me-2"></i>Kuesioner / Form Penutupan
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Buka/tutup kuesioner untuk feedback peserta
                                    </p>
                                </div>
                                <form action="{{ route('admin.pj.toggle-questionnaire', $event->event_id) }}"
                                    method="POST" class="d-flex align-items-center gap-3">
                                    @csrf
                                    @method('PATCH')
                                    <span
                                        class="badge {{ $event->questionnaire_enabled ? 'bg-success' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                        {{ $event->questionnaire_enabled ? 'DIBUKA' : 'DITUTUP' }}
                                    </span>
                                    <label class="toggle-switch mb-0">
                                        <input type="checkbox" name="questionnaire_enabled" value="1"
                                            {{ $event->questionnaire_enabled ? 'checked' : '' }}
                                            onchange="this.form.submit()">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </form>
                            </div>
                        @else
                            <div class="control-item d-flex justify-content-between align-items-center opacity-50">
                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        <i class="fa-regular fa-ballot-check me-2"></i>Kuesioner / Form Penutupan
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        <i class="fa-solid fa-info-circle me-1"></i>Kegiatan ini tidak memiliki form
                                        kuesioner
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Manual Event Control --}}
                        <div class="control-item">
                            <div class="section-title mt-2">
                                <i class="fa-regular fa-play-pause"></i>Kontrol Kegiatan Manual
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($status !== 'ended')
                                    @if ($status === 'upcoming')
                                        <button type="button" class="btn btn-success action-btn-lg"
                                            onclick="showConfirmModal({
                                                action: '{{ route('admin.pj.start-event', $event->event_id) }}',
                                                method: 'PATCH',
                                                type: 'start',
                                                title: 'Mulai Kegiatan',
                                                message: 'Apakah Anda yakin ingin memulai kegiatan sekarang?',
                                                buttonText: 'Ya, Mulai'
                                            })">
                                            <i class="fa-solid fa-play me-2"></i>Mulai Kegiatan Sekarang
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-outline-danger action-btn-lg"
                                        onclick="showConfirmModal({
                                            action: '{{ route('admin.pj.end-event', $event->event_id) }}',
                                            method: 'PATCH',
                                            type: 'end',
                                            title: 'Akhiri Kegiatan',
                                            message: 'Apakah Anda yakin ingin mengakhiri kegiatan sekarang?',
                                            buttonText: 'Ya, Akhiri'
                                        })">
                                        <i class="fa-solid fa-stop me-2"></i>Akhiri Kegiatan
                                    </button>
                                @else
                                    <div class="alert alert-secondary mb-0 w-100">
                                        <i class="fa-solid fa-check-circle me-2"></i>Kegiatan ini sudah selesai
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik & Quick Actions --}}
            <div class="col-lg-4">
                <div class="card control-card mb-4">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <i class="fa-duotone fa-chart-simple"></i>Statistik
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-box">
                                    <i class="fa-duotone fa-users text-primary fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $registrantCount }}</h3>
                                    <small class="text-muted">Pendaftar</small>
                                </div>
                            </div>
                            @if ($event->registrationForm)
                                <div class="col-6">
                                    <div class="stat-box">
                                        <i class="fa-duotone fa-user-check text-success fa-2x mb-2"></i>
                                        <h3 class="fw-bold mb-0">
                                            {{ $event->registrationForm->responses->where('is_verified', true)->count() }}
                                        </h3>
                                        <small class="text-muted">Terverifikasi</small>
                                    </div>
                                </div>
                            @endif
                            @if ($event->closingForm)
                                @php
                                    $closingResponseCount = $event->closingForm->responses->count();
                                @endphp
                                <div class="col-12">
                                    <div class="stat-box">
                                        <i class="fa-duotone fa-clipboard-check text-info fa-2x mb-2"></i>
                                        <h3 class="fw-bold mb-0">{{ $closingResponseCount }}</h3>
                                        <small class="text-muted">Responden Kuesioner</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card control-card">
                    <div class="card-body p-4">
                        <div class="section-title">
                            <i class="fa-duotone fa-bolt"></i>Aksi Cepat
                        </div>

                        <div class="d-grid gap-2">
                            @if ($event->has_registration_form && $registrantCount > 0)
                                <a href="{{ route('admin.pj.registrants', $event->event_id) }}"
                                    class="btn btn-outline-primary action-btn-lg">
                                    <i class="fa-solid fa-users me-2"></i>Lihat Daftar Peserta
                                </a>
                            @endif

                            @if ($event->closingForm && $event->closingForm->responses->count() > 0)
                                <a href="{{ route('admin.pj.closing-responses', $event->event_id) }}"
                                    class="btn btn-outline-info action-btn-lg">
                                    <i class="fa-solid fa-chart-bar me-2"></i>Lihat Responden Kuesioner
                                </a>
                            @endif

                            @if ($event->registrationForm)
                                <a href="{{ route('form.show', $event->registrationForm->slug) }}" target="_blank"
                                    class="btn btn-outline-success action-btn-lg">
                                    <i class="fa-solid fa-external-link me-2"></i>Lihat Form Pendaftaran
                                </a>
                            @endif

                            @if ($event->closingForm)
                                <a href="{{ route('form.show', $event->closingForm->slug) }}" target="_blank"
                                    class="btn btn-outline-secondary action-btn-lg">
                                    <i class="fa-solid fa-external-link me-2"></i>Lihat Form Kuesioner
                                </a>
                            @endif

                            <a href="{{ route('admin.pj.index') }}" class="btn btn-light action-btn-lg border">
                                <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
