@extends('client.layout')

@section('content')
    <!-- MAIN CONTENT -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Back Link -->
                <a href="/jadwal-kegiatan"
                    class="text-decoration-none d-inline-flex align-items-center text-primary mb-4 back-link">
                    <i class="fas fa-arrow-left me-2"></i>
                    <span>Kembali ke Jadwal Kegiatan</span>
                </a>

                <!-- Event Title - Direct, No "Detail Kegiatan" -->
                <h1 class="fw-bold mb-3 event-title" style="color: #175C9E;">
                    {{ $event->event_name }}
                </h1>
                <p class="text-muted mb-4">Informasi lengkap mengenai kegiatan ini</p>

                <!-- Main Card -->
                <div class="card border rounded-4 overflow-hidden main-card">

                    <!-- Poster -->
                    @if ($event->poster)
                        <div class="bg-light p-4">
                            <div class="card-thumbnail-wrapper rounded-3"
                                style="max-width: 100%; height: auto; min-height: 300px; background-color: transparent;">
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster {{ $event->event_name }}"
                                    class="img-fluid rounded-3" style="max-height: 600px; object-fit: contain;">
                                <i class="fas fa-image fallback-icon" style="font-size: 5rem;"></i>
                            </div>
                            <div class="mt-3">
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                    <i class="fas fa-image me-1"></i>Poster Event
                                </span>
                            </div>
                        </div>
                    @endif

                    <div class="card-body p-4 p-md-5">

                        <!-- Tema/Deskripsi -->
                        @if ($event->theme)
                            <div class="mb-4">
                                <div class="card border rounded-4" style="background: #f8fafc;">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <div class="icon-circle bg-white border p-3">
                                                    <i class="fas fa-quote-left" style="color: #175C9E;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-2" style="color: #175C9E;">
                                                    <i class="fas fa-lightbulb me-2"></i>Tema & Deskripsi
                                                </h5>
                                                <div class="theme-content" style="color: #334155; line-height: 1.8;">
                                                    {{ $event->theme }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Info Grid -->
                        <div class="row g-3 mb-4">
                            <!-- Tanggal Mulai -->
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 h-100 border" style="background: #e3f2fd;">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle me-3" style="width: 44px; height: 44px; background: white;">
                                            <i class="fas fa-calendar-check" style="color: #175C9E;"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small fw-semibold text-uppercase">Waktu Mulai</p>
                                            <h6 class="fw-bold mb-0" style="color: #175C9E;">
                                                {{ date('l, d F Y', strtotime($event->start_time)) }}
                                            </h6>
                                            <span style="color: #175C9E; font-size: 0.9rem;">
                                                <i
                                                    class="fas fa-clock me-1"></i>{{ date('H:i', strtotime($event->start_time)) }}
                                                WIB
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-md-6">
                                <div class="info-card p-3 rounded-3 h-100 border" style="background: #f3e5f5;">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle me-3" style="width: 44px; height: 44px; background: white;">
                                            <i class="fas fa-calendar-times" style="color: #7b1fa2;"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small fw-semibold text-uppercase">Waktu Selesai</p>
                                            <h6 class="fw-bold mb-0" style="color: #7b1fa2;">
                                                {{ date('l, d F Y', strtotime($event->end_time)) }}
                                            </h6>
                                            <span style="color: #7b1fa2; font-size: 0.9rem;">
                                                <i
                                                    class="fas fa-clock me-1"></i>{{ date('H:i', strtotime($event->end_time)) }}
                                                WIB
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="col-12">
                                <div class="info-card p-3 rounded-3 border" style="background: #fff3e0;">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle me-3" style="width: 44px; height: 44px; background: white;">
                                            <i class="fas fa-map-marker-alt" style="color: #e65100;"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-0 small fw-semibold text-uppercase">Lokasi Kegiatan</p>
                                            <h6 class="fw-bold mb-0" style="color: #e65100;">
                                                <i class="fas fa-location-dot me-1"></i>{{ $event->location }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pembicara / Tamu Undangan -->
                        @if ($event->is_have_tamu_undangan && $event->tamuUndangan->count() > 0)
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3" style="color: #175C9E;">
                                    <i class="fas fa-microphone me-2"></i>Pembicara & Tamu Undangan
                                </h5>
                                <div class="row g-2">
                                    @foreach ($event->tamuUndangan as $tamu)
                                        <div class="col-md-6">
                                            <div class="card border rounded-3">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-circle me-3"
                                                            style="width: 44px; height: 44px; background: linear-gradient(135deg, #175C9E, #1a4d7a);">
                                                            <i class="fas fa-user-tie text-white"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold mb-0" style="color: #175C9E;">
                                                                {{ $tamu->nama_tamu }}
                                                            </h6>
                                                            <span class="badge bg-light text-primary small">Pembicara</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Registration Form Section --}}
                        @if ($event->has_registration_form && $event->registrationForm)
                            <div class="mb-4" id="registration-section">
                                <h5 class="fw-bold mb-3" style="color: #175C9E;">
                                    <i class="fas fa-clipboard-list me-2"></i>Formulir Pendaftaran
                                </h5>

                                @if ($eventEnded)
                                    <div class="alert alert-secondary rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-lock fa-lg me-3 text-secondary"></i>
                                            <div>
                                                <strong>Pendaftaran Ditutup</strong>
                                                <p class="mb-0 small">Kegiatan ini telah selesai. Formulir tidak tersedia.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($hasRegistered)
                                    <div class="alert alert-success rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle fa-lg me-3 text-success"></i>
                                            <div>
                                                <strong>Anda Sudah Terdaftar</strong>
                                                <p class="mb-0 small">Terima kasih telah mendaftar kegiatan ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card border rounded-3">
                                        <div class="card-body p-4">
                                            @if (session('success'))
                                                <div class="alert alert-success alert-dismissible fade show rounded-3"
                                                    role="alert">
                                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            @if (session('error'))
                                                <div class="alert alert-danger alert-dismissible fade show rounded-3"
                                                    role="alert">
                                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            @if ($event->registrationForm->description)
                                                <p class="text-muted mb-3">{{ $event->registrationForm->description }}</p>
                                            @endif

                                            <form
                                                action="{{ route('kegiatan.register', ['eventId' => $event->event_id]) }}"
                                                method="POST">
                                                @csrf
                                                @foreach ($event->registrationForm->fields as $field)
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">
                                                            {{ $field->label }}
                                                            @if ($field->is_required)
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </label>
                                                        @switch($field->type)
                                                            @case('text')
                                                            @case('email')

                                                            @case('number')
                                                            @case('tel')
                                                                <input type="{{ $field->type }}" name="{{ $field->name }}"
                                                                    class="form-control @error($field->name) is-invalid @enderror"
                                                                    placeholder="{{ $field->placeholder }}"
                                                                    value="{{ old($field->name) }}"
                                                                    {{ $field->is_required ? 'required' : '' }}>
                                                            @break

                                                            @case('textarea')
                                                                <textarea name="{{ $field->name }}" class="form-control @error($field->name) is-invalid @enderror"
                                                                    placeholder="{{ $field->placeholder }}" rows="3" {{ $field->is_required ? 'required' : '' }}>{{ old($field->name) }}</textarea>
                                                            @break

                                                            @case('select')
                                                                <select name="{{ $field->name }}"
                                                                    class="form-select @error($field->name) is-invalid @enderror"
                                                                    {{ $field->is_required ? 'required' : '' }}>
                                                                    <option value="">-- Pilih --</option>
                                                                    @if ($field->options)
                                                                        @foreach (explode(',', $field->options) as $option)
                                                                            <option value="{{ trim($option) }}"
                                                                                {{ old($field->name) == trim($option) ? 'selected' : '' }}>
                                                                                {{ trim($option) }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            @break

                                                            @case('radio')
                                                                @if ($field->options)
                                                                    @foreach (explode(',', $field->options) as $option)
                                                                        <div class="form-check">
                                                                            <input type="radio" name="{{ $field->name }}"
                                                                                value="{{ trim($option) }}"
                                                                                class="form-check-input @error($field->name) is-invalid @enderror"
                                                                                id="{{ $field->name }}_{{ Str::slug($option) }}"
                                                                                {{ old($field->name) == trim($option) ? 'checked' : '' }}
                                                                                {{ $field->is_required ? 'required' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="{{ $field->name }}_{{ Str::slug($option) }}">{{ trim($option) }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @break

                                                            @case('checkbox')
                                                                @if ($field->options)
                                                                    @foreach (explode(',', $field->options) as $option)
                                                                        <div class="form-check">
                                                                            <input type="checkbox" name="{{ $field->name }}[]"
                                                                                value="{{ trim($option) }}"
                                                                                class="form-check-input"
                                                                                id="{{ $field->name }}_{{ Str::slug($option) }}"
                                                                                {{ is_array(old($field->name)) && in_array(trim($option), old($field->name)) ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="{{ $field->name }}_{{ Str::slug($option) }}">{{ trim($option) }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            @break

                                                            @default
                                                                <input type="text" name="{{ $field->name }}"
                                                                    class="form-control" placeholder="{{ $field->placeholder }}"
                                                                    value="{{ old($field->name) }}"
                                                                    {{ $field->is_required ? 'required' : '' }}>
                                                        @endswitch
                                                        @error($field->name)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                                <div class="d-grid mt-4">
                                                    <button type="submit" class="btn btn-success btn-lg rounded-pill">
                                                        <i class="fas fa-paper-plane me-2"></i>Daftar Kegiatan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Questionnaire/Closing Form Section --}}
                        @if ($event->has_closing_form && $event->closingForm)
                            <div class="mb-4" id="questionnaire-section">
                                <h5 class="fw-bold mb-3" style="color: #175C9E;">
                                    <i class="fas fa-clipboard-check me-2"></i>Kuesioner / Formulir Penutupan
                                </h5>

                                @if (!$eventEnded)
                                    <div class="alert alert-warning rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hourglass-half fa-lg me-3 text-warning"></i>
                                            <div>
                                                <strong>Kuesioner Belum Tersedia</strong>
                                                <p class="mb-0 small">Formulir akan tersedia setelah kegiatan selesai.</p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif (!$hasRegistered)
                                    <div class="alert alert-secondary rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-slash fa-lg me-3 text-secondary"></i>
                                            <div>
                                                <strong>Tidak Terdaftar</strong>
                                                <p class="mb-0 small">Hanya peserta terdaftar yang dapat mengisi kuesioner.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif (!($event->questionnaire_enabled ?? true))
                                    <div class="alert alert-secondary rounded-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-lock fa-lg me-3 text-secondary"></i>
                                            <div>
                                                <strong>Kuesioner Ditutup</strong>
                                                <p class="mb-0 small">Pengisian kuesioner ditutup oleh penyelenggara.</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card border rounded-3" style="background: #e0f7fa;">
                                        <div class="card-body p-4">
                                            <h6 class="fw-bold mb-2" style="color: #00838f;">Terima kasih telah mengikuti
                                                kegiatan ini!</h6>
                                            <p class="text-muted mb-3 small">Silakan luangkan waktu untuk mengisi
                                                kuesioner.</p>
                                            <a href="{{ route('form.show', $event->closingForm->slug) }}"
                                                class="btn btn-info rounded-pill px-4 text-white">
                                                <i class="fas fa-paper-plane me-2"></i>Isi Kuesioner
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Divider -->
                        <hr class="my-4" style="border-top: 1px dashed #e2e8f0;">

                        <!-- Footer Link - Right Aligned -->
                        <div class="text-end">
                            <a href="/jadwal-kegiatan" class="text-decoration-none footer-link">
                                Lihat Jadwal Kegiatan Lainnya
                                <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .theme-content {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .main-card {
            box-shadow: none;
        }

        .event-title {
            font-size: 2rem;
        }

        .icon-circle {
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .back-link {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .footer-link {
            color: #175C9E;
            font-size: 0.95rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .event-title {
                font-size: 1.5rem;
            }

            .card-body {
                padding: 1.5rem !important;
            }
        }
    </style>
@endpush
