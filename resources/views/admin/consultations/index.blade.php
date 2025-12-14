@extends('admin.layout')

@section('title', 'Manajemen Konsultasi')

@section('content')
    <div class="container-fluid p-3 h-100">
        <div class="card shadow-sm border-0 h-100 overflow-hidden" style="min-height: 85vh;">
            <div class="row g-0 h-100">
                <div class="col-md-2 col-lg-2 border-end h-100 bg-white d-flex flex-column py-3">
                    <h5 class="px-3 fw-bold mb-4">Konsultasi</h5>
                    <div class="space-y-2 px-3">
                        <a href="{{ route('admin.consultations.index', ['status' => 'all']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'all' || !request('status') ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center"><i class="fas fa-inbox me-2"
                                    style="width: 20px; text-align: center;"></i><span class="fw-medium">Semua</span></div>
                            <span
                                class="badge {{ request('status') == 'all' || !request('status') ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">{{ $counts['all'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.consultations.index', ['status' => 'pending']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'pending' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center"><i class="fas fa-clock me-2"
                                    style="width: 20px; text-align: center;"></i><span class="fw-medium">Pending</span>
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
                            <div class="d-flex align-items-center"><i class="fas fa-comments me-2"
                                    style="width: 20px; text-align: center;"></i><span class="fw-medium">Aktif</span></div>
                            <span
                                class="badge {{ request('status') == 'active' ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">{{ $counts['active'] ?? 0 }}</span>
                        </a>
                        <a href="{{ route('admin.consultations.index', ['status' => 'closed']) }}"
                            class="d-flex align-items-center justify-content-between px-3 py-2 rounded-3 text-decoration-none {{ request('status') == 'closed' ? 'bg-dark text-white shadow-sm' : 'text-secondary hover-bg-light' }}">
                            <div class="d-flex align-items-center"><i class="fas fa-check-circle me-2"
                                    style="width: 20px; text-align: center;"></i><span class="fw-medium">Selesai</span>
                            </div>
                            <span
                                class="badge {{ request('status') == 'closed' ? 'bg-secondary' : 'bg-light text-dark' }} rounded-pill">{{ $counts['closed'] ?? 0 }}</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3 border-end h-100 d-flex flex-column bg-white">
                    <div class="p-3 border-bottom">
                        <div class="position-relative">
                            <i class="fas fa-search position-absolute text-muted"
                                style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                            <input type="text" class="form-control ps-5 rounded-pill bg-light border-0"
                                placeholder="Cari orang...">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted fw-bold text-uppercase"
                                style="font-size: 0.75rem; letter-spacing: 0.5px;">{{ request('status') ? ucfirst(request('status')) : 'Semua' }}
                                ({{ $consultations->count() }})</small>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-auto">
                        <div class="list-group list-group-flush">
                            @forelse ($consultations as $item)
                                <div class="list-group-item list-group-item-action border-0 p-4 border-bottom cursor-pointer {{ request()->route('id') == $item->id ? 'bg-blue-50' : '' }}"
                                    onclick="loadChat({{ $item->id }}, this)">
                                    <div class="d-flex align-items-start">
                                        <div class="position-relative me-3">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                                style="width: 45px; height: 45px; font-size: 1.2rem;">
                                                {{ substr($item->user->full_name ?? 'H', 0, 1) }}</div>
                                            @if ($item->status == 'pending')
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"><span
                                                        class="visually-hidden">New alerts</span></span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0">
                                                        {{ $item->question_from ?? 'Anonim' }}</h6><small
                                                        class="text-muted"
                                                        style="font-size: 0.75rem;">{{ $item->user->email ?? '-' }}</small>
                                                </div>
                                                <span class="text-muted small"
                                                    style="font-size: 0.7rem;">{{ $item->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-muted small mb-2 text-truncate" style="max-width: 200px;">
                                                {{ $item->question_subject }}</p>
                                            @if ($item->status === 'pending')
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" class="btn btn-success btn-sm py-0 px-2"
                                                        style="font-size: 0.75rem;"
                                                        onclick="showConfirmModal({
                                                action: '{{ route('admin.consultations.accept', $item->id) }}',
                                                method: 'POST',
                                                type: 'accept',
                                                title: 'Terima Konsultasi',
                                                message: 'Apakah Anda yakin ingin menerima konsultasi ini?',
                                                buttonText: 'Ya, Terima'
                                            })">
                                                        <i class="fas fa-check me-1"></i> Terima
                                                    </button>
                                                    <button type="button" onclick="openRejectModal({{ $item->id }})"
                                                        class="btn btn-outline-danger btn-sm py-0 px-2"
                                                        style="font-size: 0.75rem;"><i class="fas fa-times me-1"></i>
                                                        Tolak</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center p-4 text-muted"><small>Tidak ada data</small></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-7 h-100 bg-light position-relative" id="chat-area-container">
                    <div id="loading-spinner" class="position-absolute top-50 start-50 translate-middle d-none">
                        <div class="spinner-border text-primary" role="status"><span
                                class="visually-hidden">Loading...</span></div>
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
                                    <div class="bg-white rounded-circle p-4 shadow-sm d-inline-block"><i
                                            class="fas fa-comments fa-3x text-primary opacity-50"></i></div>
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

    <div class="modal fade" id="closeConsultationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> Tutup Konsultasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menutup konsultasi ini?</p>
                    <p class="text-muted">Setelah ditutup, Anda tidak bisa mengirim pesan lagi.</p>
                    <input type="hidden" id="consultationId" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmCloseBtn" class="btn btn-danger">Ya, Tutup Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.17.0/echo.iife.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            let echoInstance = null;
            let currentConsultationId = {{ isset($consultation) ? $consultation->id : 'null' }};
            const currentAdminId = {{ Auth::id() }};

            function initEcho(consultationId) {
                if (echoInstance) {
                    echoInstance.leave(`consultation.${consultationId}`);
                }

                currentConsultationId = consultationId;

                // Setup Echo
                echoInstance = new Echo({
                    broadcaster: 'reverb',
                    key: "{{ env('REVERB_APP_KEY') }}",
                    wsHost: "{{ env('REVERB_HOST', '127.0.0.1') }}",
                    wsPort: {{ env('REVERB_PORT', 8080) }},
                    wssPort: {{ env('REVERB_PORT', 443) }},
                    forceTLS: {{ env('REVERB_SCHEME', 'http') === 'https' ? 'true' : 'false' }},
                    enabledTransports: ['ws', 'wss'],
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }
                });

                console.log('Humas listening to: consultation.' + consultationId);

                echoInstance.private(`consultation.${consultationId}`)
                    .listen('.message.sent', (e) => {
                        console.log('Event Received:', e);

                        if (e.message.consultation_id != currentConsultationId) {
                            console.log('Message for different consultation, ignoring.');
                            return;
                        }
                        if (e.user.id != currentAdminId) {
                            appendMessageToChat(e.message, e.user);
                            scrollToBottom();
                        }
                    });
            }

            function appendMessageToChat(message, user) {
                const isMe = user.id == currentAdminId;
                const time = new Date(message.created_at).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                let attachmentHtml = '';
                if (message.message_type === 'file' && message.attachment_url) {
                    const color = isMe ? 'text-white' : 'text-primary';
                    attachmentHtml =
                        `<a href="/${message.attachment_url}" target="_blank" class="${color} text-decoration-none"><i class="fas fa-paperclip me-1"></i> Lampiran</a><br>`;
                }

                const html = `
            <div class="d-flex mb-3 ${isMe ? 'justify-content-end' : ''}">
                ${!isMe ? `
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold small me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                            ${(user.name || user.full_name || 'U').charAt(0).toUpperCase()}
                        </div>` : ''}
                <div class="d-flex flex-column ${isMe ? 'align-items-end' : 'align-items-start'}" style="max-width: 70%;">
                    <div class="d-flex align-items-baseline mb-1">
                        <span class="fw-bold text-dark small me-2">${isMe ? 'You' : (user.name || user.full_name || 'Pengguna')}</span>
                        <span class="text-muted small" style="font-size: 0.7rem;">${time}</span>
                    </div>
                    <div class="p-3 rounded-3 shadow-sm ${isMe ? 'bg-primary text-white' : 'bg-white text-dark'}">
                        <p class="mb-0">${attachmentHtml} ${message.message}</p>
                    </div>
                </div>
                ${isMe ? `
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold small ms-2 flex-shrink-0" style="width: 32px; height: 32px;">U</div>
                        ` : ''}
            </div>
        `;

                const container = document.getElementById('adminChatContainer');
                if (container) {
                    container.insertAdjacentHTML('beforeend', html);
                }
            }

            function scrollToBottom() {
                const container = document.getElementById('adminChatContainer');
                if (container) container.scrollTop = container.scrollHeight;
            }

            function loadChat(id, element) {
                currentConsultationId = id;
                document.querySelectorAll('.list-group-item').forEach(el => {
                    el.classList.remove('bg-blue-50', 'border-start', 'border-4', 'border-primary');
                });
                if (element) {
                    element.classList.add('bg-blue-50', 'border-start', 'border-4', 'border-primary');
                }

                document.getElementById('loading-spinner').classList.remove('d-none');
                document.getElementById('chat-content').style.opacity = '0.5';

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

                        const chatDiv = document.getElementById("adminChatContainer");
                        if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;

                        const url = new URL(window.location);
                        url.pathname = `/admin/konsultasi/${id}`;
                        window.history.pushState({}, '', url);

                        initEcho(id);
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal memuat chat.');
                        document.getElementById('loading-spinner').classList.add('d-none');
                        document.getElementById('chat-content').style.opacity = '1';
                    });
            }

            // Modal Helpers
            function openRejectModal(id) {
                const form = document.getElementById('globalRejectForm');
                form.action = `{{ url('/admin/konsultasi') }}/${id}/reject`;
                const modal = new bootstrap.Modal(document.getElementById('globalRejectModal'));
                modal.show();
            }

            document.addEventListener('DOMContentLoaded', function() {
                let consultationIdToClose = null;
                const closeConsultationModal = document.getElementById('closeConsultationModal');
                if (closeModalEl = document.getElementById('closeConsultationModal')) {
                    closeModalEl.addEventListener('show.bs.modal', function(e) {
                        consultationIdToClose = e.relatedTarget.getAttribute('data-id');
                        document.getElementById('consultationId').value = consultationIdToClose;
                    });
                }
                const confirmBtn = document.getElementById('confirmCloseBtn');
                if (confirmBtn) {
                    confirmBtn.addEventListener('click', function() {
                        if (!consultationIdToClose) return;
                        const url = `/admin/konsultasi/${consultationIdToClose}/close`;
                        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    bootstrap.Modal.getInstance(document.getElementById(
                                        'closeConsultationModal')).hide();
                                    window.location.href = '/admin/konsultasi?status=closed';
                                } else {
                                    alert('Gagal menutup konsultasi.');
                                }
                            })
                            .catch(err => {
                                console.error('Error:', err);
                                alert('Terjadi kesalahan.');
                            });
                    });
                }
            });

            @if (isset($consultation))
                document.addEventListener('DOMContentLoaded', () => {
                    initEcho({{ $consultation->id }});
                });
            @endif

            document.addEventListener('submit', function(e) {
                if (e.target && e.target.id === 'admin-chat-form') {
                    e.preventDefault();
                    const input = e.target.querySelector('textarea[name="message"]');
                    const message = input.value.trim();
                    if (!message) return;

                    const formData = new FormData(e.target);

                    axios.post(`/admin/konsultasi/${currentConsultationId}/pesan`, formData)
                        .then(response => {
                            if (response.data.success) {
                                const user = {{ Js::from(Auth::user()) }};
                                appendMessageToChat(response.data.message, user);
                                input.value = '';
                            } else {
                                alert(response.data.error);
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Gagal mengirim pesan.');
                        });
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.target && e.target.name === 'message' && e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const form = e.target.closest('form');
                    if (form) form.dispatchEvent(new Event('submit', {
                        bubbles: true
                    }));
                }
            });
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
