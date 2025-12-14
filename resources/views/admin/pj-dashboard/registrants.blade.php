@extends('admin.layout')

@section('title', 'Daftar Peserta - ' . $event->event_name)

@push('styles')
    <style>
        .registrant-card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .event-info-bar {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #2563eb;
        }

        .status-verified {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .table-registrants th {
            background-color: #f9fafb;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6b7280;
            border: none;
        }

        .table-registrants td {
            vertical-align: middle;
            border-color: #f3f4f6;
        }

        .verify-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .summary-card {
            background: #f9fafb;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .tab-custom .nav-link {
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 600;
            color: #6b7280;
            transition: all 0.2s;
        }

        .tab-custom .nav-link.active {
            background-color: #2563eb;
            color: white;
        }

        .tab-custom .nav-link:hover:not(.active) {
            background-color: #f3f4f6;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
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
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.pj.show', $event->event_id) }}" class="text-decoration-none">
                        {{ Str::limit($event->event_name, 30) }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Peserta</li>
            </ol>
        </nav>

        {{-- Event Info Bar --}}
        <div class="event-info-bar mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">{{ $event->event_name }}</h4>
                    <p class="mb-0 text-muted">
                        <i class="fa-regular fa-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('d M Y, H:i') }}
                        &bull;
                        <i class="fa-regular fa-location-dot ms-2 me-1"></i>
                        {{ $event->location ?? 'Lokasi belum ditentukan' }}
                    </p>
                </div>
                <div class="d-flex gap-4">
                    <div class="text-center">
                        <div class="stat-number text-primary">{{ $registrants->count() }}</div>
                        <small class="text-muted">Total Pendaftar</small>
                    </div>
                    @php
                        $verifiedCount = $registrants->where('is_verified', true)->count();
                    @endphp
                    <div class="text-center">
                        <div class="stat-number text-success">{{ $verifiedCount }}</div>
                        <small class="text-muted">Terverifikasi</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav tab-custom mb-4 p-2 rounded bg-light w-fit-content" id="registrantTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-pane"
                    type="button" role="tab">
                    <i class="fa-solid fa-list me-1"></i>Daftar Pendaftar
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button"
                    role="tab">
                    <i class="fa-solid fa-chart-bar me-1"></i>Ringkasan
                </button>
            </li>
        </ul>

        <div class="tab-content" id="registrantTabsContent">
            {{-- Tab: Daftar Pendaftar --}}
            <div class="tab-pane fade show active" id="list-pane" role="tabpanel">
                <div class="card registrant-card">
                    <div class="card-body p-0">
                        @if ($registrants->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa-duotone fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Pendaftar</h5>
                                <p class="text-secondary">Belum ada peserta yang mendaftar untuk kegiatan ini.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-registrants table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3">#</th>
                                            <th class="px-4 py-3">Waktu Daftar</th>
                                            @if ($event->registrationForm)
                                                @foreach ($event->registrationForm->fields->take(3) as $field)
                                                    @if (!in_array($field->type, ['header', 'paragraph']))
                                                        <th class="px-4 py-3">{{ $field->label }}</th>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <th class="px-4 py-3">Status</th>
                                            <th class="px-4 py-3 text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registrants as $index => $registrant)
                                            <tr>
                                                <td class="px-4">{{ $index + 1 }}</td>
                                                <td class="px-4">
                                                    <span class="text-muted small">
                                                        {{ $registrant->created_at->format('d M Y') }}
                                                    </span>
                                                    <br>
                                                    <span class="text-muted small">
                                                        {{ $registrant->created_at->format('H:i') }}
                                                    </span>
                                                </td>
                                                @if ($event->registrationForm)
                                                    @foreach ($event->registrationForm->fields->take(3) as $field)
                                                        @if (!in_array($field->type, ['header', 'paragraph']))
                                                            @php
                                                                $item = $registrant->items
                                                                    ->where('field_name', $field->name)
                                                                    ->first();
                                                                $value = $item ? $item->value : '-';

                                                                // Handle array values (checkboxes, etc.)
                                                                if (is_string($value) && json_decode($value)) {
                                                                    $decoded = json_decode($value, true);
                                                                    $value = is_array($decoded)
                                                                        ? implode(', ', $decoded)
                                                                        : $value;
                                                                }
                                                            @endphp
                                                            <td class="px-4">{{ Str::limit($value, 30) }}</td>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <td class="px-4">
                                                    @if ($registrant->is_verified)
                                                        <span class="badge status-verified rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-check me-1"></i>Terverifikasi
                                                        </span>
                                                    @else
                                                        <span class="badge status-pending rounded-pill px-3 py-2">
                                                            <i class="fa-solid fa-clock me-1"></i>Pending
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 text-end">
                                                    <div class="d-flex gap-1 justify-content-end">
                                                        @if (!$registrant->is_verified)
                                                            <button type="button" class="btn btn-success verify-btn"
                                                                onclick="showConfirmModal({
                                                                    action: '{{ route('admin.pj.verify-registrant', [$event->event_id, $registrant->id]) }}',
                                                                    method: 'PATCH',
                                                                    type: 'verify',
                                                                    title: 'Verifikasi Pendaftar',
                                                                    message: 'Apakah Anda yakin ingin memverifikasi pendaftar ini?',
                                                                    buttonText: 'Ya, Verifikasi'
                                                                })">
                                                                <i class="fa-solid fa-check me-1"></i>Verifikasi
                                                            </button>
                                                            <input type="hidden" name="is_verified" value="1">
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-outline-secondary verify-btn"
                                                                onclick="showConfirmModal({
                                                                    action: '{{ route('admin.pj.verify-registrant', [$event->event_id, $registrant->id]) }}',
                                                                    method: 'PATCH',
                                                                    type: 'unverify',
                                                                    title: 'Batalkan Verifikasi',
                                                                    message: 'Apakah Anda yakin ingin membatalkan verifikasi pendaftar ini?',
                                                                    buttonText: 'Ya, Batalkan'
                                                                })">
                                                                <i class="fa-solid fa-times me-1"></i>Batal
                                                            </button>
                                                            <input type="hidden" name="is_verified" value="0">
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tab: Ringkasan --}}
            <div class="tab-pane fade" id="summary-pane" role="tabpanel">
                @if ($event->registrationForm && $registrants->isNotEmpty())
                    <div class="row g-4">
                        @foreach ($event->registrationForm->fields as $field)
                            @if (in_array($field->type, ['header', 'paragraph']))
                                @continue
                            @endif

                            <div class="col-md-6">
                                <div class="card summary-card">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3">{{ $field->label }}</h6>

                                        @php
                                            $answers = $registrants->flatMap(function ($r) use ($field) {
                                                return $r->items->where('field_name', $field->name)->pluck('value');
                                            });
                                        @endphp

                                        @if ($answers->isEmpty())
                                            <p class="text-muted small fst-italic">Belum ada jawaban</p>
                                        @else
                                            <div class="bg-white rounded p-3"
                                                style="max-height: 200px; overflow-y: auto;">
                                                <ul class="list-unstyled mb-0 small">
                                                    @foreach ($answers as $ans)
                                                        <li class="mb-2 pb-2 border-bottom">
                                                            @php
                                                                $display = $ans;
                                                                if (is_string($ans) && json_decode($ans)) {
                                                                    $decoded = json_decode($ans, true);
                                                                    $display = is_array($decoded)
                                                                        ? implode(', ', $decoded)
                                                                        : $ans;
                                                                }
                                                            @endphp
                                                            {{ $display }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="mt-2 text-end">
                                                <span class="badge bg-secondary">{{ $answers->count() }} Jawaban</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa-duotone fa-chart-simple fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Data</h5>
                        <p class="text-secondary">Belum ada data untuk ditampilkan.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-4">
            <a href="{{ route('admin.pj.show', $event->event_id) }}" class="btn btn-light border">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Kontrol Kegiatan
            </a>
        </div>
    </section>
@endsection
