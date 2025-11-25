@extends('admin.layout')

@section('title', 'Manajemen Konsultasi')


@push('styles')
    <style>
        .width-20 {
            width: 20px;
            text-align: center;
        }

        .nav-pills .nav-link {
            color: #64748b;
            border-radius: 8px;
            padding: 10px 15px;
        }

        .nav-pills .nav-link:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .nav-pills .nav-link.active {
            background-color: #e0f2fe;
            /* Light blue */
            color: #0284c7;
            /* Primary blue */
        }

        .bg-primary-subtle {
            background-color: #f0f9ff !important;
            /* Lighter blue for active list item */
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid p-3 h-100">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="min-height: 85vh;">
            <div class="row g-0 h-100">
                <!-- Section 1: Status Filters (Sidebar) -->
                <div class="col-md-2 col-lg-2 border-end h-100 bg-white d-flex flex-column py-3">
                    <h5 class="px-3 fw-bold mb-4">Konsultasi</h5>

                    <div class="nav flex-column nav-pills px-2" role="tablist" aria-orientation="vertical">
                        <a href="{{ route('admin.consultations.index', ['status' => 'all']) }}"
                            class="nav-link mb-2 text-start d-flex justify-content-between align-items-center {{ request('status') == 'all' || !request('status') ? 'active fw-bold' : 'text-muted' }}">
                            <span><i class="fas fa-inbox me-2 width-20"></i> Semua</span>
                            <span class="badge bg-light text-dark rounded-pill">{{ $counts['all'] ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.consultations.index', ['status' => 'pending']) }}"
                            class="nav-link mb-2 text-start d-flex justify-content-between align-items-center {{ request('status') == 'pending' ? 'active fw-bold' : 'text-muted' }}">
                            <span><i class="fas fa-clock me-2 width-20"></i> Pending</span>
                            @if (($counts['pending'] ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill">{{ $counts['pending'] ?? 0 }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.consultations.index', ['status' => 'active']) }}"
                            class="nav-link mb-2 text-start d-flex justify-content-between align-items-center {{ request('status') == 'active' ? 'active fw-bold' : 'text-muted' }}">
                            <span><i class="fas fa-comments me-2 width-20"></i> Aktif</span>
                            <span class="badge bg-light text-dark rounded-pill">{{ $counts['active'] ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.consultations.index', ['status' => 'closed']) }}"
                            class="nav-link mb-2 text-start d-flex justify-content-between align-items-center {{ request('status') == 'closed' ? 'active fw-bold' : 'text-muted' }}">
                            <span><i class="fas fa-check-circle me-2 width-20"></i> Selesai</span>
                            <span class="badge bg-light text-dark rounded-pill">{{ $counts['closed'] ?? 0 }}</span>
                        </a>
                    </div>
                </div>

                <!-- Section 2: User List (Middle) -->
                <div class="col-md-4 col-lg-3 border-end h-100 d-flex flex-column bg-white">
                    <!-- Search Header -->
                    <div class="p-3 border-bottom">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute text-muted"
                                style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                            <input type="text" class="form-control ps-5 rounded-pill bg-light border-0"
                                placeholder="Cari orang...">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted fw-bold text-uppercase"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                {{ request('status') ? ucfirst(request('status')) : 'Semua' }}
                                ({{ $consultations->count() }})
                            </small>
                        </div>
                    </div>

                    <!-- Conversation List -->
                    <div class="flex-grow-1 overflow-auto">
                        <div class="list-group list-group-flush">
                            @forelse ($consultations as $item)
                                <a href="{{ route('admin.consultations.show', ['id' => $item->id, 'status' => request('status')]) }}"
                                    class="list-group-item list-group-item-action border-0 p-3 {{ request()->route('id') == $item->id ? 'bg-primary-subtle border-start border-4 border-primary' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative me-3">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                                style="width: 40px; height: 40px;">
                                                {{ substr($item->user->full_name ?? 'H', 0, 1) }}
                                            </div>
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-baseline mb-1">
                                                <h6 class="mb-0 fw-bold text-dark fs-14px">
                                                    {{ $item->user->full_name ?? 'Hamba Allah' }}</h6>
                                                <small class="text-muted"
                                                    style="font-size: 0.75rem;">{{ $item->created_at->diffForHumans(null, true, true) }}</small>
                                            </div>
                                            <p class="mb-0 text-muted small text-truncate">
                                                {{ $item->user->email ?? 'No email' }}</p>
                                            <p class="mb-0 text-dark small text-truncate mt-1">
                                                {{ $item->question_subject }}</p>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center p-4 text-muted">
                                    <small>Tidak ada data</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Section 3: Chat Area (Right) -->
                <div class="col-md-6 col-lg-7 h-100 bg-light">
                    @if (isset($consultation))
                        @include('admin.consultations.show_partial', [
                            'consultation' => $consultation,
                            'messages' => $messages,
                        ])
                    @else
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
                            <div class="mb-4">
                                <div class="bg-white rounded-circle p-4 shadow-sm d-inline-block">
                                    <i class="fas fa-comments fa-3x text-primary opacity-50"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark">Pilih Percakapan</h5>
                            <p class="text-muted">Pilih percakapan dari daftar untuk mulai chatting.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
