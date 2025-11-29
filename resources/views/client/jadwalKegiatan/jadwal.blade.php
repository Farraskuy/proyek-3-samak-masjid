@extends('client.layout')

@section('content')
    <!-- HERO SECTION -->
    <section class="py-5 bg-pattern" style="background-color: #175C9E; height: 320px; display: flex; align-items: center;">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-white mb-3" data-aos="fade-up" data-aos-duration="900">
                Jadwal <span style="color: #F6C948;">Kegiatan</span>
            </h1>
            <p class="lead text-white-50 mb-0 col-lg-8 mx-auto" data-aos="fade-up" data-aos-duration="1100"
                data-aos-delay="200">
                Informasi agenda kegiatan dan kajian rutin di Masjid
            </p>
        </div>
    </section>


    <!-- ===== MAIN CONTENT ===== -->
    <div class="container py-5">

        <div class="row g-4">

            <!-- Kalender Sidebar -->
            <div class="col-lg-4 mb-4" data-aos="fade-right" data-aos-duration="700">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <!-- Header Kalender -->
                    <div class="text-center py-4 px-3" style="background: linear-gradient(135deg, #175C9E, #1a4d7a);">
                        <h4 class="fw-bold mb-2 text-white">
                            <i class="fas fa-calendar-alt me-2"></i>Jadwal Kegiatan
                        </h4>
                    </div>

                    <!-- Kalender -->
                    <div class="card-body p-4">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Kegiatan Hari Ini -->
            <div class="col-lg-8 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-star text-primary"></i>
                    </div>
                    <h4 class="fw-bold mb-0" id="event-title" style="color:#175C9E;">
                        Kegiatan Hari Ini
                    </h4>
                </div>

                <div id="event-detail-container">
                    @include('client.jadwalKegiatan.today', [
                        'event' => $todayEvent,
                        'selectedDate' => date('Y-m-d'),
                    ])
                </div>
            </div>

        </div>

        <!-- Daftar Kegiatan -->
        <div class="row mt-5">
            <div class="col-12">
                <div data-aos="fade-up" data-aos-duration="700">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-list text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-0" style="color: #175C9E;">Daftar Kegiatan</h4>
                    </div>

                    @forelse ($events as $index => $event)
                        <div class="card border-0 shadow-sm rounded-4 mb-3 hover-lift" data-aos="fade-up"
                            data-aos-duration="700" data-aos-delay="{{ $index * 100 }}">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <!-- Poster -->
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        @if ($event->poster)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $event->poster) }}"
                                                    class="img-fluid rounded-3 w-100"
                                                    style="height: 140px; object-fit: cover;">
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-primary rounded-pill">
                                                        <i class="fas fa-calendar-day me-1"></i>
                                                        {{ date('d M', strtotime($event->start_time)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-gradient rounded-3 d-flex align-items-center justify-content-center"
                                                style="background: linear-gradient(135deg, #175C9E, #1a4d7a); height: 140px;">
                                                <i class="fas fa-calendar fa-3x text-white opacity-25"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="col-md-9">
                                        <h5 class="fw-bold mb-2" style="color: #175C9E;">
                                            {{ $event->event_name }}
                                        </h5>

                                        @if ($event->tamuUndangan->count() > 0)
                                            <p class="mb-2 text-muted small">
                                                <i class="fas fa-user-tie me-2"></i>
                                                <span
                                                    class="fw-semibold">{{ $event->tamuUndangan->implode('nama_tamu', ', ') }}</span>
                                            </p>
                                        @endif

                                        <div class="row g-2 mb-3">
                                            <div class="col-auto">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                                    {{ date('d M Y', strtotime($event->start_time)) }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-clock me-1 text-success"></i>
                                                    {{ date('H:i', strtotime($event->start_time)) }} WIB
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                                    {{ $event->location }}
                                                </span>
                                            </div>
                                        </div>

                                        <a href="{{ url('/jadwal-kegiatan/' . $event->event_id) }}"
                                            class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                            <i class="fas fa-arrow-right me-2"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card border-0 bg-light rounded-4">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="fw-semibold text-muted mb-2">Belum Ada Kegiatan</h5>
                                <p class="text-muted mb-0">Kegiatan akan ditampilkan di sini ketika tersedia</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

    <style>
        /* Calendar Styling - Enhanced Design */
        .bg-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 25px 25px;
        }

        #calendar {
            width: 100%;
        }

        .flatpickr-calendar {
            width: 100% !important;
            box-shadow: none !important;
            background: transparent !important;
            border: none !important;
        }

        .flatpickr-months {
            background: #f8f9fa !important;
            padding: 15px !important;
            border-radius: 12px !important;
            margin-bottom: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .flatpickr-month {
            color: #175C9E !important;
            height: auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex: 1 !important;
        }

        .flatpickr-current-month {
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            padding: 8px 0 !important;
            color: #175C9E !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            position: static !important;
            width: auto !important;
            left: auto !important;
            transform: none !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            appearance: none !important;
            background: white !important;
            color: #175C9E !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 30px 6px 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23175C9E' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 10px center !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            border-color: #175C9E !important;
            background-color: #f8f9fa !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: white !important;
            color: #175C9E !important;
            padding: 8px !important;
        }

        .flatpickr-current-month .numInputWrapper {
            width: 80px !important;
        }

        .flatpickr-current-month input.cur-year {
            background: white !important;
            color: #175C9E !important;
            border: 2px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
        }

        .flatpickr-current-month input.cur-year:hover {
            border-color: #175C9E !important;
            background: #f8f9fa !important;
        }

        .flatpickr-current-month input.cur-year:focus {
            background: white !important;
            border-color: #175C9E !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(23, 92, 158, 0.1) !important;
        }

        .flatpickr-current-month .numInputWrapper span {
            border: none !important;
            background: transparent !important;
            opacity: 0.6 !important;
        }

        .flatpickr-current-month .numInputWrapper span:hover {
            opacity: 1 !important;
            background: rgba(23, 92, 158, 0.1) !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #175C9E !important;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #175C9E !important;
        }

        .flatpickr-prev-month,
        .flatpickr-next-month {
            fill: #175C9E !important;
            padding: 8px !important;
            position: static !important;
            height: auto !important;
            transition: all 0.3s ease !important;
            border-radius: 8px !important;
        }

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg {
            width: 14px !important;
            height: 14px !important;
        }

        .flatpickr-prev-month:hover,
        .flatpickr-next-month:hover {
            background: rgba(23, 92, 158, 0.1) !important;
            fill: #175C9E !important;
        }

        .flatpickr-prev-month:hover svg,
        .flatpickr-next-month:hover svg {
            fill: #175C9E !important;
        }

        .flatpickr-weekdays {
            background: transparent !important;
            padding: 12px 0 !important;
            margin-top: 5px !important;
            border-bottom: 2px solid #f1f5f9 !important;
        }

        .flatpickr-weekday {
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 0.8rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .flatpickr-days {
            width: 100% !important;
        }

        .flatpickr-day {
            height: 40px !important;
            line-height: 40px !important;
            border-radius: 10px !important;
            border: none !important;
            font-weight: 500 !important;
            margin: 2px !important;
            transition: all 0.2s ease !important;
            color: #1e293b !important;
            position: relative !important;
        }

        /* Event Indicator Dot */
        .flatpickr-day.has-event::after {
            content: '' !important;
            position: absolute !important;
            bottom: 4px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 5px !important;
            height: 5px !important;
            background: #175C9E !important;
            border-radius: 50% !important;
            box-shadow: 0 0 0 2px rgba(23, 92, 158, 0.2) !important;
        }

        .flatpickr-day.has-event.today::after {
            background: white !important;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5) !important;
        }

        .flatpickr-day.has-event.selected::after {
            background: white !important;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5) !important;
        }

        .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected):not(.today) {
            background: #e0f2fe !important;
            border: none !important;
            color: #175C9E !important;
            transform: scale(1.05) !important;
        }

        .flatpickr-day.selected {
            background: #175C9E !important;
            color: white !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px rgba(23, 92, 158, 0.3) !important;
        }

        .flatpickr-day.today {
            background: #F6C948 !important;
            color: #175C9E !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 8px rgba(246, 201, 72, 0.4) !important;
        }

        .flatpickr-day.today:hover {
            background: #F6C948 !important;
            color: #175C9E !important;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #cbd5e0 !important;
        }

        .flatpickr-day.flatpickr-disabled {
            color: #e2e8f0 !important;
        }

        .flatpickr-day.inRange {
            background: rgba(23, 92, 158, 0.1) !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Responsive Calendar */
        @media (max-width: 768px) {
            .flatpickr-day {
                height: 36px !important;
                line-height: 36px !important;
                font-size: 0.85rem !important;
            }
        }

        /* Card Hover Effect */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(23, 92, 158, 0.15) !important;
        }

        /* Gradient Background */
        .bg-gradient {
            background: linear-gradient(135deg, #175C9E 0%, #1a4d7a 100%);
        }

        /* Badge Styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .display-3 {
                font-size: 2.5rem !important;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
                    const eventDates = @json($events->pluck('start_time')->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))->unique()->values()->toArray());

            const today = new Date().toISOString().split('T')[0];

            flatpickr("#calendar", {
                inline: true,
                locale: "id",
                dateFormat: "Y-m-d",
                defaultDate: today,

                        onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = flatpickr.formatDate(dayElem.dateObj, "Y-m-d");
                    if (eventDates.includes(dateStr)) {
                        dayElem.classList.add("has-event");
                    }
                },

                onChange: function(selectedDates, dateStr) {
                    if (!dateStr) return;

                            const container = document.getElementById("event-detail-container");
                    container.innerHTML =
                        '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

                            if (dateStr === today) {
                        document.getElementById("event-title").innerText = "Kegiatan Hari Ini";
                    } else {
                        const tanggal = new Date(dateStr);
                        const options = {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        };
                        document.getElementById("event-title").innerText =
                            "Kegiatan " + tanggal.toLocaleDateString("id-ID", options);
                    }

                    // Fetch data
                    fetch(`/jadwal-kegiatan/by-date?date=${dateStr}`)
                        .then(r => r.ok ? r.json() : Promise.reject())
                        .then(data => container.innerHTML = data.html)
                        .catch(() => container.innerHTML =
                            '<div class="alert alert-danger">Gagal memuat</div>');
                }
            });

                    document.getElementById("event-title").innerText = "Kegiatan Hari Ini";

                    setTimeout(() => {
                const fp = document.querySelector("#calendar")?._flatpickr;
                if (fp) fp.setDate(today, true);
            }, 150);
        });
    </script>
@endpush
