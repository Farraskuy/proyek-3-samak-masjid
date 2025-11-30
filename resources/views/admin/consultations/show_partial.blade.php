<div class="d-flex flex-column h-100 bg-white">
    <div class="border-bottom p-3 bg-white">
        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold fs-4 me-3" style="width: 56px; height: 56px;">
                    {{ substr($consultation->user->full_name ?? 'H', 0, 1) }}
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">{{ $consultation->user->full_name ?? 'Hamba Allah' }}</h5>
                    <div class="small text-muted">
                        {{ $consultation->user->email ?? 'No email' }} &bull;
                        {{ $consultation->user->phone ?? 'No phone' }}
                    </div>
                </div>
            </div>
            <div>
                @if ($consultation->status == 'pending')
                    <form action="{{ route('admin.consultations.accept', $consultation->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm fw-semibold">
                            <i class="fas fa-check me-1"></i> Terima
                        </button>
                    </form>
                @elseif ($consultation->status == 'active' && $consultation->answered_by_ustadz_id == Auth::id())
                    <!-- Tombol Selesai -->
                    <button type="button" 
                            class="btn btn-success btn-sm fw-semibold"
                            data-bs-toggle="modal"
                            data-bs-target="#closeConsultationModal"
                            data-id="{{ $consultation->id }}">
                        <i class="fas fa-check me-1"></i> Selesai
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="flex-grow-1 overflow-auto p-4 bg-light" id="adminChatContainer" style="background-color: #f8f9fa;">
        <div class="text-center mb-4">
            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                {{ $consultation->created_at->format('F d, Y') }}
            </span>
        </div>

        <div class="d-flex mb-3">
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold small me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                {{ substr($consultation->user->full_name ?? 'H', 0, 1) }}
            </div>
            <div class="d-flex flex-column align-items-start" style="max-width: 70%;">
                <div class="d-flex align-items-baseline mb-1">
                    <span class="fw-bold text-dark small me-2">{{ $consultation->user->full_name ?? 'Hamba Allah' }}</span>
                    <span class="text-muted small" style="font-size: 0.7rem;">{{ $consultation->created_at->format('g:i A') }}</span>
                </div>
                <div class="bg-white p-3 rounded-3 shadow-sm text-dark">
                    <p class="mb-0">{{ $consultation->question_text }}</p>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-center my-4">
            <div class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2 fw-normal border border-info border-opacity-25">
                <i class="fas fa-user-check me-1"></i> Assigned to Ustadz
            </div>
        </div>

        @foreach ($messages as $msg)
            @php $isMe = $msg->user_id == Auth::id(); @endphp
            <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : '' }}">
                @if (!$isMe)
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold small me-2 flex-shrink-0" style="width: 32px; height: 32px;">
                        {{ substr($consultation->user->full_name ?? 'H', 0, 1) }}
                    </div>
                @endif
                <div class="d-flex flex-column {{ $isMe ? 'align-items-end' : 'align-items-start' }}" style="max-width: 70%;">
                    <div class="d-flex align-items-baseline mb-1">
                        @if ($isMe)
                            <span class="text-muted small me-2" style="font-size: 0.7rem;">{{ $msg->created_at->format('g:i A') }}</span>
                            <span class="fw-bold text-dark small">You</span>
                        @else
                            <span class="fw-bold text-dark small me-2">{{ $consultation->user->full_name ?? 'Hamba Allah' }}</span>
                            <span class="text-muted small" style="font-size: 0.7rem;">{{ $msg->created_at->format('g:i A') }}</span>
                        @endif
                    </div>
                    <div class="p-3 rounded-3 shadow-sm {{ $isMe ? 'bg-primary text-white' : 'bg-white text-dark' }}">
                        <p class="mb-0">{{ $msg->message }}</p>
                    </div>
                </div>
                @if ($isMe)
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold small ms-2 flex-shrink-0" style="width: 32px; height: 32px;">U</div>
                @endif
            </div>
        @endforeach
    </div>

    @if ($consultation->status == 'active')
        <div class="p-3 bg-white border-top">
            <form id="admin-chat-form">
                @csrf
                <div class="border rounded-3 overflow-hidden">
                    <textarea name="message" id="admin-message-input" class="form-control border-0 shadow-none" rows="2"
                        placeholder="Balas pertanyaan..." required style="resize: none; height: 50px;"></textarea>
                    <div class="d-flex justify-content-end p-2">
                        <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3 rounded-pill">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    @elseif($consultation->status == 'pending')
        <div class="p-4 bg-white border-top text-center">
            <p class="text-muted mb-3">Konsultasi ini menunggu persetujuan Anda.</p>
        </div>
    @else
        <div class="p-4 bg-white border-top text-center">
            <p class="text-muted mb-0"><i class="fas fa-lock me-2"></i> Konsultasi telah ditutup.</p>
        </div>
    @endif
</div>