@extends('client.layout')

@section('content')

    <!-- MAIN CONTENT -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <!-- Back Button & Title Section -->
                <div class="mb-5" data-aos="fade-down" data-aos-duration="600">
                    <a href="/jadwal-kegiatan" class="btn btn-outline-primary px-4 py-2 mb-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Jadwal Kegiatan
                    </a>

                    <!-- Professional Title -->
                    <div class="mb-4">
                        <h1 class="fw-bold mb-2" style="color: #175C9E; font-size: 2.369rem; letter-spacing: -0.5px;">
                            Detail Kegiatan
                        </h1>
                        <div style="width: 80px; height: 3px; background-color: #F6C948;"></div>
                        <p class="text-secondary mt-3 mb-0" style="font-size: 1rem;">Informasi lengkap mengenai kegiatan ini</p>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-sm overflow-hidden" data-aos="fade-up" data-aos-duration="700" style="border-radius: 8px;">

                    <!-- Poster -->
                    @if ($event->poster)
                        <div class="bg-light p-4 border-bottom">
                            <div class="card-thumbnail-wrapper mx-auto" style="max-width: 100%; height: auto; min-height: 300px;">
                                <img id="poster-image" src="{{ asset('storage/' . $event->poster) }}" alt="Poster {{ $event->event_name }}"
                                    class="img-fluid poster-clickable" style="max-height: 600px; object-fit: contain; border-radius: 4px; cursor: pointer;" loading="lazy"
                                    data-bs-toggle="modal" data-bs-target="#posterModal">
                                <i id="fallback-icon" class="fas fa-image fallback-icon" style="font-size: 5rem;"></i>
                            </div>
                        </div>
                    @endif

                    <div class="card-body p-5">
                        <!-- Title -->
                        <div class="mb-5 pb-4 border-bottom">
                            <h2 class="fw-bold mb-2" style="color: #175C9E; font-size: 1.777rem; letter-spacing: -0.3px;">
                                {{ $event->event_name }}
                            </h2>
                        </div>

                        <!-- Tema/Deskripsi -->
                        @if ($event->theme)
                            <div class="mb-5">
                                <div class="p-4 border-start border-4" style="border-color: #175C9E !important; background-color: #f8fafc;">
                                    <h5 class="fw-semibold mb-3" style="color: #175C9E; font-size: 1.125rem;">
                                        Tema & Deskripsi
                                    </h5>
                                    <div class="theme-content" style="color: #475569; font-size: 1rem; line-height: 1.7;">
                                        {{ $event->theme }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Info Grid -->
                        <div class="row g-4 mb-5">
                            <!-- Tanggal Mulai -->
                            <div class="col-md-6">
                                <div class="p-4 border h-100" style="border-radius: 8px; background-color: #fafbfc;">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="rounded d-flex align-items-center justify-content-center" 
                                                style="width: 48px; height: 48px; background-color: #175C9E;">
                                                <i class="fas fa-calendar-check text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-uppercase mb-2 fw-semibold" style="font-size: 0.75rem; color: #64748b; letter-spacing: 0.5px;">
                                                Waktu Mulai
                                            </p>
                                            <h5 class="fw-semibold mb-1" style="color: #1e293b; font-size: 1.125rem;">
                                                {{ \Carbon\Carbon::parse($event->start_time)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                            </h5>
                                            <p class="mb-0" style="color: #64748b; font-size: 0.938rem;">
                                                <i class="fas fa-clock me-1"></i>{{ date('H:i', strtotime($event->start_time)) }} WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Selesai -->
                            <div class="col-md-6">
                                <div class="p-4 border h-100" style="border-radius: 8px; background-color: #fafbfc;">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="rounded d-flex align-items-center justify-content-center" 
                                                style="width: 48px; height: 48px; background-color: #175C9E;">
                                                <i class="fas fa-calendar-times text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-uppercase mb-2 fw-semibold" style="font-size: 0.75rem; color: #64748b; letter-spacing: 0.5px;">
                                                Waktu Selesai
                                            </p>
                                            <h5 class="fw-semibold mb-1" style="color: #1e293b; font-size: 1.125rem;">
                                                {{ \Carbon\Carbon::parse($event->end_time)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                            </h5>
                                            <p class="mb-0" style="color: #64748b; font-size: 0.938rem;">
                                                <i class="fas fa-clock me-1"></i>{{ date('H:i', strtotime($event->end_time)) }} WIB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="col-12">
                                <div class="p-4 border" style="border-radius: 8px; background-color: #fafbfc;">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="rounded d-flex align-items-center justify-content-center" 
                                                style="width: 48px; height: 48px; background-color: #175C9E;">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-uppercase mb-2 fw-semibold" style="font-size: 0.75rem; color: #64748b; letter-spacing: 0.5px;">
                                                Lokasi Kegiatan
                                            </p>
                                            <h5 class="fw-semibold mb-0" style="color: #1e293b; font-size: 1.125rem;">
                                                {{ $event->location }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pembicara / Tamu Undangan -->
                        @if ($event->is_have_tamu_undangan && $event->tamuUndangan->count() > 0)
                            <div class="mb-5">
                                <h4 class="fw-semibold mb-4 pb-2 border-bottom" style="color: #175C9E; font-size: 1.333rem;">
                                    Pembicara & Tamu Undangan
                                </h4>

                                <div class="row g-3">
                                    @foreach ($event->tamuUndangan as $index => $tamu)
                                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                            <div class="card border h-100" style="border-radius: 8px;">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="rounded d-flex align-items-center justify-content-center"
                                                                style="width: 48px; height: 48px; background-color: #175C9E;">
                                                                <i class="fas fa-user-tie text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-semibold mb-1" style="color: #1e293b; font-size: 1rem;">
                                                                {{ $tamu->nama_tamu }}
                                                            </h6>
                                                            <span class="badge" style="background-color: #e0f2fe; color: #0369a1; font-weight: 500;">
                                                                Pembicara
                                                            </span>
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
                            <div class="mb-5" id="registration-section">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="fas fa-clipboard-list text-success"></i>
                                    </div>
                                    <h4 class="fw-bold mb-0" style="color: #175C9E;">
                                        Formulir Pendaftaran
                                    </h4>
                                </div>

                                @if ($eventEnded)
                                    {{-- Event has ended --}}
                                    <div class="alert alert-secondary rounded-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-lock fa-2x me-3 text-secondary"></i>
                                            <div>
                                                <h5 class="mb-1">Pendaftaran Ditutup</h5>
                                                <p class="mb-0">Kegiatan ini telah selesai dilaksanakan. Formulir pendaftaran sudah tidak tersedia.</p>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($hasRegistered)
                                    {{-- Already registered --}}
                                    <div class="alert alert-success rounded-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                            <div>
                                                <h5 class="mb-1">Anda Sudah Terdaftar</h5>
                                                <p class="mb-0">Terima kasih! Anda sudah terdaftar untuk kegiatan ini.</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Show registration form --}}
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-body p-4">
                                            @if (session('success'))
                                                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            @if (session('error'))
                                                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                </div>
                                            @endif

                                            @if ($event->registrationForm->description)
                                                <p class="text-muted mb-4">{{ $event->registrationForm->description }}</p>
                                            @endif

                                            <form action="{{ route('kegiatan.register', ['eventId' => $event->event_id]) }}" method="POST" id="registration-form">
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
                                                                <input type="{{ $field->type }}" 
                                                                    name="{{ $field->name }}" 
                                                                    class="form-control @error($field->name) is-invalid @enderror"
                                                                    placeholder="{{ $field->placeholder }}"
                                                                    value="{{ old($field->name) }}"
                                                                    {{ $field->is_required ? 'required' : '' }}>
                                                                @break

                                                            @case('textarea')
                                                                <textarea name="{{ $field->name }}" 
                                                                    class="form-control @error($field->name) is-invalid @enderror"
                                                                    placeholder="{{ $field->placeholder }}"
                                                                    rows="4"
                                                                    {{ $field->is_required ? 'required' : '' }}>{{ old($field->name) }}</textarea>
                                                                @break

                                                            @case('select')
                                                                <select name="{{ $field->name }}" 
                                                                    class="form-select @error($field->name) is-invalid @enderror"
                                                                    {{ $field->is_required ? 'required' : '' }}>
                                                                    <option value="">-- Pilih --</option>
                                                                    @if ($field->options)
                                                                        @foreach (explode(',', $field->options) as $option)
                                                                            <option value="{{ trim($option) }}" {{ old($field->name) == trim($option) ? 'selected' : '' }}>
                                                                                {{ trim($option) }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                @break

                                                            @case('radio')
                                                                @if ($field->options)
                                                                    @foreach (explode(',', $field->options) as $option)
                                                                        <div class="form-check">
                                                                            <input type="radio" 
                                                                                name="{{ $field->name }}" 
                                                                                value="{{ trim($option) }}"
                                                                                class="form-check-input @error($field->name) is-invalid @enderror"
                                                                                id="{{ $field->name }}_{{ Str::slug($option) }}"
                                                                                {{ old($field->name) == trim($option) ? 'checked' : '' }}
                                                                                {{ $field->is_required ? 'required' : '' }}>
                                                                            <label class="form-check-label" for="{{ $field->name }}_{{ Str::slug($option) }}">
                                                                                {{ trim($option) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                                @break

                                                            @case('checkbox')
                                                                @if ($field->options)
                                                                    @foreach (explode(',', $field->options) as $option)
                                                                        <div class="form-check">
                                                                            <input type="checkbox" 
                                                                                name="{{ $field->name }}[]" 
                                                                                value="{{ trim($option) }}"
                                                                                class="form-check-input"
                                                                                id="{{ $field->name }}_{{ Str::slug($option) }}"
                                                                                {{ is_array(old($field->name)) && in_array(trim($option), old($field->name)) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="{{ $field->name }}_{{ Str::slug($option) }}">
                                                                                {{ trim($option) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                                @break

                                                            @default
                                                                <input type="text" 
                                                                    name="{{ $field->name }}" 
                                                                    class="form-control"
                                                                    placeholder="{{ $field->placeholder }}"
                                                                    value="{{ old($field->name) }}"
                                                                    {{ $field->is_required ? 'required' : '' }}>
                                                        @endswitch

                                                        @error($field->name)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endforeach

                                                <div class="d-grid mt-4">
                                                    <button type="submit" class="btn btn-success btn-lg rounded-pill" id="submit-btn">
                                                        <i class="fas fa-paper-plane me-2"></i>Daftar Kegiatan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Forms Section -->
                        @if ($event->has_registration_form || $event->has_closing_form)
                            <div class="mb-4">
                                <h4 class="fw-semibold mb-4 pb-2 border-bottom" style="color: #175C9E; font-size: 1.333rem;">
                                    Formulir
                                </h4>

                                <div class="row g-4">
                                    <!-- Form Pendaftaran -->
                                    @if ($event->has_registration_form && $event->registrationForm)
                                        <div class="col-12" data-aos="fade-up">
                                            <div class="card border" style="border-radius: 8px; border-color: #059669 !important;">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="rounded d-flex align-items-center justify-content-center" 
                                                                style="width: 48px; height: 48px; background-color: #059669;">
                                                                <i class="fas fa-user-plus text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="text-uppercase mb-2 fw-semibold" style="font-size: 0.75rem; color: #64748b; letter-spacing: 0.5px;">
                                                                Form Pendaftaran
                                                            </p>
                                                            <h5 class="fw-semibold mb-1" style="color: #1e293b; font-size: 1.125rem;">
                                                                {{ $event->registrationForm->title }}
                                                            </h5>
                                                            @if ($event->registrationForm->description)
                                                                <p class="mb-0 mt-2 text-muted" style="font-size: 0.875rem;">
                                                                    {{ $event->registrationForm->description }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="ms-3">
                                                            <a href="{{ route('form.show', $event->registrationForm->slug) }}"
                                                                target="_blank"
                                                                class="btn btn-success fw-semibold px-4 py-2">
                                                                <i class="fas fa-edit me-2"></i>Isi Formulir
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Form Penutupan/Kuisioner -->
                                    @if ($event->has_closing_form && $event->closingForm)
                                        <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                                            <div class="card border" style="border-radius: 8px; border-color: #d97706 !important;">
                                                <div class="card-body p-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="rounded d-flex align-items-center justify-content-center" 
                                                                style="width: 48px; height: 48px; background-color: #d97706;">
                                                                <i class="fas fa-clipboard-check text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="text-uppercase mb-2 fw-semibold" style="font-size: 0.75rem; color: #64748b; letter-spacing: 0.5px;">
                                                                Form Penutupan / Kuisioner
                                                            </p>
                                                            <h5 class="fw-semibold mb-1" style="color: #1e293b; font-size: 1.125rem;">
                                                                {{ $event->closingForm->title }}
                                                            </h5>
                                                            @if ($event->closingForm->description)
                                                                <p class="mb-0 mt-2 text-muted" style="font-size: 0.875rem;">
                                                                    {{ $event->closingForm->description }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="ms-3">
                                                            <a href="{{ route('form.show', $event->closingForm->slug) }}"
                                                                target="_blank"
                                                                class="btn btn-warning fw-semibold px-4 py-2">
                                                                <i class="fas fa-edit me-2"></i>Isi Kuisioner
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Poster -->
    @if ($event->poster)
        <div class="modal fade" id="posterModal" tabindex="-1" aria-labelledby="posterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-transparent border-0">
                    <div class="modal-header border-0 position-absolute top-0 end-0 z-3">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0 text-center">
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster {{ $event->event_name }}"
                            class="img-fluid" style="max-height: 90vh; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('styles')
    <style>
        /* Fallback Icon */
        .fallback-icon {
            display: none;
            color: #cbd5e1;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        .card-thumbnail-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Poster Clickable Effect */
        .poster-clickable {
            transition: transform 0.3s ease;
        }

        .card-thumbnail-wrapper:hover .poster-clickable {
            transform: scale(1.02);
        }

        /* Modal Backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.9);
        }

        /* Theme Content Styling */
        .theme-content {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: justify;
        }

        /* Professional Card Hover */
        .card {
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        /* Responsive Typography */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.777rem !important;
            }

            h2 {
                font-size: 1.333rem !important;
            }
            
            h4, h5 {
                font-size: 1rem !important;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .btn {
                font-size: 0.875rem !important;
            }
        }

        /* Professional Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Border and spacing refinements */
        .border {
            border-color: #e2e8f0 !important;
        }

        .border-bottom {
            border-color: #e2e8f0 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangani error pada pemuatan gambar poster
            const posterImage = document.getElementById('poster-image');
            const fallbackIcon = document.getElementById('fallback-icon');
            
            if (posterImage && fallbackIcon) {
                posterImage.addEventListener('error', function() {
                    this.style.display = 'none';
                    fallbackIcon.style.display = 'block';
                    // Hapus fungsi klik pada gambar yang gagal dimuat
                    this.style.cursor = 'default';
                    this.removeAttribute('data-bs-toggle');
                    this.removeAttribute('data-bs-target');
                });
                
                // Periksa jika gambar sudah dimuat dengan benar
                if (posterImage.complete && posterImage.naturalWidth > 0) {
                    fallbackIcon.style.display = 'none';
                }
            }

            // Tambahkan dukungan keyboard untuk modal
            const posterModal = document.getElementById('posterModal');
            if (posterModal) {
                posterModal.addEventListener('shown.bs.modal', function () {
                    this.querySelector('.btn-close').focus();
                });
            }
        });
    </script>
@endpush