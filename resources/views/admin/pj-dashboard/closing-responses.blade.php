@extends('admin.layout')

@section('title', 'Responden Kuesioner - ' . $event->event_name)

@push('styles')
    <style>
        .response-card {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #fff;
        }

        .event-info-bar {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-left: 4px solid #0891b2;
        }

        .table-responses th {
            background-color: #f9fafb;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #6b7280;
            border: none;
        }

        .table-responses td {
            vertical-align: middle;
            border-color: #f3f4f6;
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
            background-color: #0891b2;
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
                <li class="breadcrumb-item active" aria-current="page">Responden Kuesioner</li>
            </ol>
        </nav>

        {{-- Event Info Bar --}}
        <div class="event-info-bar mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1 text-dark">{{ $event->event_name }}</h4>
                    <p class="mb-0 text-muted">
                        <i class="fa-regular fa-ballot-check me-1"></i>
                        Form Kuesioner: {{ $event->closingForm->title ?? 'Tidak tersedia' }}
                    </p>
                </div>
                <div class="text-center">
                    <div class="stat-number text-info">{{ $responses->count() }}</div>
                    <small class="text-muted">Total Responden</small>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav tab-custom mb-4 p-2 rounded bg-light w-fit-content" id="responseTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-pane" type="button"
                    role="tab">
                    <i class="fa-solid fa-list me-1"></i>Daftar Responden
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary-pane" type="button"
                    role="tab">
                    <i class="fa-solid fa-chart-bar me-1"></i>Ringkasan per Field
                </button>
            </li>
        </ul>

        <div class="tab-content" id="responseTabsContent">
            {{-- Tab: Daftar Responden --}}
            <div class="tab-pane fade show active" id="list-pane" role="tabpanel">
                <div class="card response-card">
                    <div class="card-body p-0">
                        @if ($responses->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa-duotone fa-inbox fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Responden</h5>
                                <p class="text-secondary">Belum ada peserta yang mengisi kuesioner ini.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-responses table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3">#</th>
                                            <th class="px-4 py-3">Waktu Isi</th>
                                            @if ($event->closingForm)
                                                @foreach ($event->closingForm->fields->take(4) as $field)
                                                    @if (!in_array($field->type, ['header', 'paragraph']))
                                                        <th class="px-4 py-3">{{ Str::limit($field->label, 20) }}</th>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($responses as $index => $response)
                                            <tr>
                                                <td class="px-4">{{ $index + 1 }}</td>
                                                <td class="px-4">
                                                    <span class="text-muted small">
                                                        {{ $response->created_at->format('d M Y') }}
                                                    </span>
                                                    <br>
                                                    <span class="text-muted small">
                                                        {{ $response->created_at->format('H:i') }}
                                                    </span>
                                                </td>
                                                @if ($event->closingForm)
                                                    @foreach ($event->closingForm->fields->take(4) as $field)
                                                        @if (!in_array($field->type, ['header', 'paragraph']))
                                                            @php
                                                                $item = $response->items
                                                                    ->where('field_name', $field->name)
                                                                    ->first();
                                                                $value = $item ? $item->value : '-';

                                                                // Handle array values
                                                                if (is_string($value) && json_decode($value)) {
                                                                    $decoded = json_decode($value, true);
                                                                    $value = is_array($decoded)
                                                                        ? implode(', ', $decoded)
                                                                        : $value;
                                                                }
                                                            @endphp
                                                            <td class="px-4">{{ Str::limit($value, 25) }}</td>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Tab: Ringkasan per Field --}}
            <div class="tab-pane fade" id="summary-pane" role="tabpanel">
                @if ($event->closingForm && $responses->isNotEmpty())
                    <div class="row g-4">
                        @foreach ($event->closingForm->fields as $field)
                            @if (in_array($field->type, ['header', 'paragraph']))
                                @continue
                            @endif

                            <div class="col-md-6">
                                <div class="card summary-card">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3">{{ $field->label }}</h6>

                                        @php
                                            $answers = $responses->flatMap(function ($r) use ($field) {
                                                return $r->items->where('field_name', $field->name)->pluck('value');
                                            });
                                        @endphp

                                        @if ($answers->isEmpty())
                                            <p class="text-muted small fst-italic">Belum ada jawaban</p>
                                        @else
                                            <div class="bg-white rounded p-3" style="max-height: 200px; overflow-y: auto;">
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
                                                <span class="badge bg-info">{{ $answers->count() }} Jawaban</span>
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
