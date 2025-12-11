@extends('client.layout')

@section('title', 'Konsultasi')

@push('styles')
<style>
    :root {
        --chat-primary: #0d6efd;
        --chat-bg: #f4f6f9;
        --chat-sidebar-bg: #ffffff;
        --chat-border: #e9ecef;
        --chat-text: #333333;
        --chat-text-muted: #888888;
        --chat-bubble-sent: #0d6efd;
        --chat-bubble-received: #ffffff;
        --chat-hover: #f8f9fa;
        --chat-active: #e7f1ff;
    }

    .chat-container {
        height: calc(100vh - 140px);
        display: flex;
        background-color: #fff;
        border: 1px solid var(--chat-border);
        border-radius: 12px;
        overflow: hidden;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .chat-sidebar {
        width: 300px;
        background-color: var(--chat-sidebar-bg);
        border-right: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
    }

    .sidebar-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--chat-border);
        background-color: #fff;
    }

    .conversation-item {
        padding: 1rem 1.25rem;
        cursor: pointer;
        background-color: var(--chat-active);
        border-left: 4px solid var(--chat-primary);
    }

    .conversation-name {
        font-weight: 600;
        color: var(--chat-primary);
        font-size: 0.95rem;
    }

    .conversation-preview {
        font-size: 0.85rem;
        color: var(--chat-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background-color: #fff;
    }

    .chat-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--chat-border);
        height: 70px;
        display: flex;
        align-items: center;
    }

    .chat-messages {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
        background-color: var(--chat-bg);
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .message-wrapper {
        display: flex;
        gap: 1rem;
        max-width: 80%;
        align-items: flex-end;
    }

    .message-wrapper.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .message-bubble {
        padding: 0.8rem 1.2rem;
        border-radius: 15px;
        position: relative;
        font-size: 0.95rem;
        line-height: 1.5;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .message-wrapper.received .message-bubble {
        background-color: var(--chat-bubble-received);
        border-bottom-left-radius: 2px;
        color: var(--chat-text);
    }

    .message-wrapper.sent .message-bubble {
        background-color: var(--chat-bubble-sent);
        color: #fff;
        border-bottom-right-radius: 2px;
    }

    .message-time {
        font-size: 0.7rem;
        margin-top: 5px;
        opacity: 0.7;
        text-align: right;
    }

    .chat-input-area {
        padding: 1rem;
        background-color: #fff;
        border-top: 1px solid var(--chat-border);
    }

    .chat-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 25px;
        padding: 5px 15px;
    }

    .chat-input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px;
        outline: none;
        resize: none;
        max-height: 100px;
    }

    .btn-send {
        background-color: var(--chat-primary);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-send:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="chat-container">
        <div class="chat-sidebar d-none d-md-flex">
            <div class="sidebar-header">
                <h6 class="mb-0 fw-bold">Detail Konsultasi</h6>
            </div>
            <div class="conversation-list">
                <div class="conversation-item">
                    <div class="fw-bold text-primary text-truncate">{{ $consultation->question_subject }}</div>
                    <small class="text-muted">{{ Str::limit($consultation->question_text, 40) }}</small>
                </div>
            </div>
        </div>

        <div class="chat-main">
            <div class="chat-header">
                <h6 class="mb-0 fw-bold">{{ $consultation->question_subject }}</h6>
            </div>

            <div class="chat-messages" id="chatMessages">
                @foreach($messages as $message)
                @php
                $isSelf = $message->user_id === Auth::id();
                $user = $message->user;
                @endphp
                <div class="message-wrapper {{ $isSelf ? 'sent' : 'received' }}">
                    @if(!$isSelf)
                    <div class="avatar-circle d-flex align-items-center justify-content-center fw-bold text-white rounded-circle"
                        style="width: 35px; height: 35px; background-color: #6c757d; font-size: 0.8rem;">
                        {{ strtoupper(substr($user->name ?? $user->email ?? 'U', 0, 1)) }}
                    </div>
                    @endif

                    <div class="message-bubble">
                        @if($message->message_type === 'file')
                        <a href="{{ asset($message->attachment_url) }}" target="_blank" class="{{ $isSelf ? 'text-white' : 'text-primary' }} text-decoration-none">
                            <i class="fas fa-paperclip me-1"></i> Lampiran
                        </a><br>
                        @endif
                        {{ $message->message }}
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <form id="chatForm" action="{{ route('client.consultations.send-message', $consultation->id) }}" method="POST" class="chat-input-area">
                @csrf
                <div class="chat-input-wrapper">
                    <textarea id="messageInput" name="message" class="chat-input" rows="1" placeholder="Tulis pesan..." required></textarea>
                    <button type="submit" class="btn-send"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.3.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.17.0/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        const consultationId = {{ $consultation->id }};
        const currentUserId = {{ Auth::id() }};

        chatMessages.scrollTop = chatMessages.scrollHeight;

        window.Echo = new Echo({
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

        window.Echo.private(`consultation.${consultationId}`)
            .listen('.message.sent', (e) => {
                if (e.user.id !== currentUserId) {
                    appendMessage(e.message, e.user, 'received');
                }
            });

        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const message = messageInput.value.trim();
                if (!message) return;

                const formData = new FormData(this);

                axios.post(this.action, formData)
                    .then(response => {
                        if (response.data.success) {
                            appendMessage(response.data.message, {{ Js::from(Auth::user()) }}, 'sent');
                            messageInput.value = '';
                        } else {
                            alert('Gagal: ' + response.data.error);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal mengirim pesan.');
                    });
            });

            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });
        }

        function appendMessage(msgData, userData, type) {
            const time = new Date(msgData.created_at || new Date()).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            let attachmentHtml = '';

            if (msgData.message_type === 'file' && msgData.attachment_url) {
                const color = type === 'sent' ? 'text-white' : 'text-primary';
                attachmentHtml = `<a href="/${msgData.attachment_url}" target="_blank" class="${color} text-decoration-none"><i class="fas fa-paperclip"></i> Lampiran</a><br>`;
            }

            let avatarHtml = '';
            if (type === 'received') {
                const initial = (userData.name || userData.full_name || 'U').charAt(0).toUpperCase();
                avatarHtml = `
                <div class="avatar-circle d-flex align-items-center justify-content-center fw-bold text-white rounded-circle"
                     style="width: 35px; height: 35px; background-color: #6c757d; font-size: 0.8rem;">
                    ${initial}
                </div>`;
            }

            const html = `
            <div class="message-wrapper ${type}">
                ${avatarHtml}
                <div class="message-bubble">
                    ${attachmentHtml}
                    ${msgData.message}
                    <div class="message-time">${time}</div>
                </div>
            </div>`;

            chatMessages.insertAdjacentHTML('beforeend', html);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    });
</script>
@endpush