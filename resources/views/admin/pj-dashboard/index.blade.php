@extends('admin.layout')

@section('title', 'Dashboard PJ')

@push('styles')
    <style>
        .pj-event-card {
            transition: all 0.2s ease;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .pj-event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #d1d5db;
        }

        .status-badge {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
        }

        .status-upcoming {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        .status-ongoing {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-ended {
            background-color: #f3f4f6;
            color: #6b7280;
        }

        .control-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .control-open {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .control-closed {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .event-poster {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }

        .event-poster-placeholder {
            width: 100%;
            height: 140px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 2.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-card-icon.primary {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .stat-card-icon.success {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .stat-card-icon.warning {
            background-color: #fef3c7;
            color: #d97706;
        }

        .quick-action-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .quick-action-btn:hover {
            transform: translateY(-1px);
        }

        .section-header {
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <section class="p-4 p-lg-5">
        {{-- Header --}}
        <div class="mb-4">
            <h1 class="fw-bold m-0 text-dark">
                <i class="fa-duotone fa-clipboard-user me-2 text-primary"></i>Dashboard Penanggung Jawab
            </h1>
            <p class="text-muted mb-0 mt-1">
                Kelola kegiatan yang menjadi tanggung jawab Anda
            </p>
        </div>

        {{-- Statistik Ringkasan --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Total Kegiatan</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $events->count() }}</h2>
                        </div>
                        <div class="stat-card-icon primary">
                            <i class="fa-duotone fa-calendar-days"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Sedang Berlangsung</p>
                            <h2 class="mb-0 fw-bold text-dark">
                                {{ $events->filter(function ($e) {
                                        return $e->start_time <= now() && ($e->end_time >= now() || !$e->end_time);
                                    })->count() }}
                            </h2>
                        </div>
                        <div class="stat-card-icon success">
                            <i class="fa-duotone fa-play-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 text-muted small">Total Pendaftar</p>
                            <h2 class="mb-0 fw-bold text-dark">
                                {{ $events->sum(function ($e) {
                                        return $e->registrationForm ? $e->registrationForm->responses->count() : 0;
                                    }) }}
                            </h2>
                        </div>
                        <div class="stat-card-icon warning">
                            <i class="fa-duotone fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Kegiatan --}}
        <div class="rounded-3 bg-white p-4 border">
            <div class="section-header">
                <h5 class="fw-bold mb-0">
                    <i class="fa-duotone fa-list-check text-primary me-2"></i>Kegiatan Saya
                </h5>
            </div>

            @if ($events->isEmpty())
                <div class="text-center py-5">
                    <i class="fa-duotone fa-calendar-xmark fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum Ada Kegiatan</h5>
                    <p class="text-secondary">Anda belum ditugaskan sebagai PJ untuk kegiatan apapun.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($events as $event)
                        @php
                            $now = now();
                            $startTime = \Carbon\Carbon::parse($event->start_time);
                            $endTime = $event->end_time ? \Carbon\Carbon::parse($event->end_time) : null;

                            if ($endTime && $now > $endTime) {
                                $status = 'ended';
                                $statusLabel = 'Selesai';
                            } elseif ($now >= $startTime) {
                                $status = 'ongoing';
                                $statusLabel = 'Berlangsung';
                            } else {
                                $status = 'upcoming';
                                $statusLabel = 'Akan Datang';
                            }

                            $registrantCount = $event->registrationForm
                                ? $event->registrationForm->responses->count()
                                : 0;
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card pj-event-card h-100">
                                {{-- Event Poster --}}
                                @if ($event->poster)
                                    <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->event_name }}"
                                        class="event-poster">
                                @else
                                    <div class="event-poster-placeholder">
                                        <i class="fa-duotone fa-calendar-star"></i>
                                    </div>
                                @endif

                                <div class="card-body">
                                    {{-- Status Badge --}}
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="status-badge status-{{ $status }}">
                                            <i class="fa-solid fa-circle fa-xs me-1"></i>{{ $statusLabel }}
                                        </span>
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="fa-solid fa-users me-1"></i>{{ $registrantCount }}
                                        </span>
                                    </div>

                                    {{-- Event Info --}}
                                    <h6 class="fw-bold mb-1">{{ $event->event_name }}</h6>
                                    @if ($event->theme)
                                        <p class="text-muted small mb-2">{{ Str::limit($event->theme, 60) }}</p>
                                    @endif

                                    <div class="mb-3">
                                        <p class="mb-1 small">
                                            <i class="fa-regular fa-calendar text-primary me-2"></i>
                                            {{ $startTime->translatedFormat('d M Y, H:i') }}
                                        </p>
                                        <p class="mb-0 small">
                                            <i class="fa-regular fa-location-dot text-danger me-2"></i>
                                            {{ $event->location ?? 'Lokasi belum ditentukan' }}
                                        </p>
                                    </div>

                                    {{-- Control Status --}}
                                    <div class="d-flex gap-2 mb-3 flex-wrap">
                                        @if ($event->has_registration_form)
                                            <span
                                                class="control-badge {{ $event->registration_enabled ? 'control-open' : 'control-closed' }}">
                                                <i
                                                    class="fa-solid {{ $event->registration_enabled ? 'fa-door-open' : 'fa-door-closed' }} me-1"></i>
                                                Pendaftaran
                                            </span>
                                        @endif

                                        @if ($event->has_closing_form)
                                            <span
                                                class="control-badge {{ $event->questionnaire_enabled ? 'control-open' : 'control-closed' }}">
                                                <i
                                                    class="fa-solid {{ $event->questionnaire_enabled ? 'fa-door-open' : 'fa-door-closed' }} me-1"></i>
                                                Kuesioner
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('admin.pj.show', $event->event_id) }}"
                                            class="btn btn-primary quick-action-btn flex-grow-1">
                                            <i class="fa-solid fa-sliders me-1"></i>Detail & Kontrol
                                        </a>
                                        @if ($event->has_registration_form && $registrantCount > 0)
                                            <a href="{{ route('admin.pj.registrants', $event->event_id) }}"
                                                class="btn btn-outline-success quick-action-btn">
                                                <i class="fa-solid fa-users me-1"></i>Peserta
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
