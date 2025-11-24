@extends('client.layout')

@section('title', 'Chat Konsultasi')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm border-0" style="height: 80vh;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">{{ $consultation->question_subject }}</h5>
                    <small class="text-muted">
                        Status: <span
                            class="badge bg-{{ $consultation->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($consultation->status) }}</span>
                        @if ($consultation->ustadz)
                            | Bersama: {{ $consultation->ustadz->full_name }}
                        @endif
                    </small>
                </div>
                <a href="{{ route('client.consultations.history') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card-body overflow-auto bg-light" id="chatContainer">
                <!-- Initial Question -->
                <div class="d-flex justify-content-end mb-4">
                    <div class="card border-0 shadow-sm bg-primary text-white" style="max-width: 75%;">
                        <div class="card-body p-3">
                            <p class="mb-0">{{ $consultation->question_text }}</p>
                            <small
                                class="text-white-50 d-block text-end mt-1">{{ $consultation->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                </div>

                @foreach ($messages as $msg)
                    <div class="d-flex justify-content-{{ $msg->user_id == Auth::id() ? 'end' : 'start' }} mb-3">
                        @if ($msg->user_id != Auth::id())
                            <div class="me-2">
                                <img src="{{ $msg->user->image_url ?? asset('assets/images/default-avatar.png') }}"
                                    class="rounded-circle" width="35" height="35">
                            </div>
                        @endif

                        <div class="card border-0 shadow-sm {{ $msg->user_id == Auth::id() ? 'bg-primary text-white' : 'bg-white' }}"
                            style="max-width: 75%;">
                            <div class="card-body p-3">
                                @if ($msg->message_type == 'file')
                                    <a href="{{ asset($msg->attachment_url) }}" target="_blank"
                                        class="{{ $msg->user_id == Auth::id() ? 'text-white' : 'text-primary' }}">
                                        <i class="fas fa-paperclip me-1"></i>Lampiran
                                    </a>
                                    <p class="mb-0 mt-2">{{ $msg->message }}</p>
                                @else
                                    <p class="mb-0">{{ $msg->message }}</p>
                                @endif
                                <small
                                    class="{{ $msg->user_id == Auth::id() ? 'text-white-50' : 'text-muted' }} d-block text-end mt-1">
                                    {{ $msg->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($consultation->status == 'active')
                <div class="card-footer bg-white py-3">
                    <form id="chatForm">
                        <div class="input-group">
                            <input type="text" class="form-control border-0 bg-light" placeholder="Tulis pesan..."
                                name="message" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @elseif($consultation->status == 'closed')
                <div class="card-footer bg-light text-center py-3">
                    <p class="mb-0 text-muted">Konsultasi telah ditutup.</p>
                    @if ($consultation->conclusion)
                        <div class="alert alert-info mt-2 text-start">
                            <strong>Kesimpulan:</strong> {{ $consultation->conclusion }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            const chatContainer = document.getElementById('chatContainer');
            chatContainer.scrollTop = chatContainer.scrollHeight;

            document.getElementById('chatForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const input = this.querySelector('input[name="message"]');
                const message = input.value;

                fetch('{{ route('client.consultations.send-message', $consultation->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: message
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            input.value = '';
                            location.reload(); // Simple reload for now, ideally append DOM
                        }
                    });
            });
        </script>
    @endpush
@endsection
