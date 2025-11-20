@extends('admin.layout')

@section('title', 'Detail Konsultasi')

@push('styles')
    <style>
        /* Design tokens (pattern) */
        :root {
            --brand: #CE9138;
            /* primary gold */
            --brand-dark: #b88027;
            --muted-1: #fafafa;
            --muted-2: #f3f3f3;
            --border: #e6e6e6;
            --surface: #ffffff;
            --text: #222;
            --soft-shadow: 0 6px 18px rgba(15, 15, 15, 0.06);
            --radius-lg: 1rem;
            --radius-md: .75rem;
        }

        /* Global adjustments for this page to match pattern */
        .container-fluid {
            max-width: 1450px;
            margin: 0 auto;
        }

        /* Cards */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--soft-shadow);
            overflow: visible;
        }

        .card .card-header {
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            padding: 0.85rem 1rem;
            font-weight: 600;
        }

        /* Header styles variants for this page */
        .card-header--brand {
            background: linear-gradient(90deg, var(--brand) 0%, var(--brand-dark) 100%);
            color: #fff;
        }

        .card-header--info {
            background: #2db6d6;
            /* soft info — still keep contrast */
            color: #fff;
        }

        .card-header--success {
            background: #28a745;
            color: #fff;
        }

        .card-header--danger {
            background: #dc3545;
            color: #fff;
        }

        .card-header--secondary {
            background: #6c757d;
            color: #fff;
        }

        /* Body spacing */
        .card-body {
            padding: 1.25rem;
        }

        /* Headings */
        h2 {
            color: var(--text);
            font-weight: 700;
            margin-bottom: 0;
        }

        /* Buttons - primary gold & variants */
        .btn-brand {
            background-color: var(--brand);
            color: #fff;
            border: 0;
            border-radius: .75rem;
            padding: .575rem .85rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(206, 145, 56, 0.12);
        }

        .btn-brand:hover {
            background-color: var(--brand-dark);
            color: #fff;
        }

        .btn-outline-brand {
            background: transparent;
            color: var(--brand);
            border: 1px solid rgba(206, 145, 56, 0.16);
            border-radius: .75rem;
            font-weight: 600;
        }

        .btn-soft {
            background: var(--muted-1);
            color: var(--text);
            border-radius: .75rem;
            border: 1px solid var(--border);
        }

        /* Badges — unified */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .6rem;
            border-radius: .6rem;
            font-weight: 600;
            font-size: .85rem;
        }

        .badge-pending {
            background: rgba(220, 53, 69, 0.12);
            color: #b02a37;
        }

        .badge-inprogress {
            background: rgba(13, 110, 253, 0.08);
            color: #0d6efd;
        }

        .badge-answered {
            background: rgba(40, 167, 69, 0.12);
            color: #19692c;
        }

        .badge-closed {
            background: rgba(108, 117, 125, 0.12);
            color: #495057;
        }

        .badge-rejected {
            background: rgba(220, 53, 69, 0.12);
            color: #b02a37;
        }

        /* Rounded content box (for question / answer text area) */
        .content-panel {
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: var(--muted-1);
            padding: 1rem;
            color: var(--text);
        }

        /* Form labels */
        .form-label.fw-bold {
            font-weight: 700;
            color: #333;
            margin-bottom: .4rem;
        }

        /* Sidebar cards spacing */
        .sidebar .card {
            margin-bottom: 1rem;
        }

        /* Chat box */
        #chat-messages {
            max-height: 300px;
            overflow-y: auto;
            padding: .5rem;
            border-radius: .6rem;
            background: #fff;
            border: 1px solid var(--border);
        }

        #chat-messages .message {
            margin-bottom: .5rem;
            padding: .6rem .8rem;
            border-radius: .75rem;
            background: rgba(14, 165, 233, 0.06);
            border: 1px solid rgba(14, 165, 233, 0.08);
        }

        #chat-messages .message.you {
            background: rgba(40, 167, 69, 0.06);
            border-color: rgba(40, 167, 69, 0.08);
        }

        /* Input group adjustments */
        .input-group .form-control {
            border-radius: .6rem 0 0 .6rem;
            border-right: 0;
        }

        .input-group .btn {
            border-radius: 0 .6rem .6rem 0;
        }

        /* File input small */
        input[type="file"] {
            border-radius: .5rem;
        }

        /* Modal customizations */
        .modal-content {
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--soft-shadow);
            overflow: hidden;
        }

        .modal-header {
            padding: .85rem 1rem;
        }

        .modal-footer {
            padding: .75rem 1rem;
        }

        /* Small responsive tweaks */
        @media (max-width: 992px) {
            .container-fluid {
                padding: 0 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Detail Konsultasi</h2>
                    <a href="{{ route('admin.konsultasi') }}" class="btn btn-soft">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Question Section --}}
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header card-header--brand">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i> Pertanyaan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="form-label fw-bold">Subjek Pertanyaan</p>
                            <p class="form-control-plaintext">{{ $consultation->question_subject }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="form-label fw-bold">Isi Pertanyaan</p>
                            <div class="content-panel">
                                {!! nl2br(e($consultation->question_text)) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="form-label fw-bold">Dari</p>
                                <p class="form-control-plaintext">
                                    @if ($consultation->is_anonymous)
                                        <span class="badge badge-status badge-pending">
                                            <i class="fas fa-user-secret"></i> Anonim
                                        </span>
                                    @else
                                        {{ $consultation->question_from }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="form-label fw-bold">Tanggal Ditanya</p>
                                <p class="form-control-plaintext">
                                    {{ $consultation->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="form-label fw-bold">Status</p>
                            <p class="form-control-plaintext">
                                @if ($consultation->status === 'pending')
                                    <span class="badge badge-status badge-pending">
                                        <i class="fas fa-clock"></i> Menunggu Jawaban
                                    </span>
                                @elseif ($consultation->status === 'in_progress')
                                    <span class="badge badge-status badge-inprogress">
                                        <i class="fas fa-hourglass-half"></i> Sedang Diproses
                                    </span>
                                @elseif ($consultation->status === 'answered')
                                    <span class="badge badge-status badge-answered">
                                        <i class="fas fa-check-circle"></i> Sudah Dijawab
                                    </span>
                                @elseif ($consultation->status === 'closed')
                                    <span class="badge badge-status badge-closed">
                                        <i class="fas fa-lock"></i> Ditutup
                                    </span>
                                @elseif ($consultation->status === 'rejected')
                                    <span class="badge badge-status badge-rejected">
                                        <i class="fas fa-ban"></i> Ditolak
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Sidebar --}}
            <div class="col-lg-4 sidebar">
                <div class="card">
                    <div class="card-header card-header--info">
                        <h5 class="mb-0">
                            <i class="fas fa-cog me-2"></i> Aksi
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($consultation->status === 'pending')
                            <button class="btn btn-brand w-100 mb-2" data-bs-toggle="modal" data-bs-target="#answerModal">
                                <i class="fas fa-reply me-1"></i> Beri Jawaban
                            </button>
                            <button class="btn btn-outline-brand w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-1"></i> Tolak
                            </button>
                            <button class="btn btn-soft w-100" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <i class="fas fa-edit me-1"></i> Ubah Status
                            </button>
                        @elseif ($consultation->status === 'in_progress')
                            <button class="btn btn-brand w-100 mb-2" data-bs-toggle="modal" data-bs-target="#answerModal">
                                <i class="fas fa-reply me-1"></i> Beri Jawaban
                            </button>
                            <button class="btn btn-outline-brand w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-1"></i> Tolak
                            </button>
                        @elseif ($consultation->status === 'answered')
                            <button class="btn btn-brand w-100 mb-2" data-bs-toggle="modal" data-bs-target="#closeModal">
                                <i class="fas fa-check me-1"></i> Tandai Selesai
                            </button>
                            <button class="btn btn-outline-brand w-100" data-bs-toggle="modal"
                                data-bs-target="#editAnswerModal">
                                <i class="fas fa-edit me-1"></i> Edit Jawaban
                            </button>
                        @elseif ($consultation->status === 'closed')
                            <p class="text-muted small">Konsultasi telah ditutup</p>
                        @elseif ($consultation->status === 'rejected')
                            <button class="btn btn-brand w-100" data-bs-toggle="modal" data-bs-target="#answerModal">
                                <i class="fas fa-reply me-1"></i> Ubah Jawaban
                            </button>
                        @endif

                        <hr>

                        <form action="{{ route('admin.konsultasi.destroy', $consultation->id) }}" method="POST"
                            class="d-inline-block w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus konsultasi ini?')">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="card">
                    <div class="card-header card-header--secondary">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i> Informasi
                        </h5>
                    </div>
                    <div class="card-body small">
                        <div class="mb-2">
                            <strong>Input oleh:</strong>
                            <br>
                            {{ $consultation->inputter ? $consultation->inputter->name : '-' }}
                        </div>
                        @if ($consultation->answerer)
                            <div class="mb-2">
                                <strong>Dijawab oleh:</strong>
                                <br>
                                {{ $consultation->answerer->name }}
                            </div>
                        @endif
                        @if ($consultation->answered_at)
                            <div class="mb-2">
                                <strong>Waktu Dijawab:</strong>
                                <br>
                                {{ $consultation->answered_at->format('d M Y H:i') }}
                            </div>
                        @endif
                        @if ($consultation->closed_at)
                            <div>
                                <strong>Waktu Ditutup:</strong>
                                <br>
                                {{ $consultation->closed_at->format('d M Y H:i') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Answer Section --}}
        @if ($consultation->answer_text)
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header card-header--success">
                            <h5 class="mb-0">
                                <i class="fas fa-check-circle me-2"></i> Jawaban
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="content-panel">
                                {!! nl2br(e($consultation->answer_text)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Rejection Reason Section --}}
        @if ($consultation->rejection_reason)
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header card-header--danger">
                            <h5 class="mb-0">
                                <i class="fas fa-ban me-2"></i> Alasan Penolakan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="content-panel">
                                {!! nl2br(e($consultation->rejection_reason)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Conclusion Section --}}
        @if ($consultation->conclusion)
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header card-header--info">
                            <h5 class="mb-0">
                                <i class="fas fa-bookmark me-2"></i> Kesimpulan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="content-panel">
                                {!! nl2br(e($consultation->conclusion)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Answer Modal --}}
    <div class="modal fade" id="answerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header card-header--brand">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-reply me-2"></i> Beri Jawaban
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.konsultasi.answer', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jawaban <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('answer_text') is-invalid @enderror" name="answer_text" rows="6" required
                                placeholder="Masukkan jawaban atas pertanyaan...">{{ old('answer_text', $consultation->answer_text) }}</textarea>
                            @error('answer_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">
                            <i class="fas fa-save me-1"></i> Simpan Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(90deg,#ffc107,#ffb300); color:#222;">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle me-2"></i> Tolak Konsultasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.konsultasi.reject', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('rejection_reason') is-invalid @enderror" name="rejection_reason" rows="6"
                                required placeholder="Jelaskan alasan penolakan...">{{ old('rejection_reason', $consultation->rejection_reason) }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-brand">
                            <i class="fas fa-ban me-1"></i> Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Close Modal --}}
    <div class="modal fade" id="closeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header card-header--success">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-check me-2"></i> Tandai Selesai
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.konsultasi.close', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kesimpulan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('conclusion') is-invalid @enderror" name="conclusion" rows="6" required
                                placeholder="Tuliskan kesimpulan konsultasi...">{{ old('conclusion', $consultation->conclusion) }}</textarea>
                            @error('conclusion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">
                            <i class="fas fa-check me-1"></i> Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Answer Modal --}}
    <div class="modal fade" id="editAnswerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header card-header--info">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-edit me-2"></i> Edit Jawaban
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.konsultasi.answer', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jawaban <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('answer_text') is-invalid @enderror" name="answer_text" rows="6" required>{{ old('answer_text', $consultation->answer_text) }}</textarea>
                            @error('answer_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-outline-brand">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Status Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header card-header--info">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-edit me-2"></i> Ubah Status
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.konsultasi.status', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Baru <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="pending" {{ $consultation->status === 'pending' ? 'selected' : '' }}>
                                    Menunggu Jawaban
                                </option>
                                <option value="in_progress"
                                    {{ $consultation->status === 'in_progress' ? 'selected' : '' }}>
                                    Sedang Diproses
                                </option>
                                <option value="answered" {{ $consultation->status === 'answered' ? 'selected' : '' }}>
                                    Sudah Dijawab
                                </option>
                                <option value="closed" {{ $consultation->status === 'closed' ? 'selected' : '' }}>
                                    Ditutup
                                </option>
                                <option value="rejected" {{ $consultation->status === 'rejected' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brand">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/socket.io-client/dist/socket.io.js"></script>
    <script>
        // Inisialisasi Echo.js untuk Reverb (pastikan env terisi)
        try {
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: '{{ env('REVERB_APP_KEY') }}',
                host: '{{ env('REVERB_HOST') }}',
                port: {{ env('REVERB_PORT') ?? 6001 }},
                scheme: '{{ env('REVERB_SCHEME') ?? 'https' }}',
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });

            // Listen pesan baru di channel privat konsultasi
            window.Echo.private('consultation.{{ $consultation->id }}')
                .listen('.ConsultationMessageSent', (e) => {
                    const chatBox = document.getElementById('chat-messages');
                    if (chatBox) {
                        const msg = document.createElement('div');
                        msg.className = 'message';
                        // If the incoming user is the authenticated admin, consider marking 'you'
                        msg.innerHTML = `<strong>${e.user.full_name}:</strong> ${e.message.message}`;
                        chatBox.appendChild(msg);
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
        } catch (err) {
            // silent fail — Reverb not configured
            console.warn('Echo init failed:', err);
        }

        // Fungsi kirim pesan via AJAX
        function sendChatMessage() {
            const input = document.getElementById('chat-input');
            const form = document.getElementById('chat-form');
            const chatBox = document.getElementById('chat-messages');
            if (!input.value.trim()) return;

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Tambah pesan ke UI secara langsung untuk respons cepat
                        const msg = document.createElement('div');
                        msg.className = 'message you';
                        msg.innerHTML =
                            `<strong>{{ auth()->user() ? auth()->user()->name : 'Anda' }}:</strong> ${input.value}`;
                        chatBox.appendChild(msg);
                        chatBox.scrollTop = chatBox.scrollHeight;
                        input.value = '';
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal mengirim pesan. Coba lagi.');
                });
        }
    </script>
@endsection

@push('after-content')
    <div class="card">
        <div class="card-header card-header--info">
            <h5 class="mb-0"><i class="fas fa-comments me-2"></i> Chat Konsultasi</h5>
        </div>
        <div class="card-body">
            <div id="chat-messages">
                @foreach ($consultation->messages as $msg)
                    <div class="message @if (optional($msg->user)->id === auth()->id()) you @endif">
                        <strong>{{ $msg->user->full_name }}:</strong> {{ $msg->message }}
                    </div>
                @endforeach
            </div>

            <form id="chat-form" action="{{ route('client.consultations.send-message', $consultation->id) }}"
                method="POST" enctype="multipart/form-data" onsubmit="event.preventDefault();sendChatMessage();">
                @csrf
                <div class="input-group mt-3">
                    <input type="text" id="chat-input" name="message" class="form-control"
                        placeholder="Ketik pesan..." maxlength="5000" required>
                    <button class="btn btn-brand" type="submit"><i class="fas fa-paper-plane me-1"></i> Kirim</button>
                </div>
                <div class="mt-2">
                    <input type="file" name="attachment" class="form-control form-control-sm">
                    <small class="text-muted">File maksimal 5MB</small>
                </div>
            </form>
        </div>
    </div>
@endpush
