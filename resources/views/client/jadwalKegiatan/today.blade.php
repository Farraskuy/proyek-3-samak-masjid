{{-- client/jadwalKegiatan/today.blade.php --}}

@if(!$event)
    <div class="card border-0 bg-warning bg-opacity-10 rounded-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-warning mb-3"></i>
            <h5 class="fw-semibold text-warning mb-2">Tidak Ada Kegiatan</h5>
            <p class="text-muted mb-0">
                @if($selectedDate ?? false)
                    Tidak ada kegiatan pada
                    <strong class="fs-7">
                        {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, j F Y') }}
                    </strong>
                @else
                    Belum ada kegiatan yang dijadwalkan hari ini
                @endif
            </p>
        </div>
    </div>

@else
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-lift">
        <div class="row g-0">

            <!-- Poster -->
            <div class="col-md-5 position-relative">
                @if($event->poster)
                    <img src="{{ asset('storage/' . $event->poster) }}"
                         class="w-100 h-100" style="object-fit: cover; min-height: 250px;">
                    
                    <!-- Badge "Hari Ini" hanya muncul jika tanggal yang dipilih adalah hari ini -->
                    @if($selectedDate && \Carbon\Carbon::parse($selectedDate)->isToday())
                        <div class="position-absolute top-0 start-0 m-3">
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                <i class="fas fa-fire me-1"></i>Hari Ini
                            </span>
                        </div>
                    @endif
                @else
                    <div class="bg-gradient d-flex align-items-center justify-content-center" 
                         style="background: linear-gradient(135deg, #175C9E, #1a4d7a); min-height: 250px;">
                        <i class="fas fa-calendar-check fa-4x text-white opacity-25"></i>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="col-md-7">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3" style="color: #175C9E;">
                        {{ $event->event_name }}
                    </h5>

                    @if($event->tamuUndangan->count() > 0)
                        <div class="mb-3 p-3 bg-light rounded-3">
                            <small class="text-muted d-block mb-1">Pembicara:</small>
                            <div class="fw-semibold text-dark">
                                <i class="fas fa-user-tie me-2 text-primary"></i>
                                {{ $event->tamuUndangan->implode('nama_tamu', ', ') }}
                            </div>
                        </div>
                    @endif

                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="fas fa-calendar-alt text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Tanggal</small>
                            <span class="fw-semibold">
                                {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Waktu</small>
                            <span class="fw-semibold">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-danger bg-opacity-10 rounded p-2 me-3">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Lokasi</small>
                            <span class="fw-semibold">{{ $event->location }}</span>
                        </div>
                    </div>

                    <a href="{{ url('/jadwal-kegiatan/' . $event->event_id) }}"
                       class="btn btn-primary rounded-pill px-4 w-100">
                        <i class="fas fa-info-circle me-2"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif