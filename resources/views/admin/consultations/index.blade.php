@extends('admin.layout')

@section('title', 'Manajemen Konsultasi')

@section('content')
    <div class="container-fluid p-3 h-100">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="min-height: 85vh;">
            <div class="row g-0 h-100">
                <!-- Section 1: Status Filters (Sidebar) -->
                <div class="col-md-2 col-lg-2 border-end h-100 bg-white d-flex flex-column py-3">
                    <h5 class="px-3 fw-bold mb-4">Konsultasi</h5>

                    <div class="space-y-2 px-3">
                        <a href="{{ route('admin.consultations.index', ['status' => 'all']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'all' || !request('status') ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-inbox me-2" style="width: 20px; text-align: center;"></i>
                                <span class="fw-medium">Semua</span>
                            </div>
                            <span
                                class="badge {{ request('status') == 'all' || !request('status') ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">{{ $counts['all'] ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.consultations.index', ['status' => 'pending']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'pending' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock me-2" style="width: 20px; text-align: center;"></i>
                                <span class="fw-medium">Pending</span>
                            </div>
                            @if (($counts['pending'] ?? 0) > 0)
                                <span class="badge bg-danger rounded-pill">{{ $counts['pending'] ?? 0 }}</span>
                            @else
                                <span
                                    class="badge {{ request('status') == 'pending' ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">0</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.consultations.index', ['status' => 'active']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'active' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-comments me-2" style="width: 20px; text-align: center;"></i>
                                <span class="fw-medium">Aktif</span>
                            </div>
                            <span
                                class="badge {{ request('status') == 'active' ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">{{ $counts['active'] ?? 0 }}</span>
                        </a>

                        <a href="{{ route('admin.consultations.index', ['status' => 'closed']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'closed' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2" style="width: 20px; text-align: center;"></i>
                                <span class="fw-medium">Selesai</span>
                            </div>
                            <span
                                class="badge {{ request('status') == 'closed' ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">{{ $counts['closed'] ?? 0 }}</span>
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
                                <div class="list-group-item list-group-item-action border-0 p-4 border-bottom cursor-pointer {{ request()->route('id') == $item->id ? 'bg-blue-50' : '' }}"
                                    onclick="loadChat({{ $item->id }}, this)">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative me-3">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                                style="width: 45px; height: 45px; font-size: 1.2rem;">
                                                {{ substr($item->user->full_name ?? 'H', 0, 1) }}
                                            </div>
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                    <span class="visually-hidden">New alerts</span>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0">
                                                        {{ $item->question_from ?? 'Anonim' }}</h6>
                                                    <small class="text-muted"
                                                        style="font-size: 0.75rem;">{{ $item->user->email ?? '-' }}</small>
                                                </div>
                                                <span class="text-muted small"
                                                    style="font-size: 0.7rem;">{{ $item->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-muted small mb-2 text-truncate" style="max-width: 200px;">
                                                {{ $item->question_subject }}
                                            </p>

                                            @if ($item->status === 'pending')
                                                <div class="d-flex gap-2 mt-2">
                                                    <form action="{{ route('admin.consultations.accept', $item->id) }}"
                                                        method="POST" onsubmit="return confirm('Terima konsultasi ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm py-0 px-2"
                                                            style="font-size: 0.75rem;">
                                                            <i class="fas fa-check me-1"></i> Terima
                                                        </button>
                                                    </form>
                                                    <button type="button" onclick="openRejectModal({{ $item->id }})"
                                                        class="btn btn-outline-danger btn-sm py-0 px-2"
                                                        style="font-size: 0.75rem;">
                                                        <i class="fas fa-times me-1"></i> Tolak
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-4 text-muted">
                                    <small>Tidak ada data</small>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Section 3: Chat Area (Right) -->
                <div class="col-md-6 col-lg-7 h-100 bg-light position-relative" id="chat-area-container">
                    <div id="loading-spinner" class="position-absolute top-50 start-50 translate-middle d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div id="chat-content" class="h-100">
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
    </div>

    <!-- Reject Modal (Global) -->
    <div class="modal fade" id="globalRejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="globalRejectForm" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Tolak Konsultasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="fw-semibold mb-2">Alasan Penolakan</label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Jelaskan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function loadChat(id, element) {
                // Highlight active item
                document.querySelectorAll('.list-group-item').forEach(el => {
                    el.classList.remove('bg-blue-50', 'border-start', 'border-4', 'border-primary');
                });
                if (element) {
                    element.classList.add('bg-blue-50', 'border-start', 'border-4', 'border-primary');
                }

                // Show loading
                document.getElementById('loading-spinner').classList.remove('d-none');
                document.getElementById('chat-content').style.opacity = '0.5';

                // Fetch chat
                fetch(`{{ url('/admin/konsultasi') }}/${id}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('chat-content').innerHTML = html;
                        document.getElementById('chat-content').style.opacity = '1';
                        document.getElementById('loading-spinner').classList.add('d-none');

                        // Scroll to bottom
                        var chatDiv = document.getElementById("adminChatContainer");
                        if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;

                        // Update URL without reload
                        const url = new URL(window.location);
                        url.pathname = `/admin/konsultasi/${id}`;
                        window.history.pushState({}, '', url);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memuat chat.');
                        document.getElementById('loading-spinner').classList.add('d-none');
                        document.getElementById('chat-content').style.opacity = '1';
                    });
            }

            function openRejectModal(id) {
                const form = document.getElementById('globalRejectForm');
                form.action = `{{ url('/admin/konsultasi') }}/${id}/reject`;
                const modal = new bootstrap.Modal(document.getElementById('globalRejectModal'));
                modal.show();
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .hover-bg-light:hover {
                background-color: #f8f9fa;
            }

            .bg-blue-50 {
                background-color: #eff6ff !important;
            }
        </style>
    @endpush
@endsection
