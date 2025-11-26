<div class="d-flex flex-column h-100 bg-white">
    <!-- Chat Header -->
    <div class="border-bottom p-3 bg-white">
        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold fs-4 me-3"
                    style="width: 56px; height: 56px;">
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

            <!-- Actions -->
            <div>
                @if ($consultation->status == 'pending')
                    <form action="{{ route('admin.consultations.accept', $consultation->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm fw-semibold">
                            <i class="fas fa-check me-1"></i> Accept
                        </button>
                    </form>
                    <button type="button" class="btn btn-outline-danger btn-sm fw-semibold ms-1" data-toggle="modal"
                        data-target="#rejectModal">
                        Reject
                    </button>
                @elseif($consultation->status == 'active')
                    <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-toggle="modal"
                        data-target="#closeModal">
                        <i class="fas fa-check-circle me-1"></i> Mark as Resolved
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Chat Body -->
    <div class="flex-grow-1 overflow-auto p-4 bg-light" id="adminChatContainer" style="background-color: #f8f9fa;">

        <!-- Date Separator -->
        <div class="text-center mb-4">
            <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                {{ $consultation->created_at->format('F d, Y') }}
            </span>
        </div>

        <!-- Initial Question (Incoming) -->
        <div class="d-flex mb-3">
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold small me-2 flex-shrink-0"
                style="width: 32px; height: 32px;">
                {{ substr($consultation->user->full_name ?? 'H', 0, 1) }}
            </div>
            <div class="d-flex flex-column align-items-start" style="max-width: 70%;">
                <div class="d-flex align-items-baseline mb-1">
                    <span
                        class="fw-bold text-dark small me-2">{{ $consultation->user->full_name ?? 'Hamba Allah' }}</span>
                    <span class="text-muted small"
                        style="font-size: 0.7rem;">{{ $consultation->created_at->format('g:i A') }}</span>
                </div>
                <div class="bg-white p-3 rounded-3 shadow-sm text-dark">
                    <p class="mb-0">{{ $consultation->question_text }}</p>
                </div>
            </div>
        </div>

        <!-- Assigned Separator -->
        <div class="d-flex align-items-center justify-content-center my-4">
            <div
                class="badge rounded-pill bg-info bg-opacity-10 text-info px-3 py-2 fw-normal border border-info border-opacity-25">
                <i class="fas fa-code me-1"></i> Assigned to Ustadz
            </div>
        </div>

        @foreach ($messages as $msg)
            @php
                $isMe = $msg->user_id == Auth::id();
            @endphp
            <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : '' }}">
                @if (!$isMe)
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold small me-2 flex-shrink-0"
                        style="width: 32px; height: 32px;">
                        {{ substr($consultation->user->full_name ?? 'H', 0, 1) }}
                    </div>
                @endif

                <div class="d-flex flex-column {{ $isMe ? 'align-items-end' : 'align-items-start' }}"
                    style="max-width: 70%;">
                    <div class="d-flex align-items-baseline mb-1">
                        @if ($isMe)
                            <span class="text-muted small me-2"
                                style="font-size: 0.7rem;">{{ $msg->created_at->format('g:i A') }}</span>
                            <span class="fw-bold text-dark small">You</span>
                        @else
                            <span
                                class="fw-bold text-dark small me-2">{{ $consultation->user->full_name ?? 'Hamba Allah' }}</span>
                            <span class="text-muted small"
                                style="font-size: 0.7rem;">{{ $msg->created_at->format('g:i A') }}</span>
                        @endif
                    </div>
                    <div class="p-3 rounded-3 shadow-sm {{ $isMe ? 'bg-primary-subtle text-dark' : 'bg-white text-dark' }}"
                        style="{{ $isMe ? 'background-color: #e3f2fd;' : '' }}">
                        <p class="mb-0">{{ $msg->message }}</p>
                    </div>
                </div>

                @if ($isMe)
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold small ms-2 flex-shrink-0"
                        style="width: 32px; height: 32px;">
                        A
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Chat Input -->
    @if ($consultation->status == 'active')
        <div class="p-3 bg-white border-top">
            <form action="{{ route('client.consultations.send-message', $consultation->id) }}" method="POST">
                @csrf
                <div class="border rounded-3 overflow-hidden">
                    <div class="p-2 border-bottom bg-light d-flex gap-2 text-muted">
                        <button type="button" class="btn btn-sm btn-link text-muted p-0 px-1"><i
                                class="far fa-smile"></i></button>
                        <button type="button" class="btn btn-sm btn-link text-muted p-0 px-1"><i
                                class="fas fa-bold"></i></button>
                        <button type="button" class="btn btn-sm btn-link text-muted p-0 px-1"><i
                                class="fas fa-italic"></i></button>
                        <button type="button" class="btn btn-sm btn-link text-muted p-0 px-1"><i
                                class="fas fa-list-ul"></i></button>
                        <button type="button" class="btn btn-sm btn-link text-muted p-0 px-1"><i
                                class="fas fa-link"></i></button>
                        <button type="button" class="btn btn-sm btn-link text-muted p-0 px-1"><i
                                class="fas fa-paperclip"></i></button>
                    </div>
                    <textarea name="message" class="form-control border-0 shadow-none" rows="2"
                        placeholder="Message {{ $consultation->user->full_name ?? 'User' }}..." required style="resize: none;"></textarea>
                    <div class="d-flex justify-content-between align-items-center p-2 bg-white">
                        <div class="form-check mb-0">
                            <!-- Optional checkbox if needed -->
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm fw-semibold px-3 rounded-pill">
                            Send Message
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @elseif($consultation->status == 'pending')
        <div class="p-4 bg-white border-top text-center">
            <p class="text-muted mb-3">This consultation is pending approval.</p>
            <form action="{{ route('admin.consultations.accept', $consultation->id) }}" method="POST"
                class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success fw-semibold px-4">
                    Accept Request
                </button>
            </form>
        </div>
    @else
        <div class="p-4 bg-white border-top text-center">
            <p class="text-muted mb-0"><i class="fas fa-lock me-2"></i> This consultation has been closed.</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.consultations.reject', $consultation->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Reject Consultation</h5>
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-semibold mb-2">Reason for Rejection</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Please explain why..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Close Modal -->
<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.consultations.close', $consultation->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Close Consultation</h5>
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="fw-semibold mb-2">Conclusion / Final Notes</label>
                        <textarea name="conclusion" class="form-control" rows="4" required
                            placeholder="Summarize the consultation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save & Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var chatDiv = document.getElementById("adminChatContainer");
    if (chatDiv) chatDiv.scrollTop = chatDiv.scrollHeight;
</script>
