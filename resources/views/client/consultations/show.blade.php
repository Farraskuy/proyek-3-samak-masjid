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
        height: calc(100vh - 70px);
        display: flex;
        background-color: #fff;
        border: 1px solid var(--chat-border);
        font-family: 'Poppins', sans-serif;
    }

    .chat-sidebar {
        width: 300px;
        background-color: var(--chat-sidebar-bg);
        border-right: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
        z-index: 10;
    }

    .sidebar-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--chat-border);
        background-color: #fff;
    }

    .conversation-item {
        padding: 1rem 1.25rem;
        background-color: var(--chat-active);
        border-left: 3px solid var(--chat-primary);
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
        display: flex;
        align-items: center;
        height: 75px;
    }

    .chat-messages {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
        background-color: var(--chat-bg);
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .message-wrapper {
        display: flex;
        gap: 1rem;
        max-width: 75%;
        align-items: flex-start;
        /* rata atas */
    }

    .message-wrapper.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-weight: bold;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .avatar-circle.sent {
        background-color: #3b82f6;
        color: white;
    }

    .avatar-circle.received {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .message-bubble {
        padding: 1rem 1.25rem;
        border-radius: 18px;
        position: relative;
        font-size: 0.95rem;
        line-height: 1.6;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    .message-wrapper.received .message-bubble {
        background-color: var(--chat-bubble-received);
        border-bottom-left-radius: 4px;
        color: var(--chat-text);
    }

    .message-wrapper.sent .message-bubble {
        background-color: var(--chat-bubble-sent);
        color: #fff;
        border-bottom-right-radius: 4px;
    }

    .message-time {
        font-size: 0.7rem;
        margin-top: 0.4rem;
        opacity: 0.8;
        text-align: right;
        font-weight: 500;
    }

    .chat-input-area {
        padding: 1.25rem 1.5rem;
        background-color: #fff;
        border-top: 1px solid var(--chat-border);
    }

    .chat-input-wrapper {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem;
    }

    .chat-input {
        flex: 1;
        border: none;
        background: transparent;
        resize: none;
        max-height: 120px;
        padding: 0;
        outline: none;
        font-size: 0.95rem;
        line-height: 1.5;
        color: var(--chat-text);
    }

    .btn-send {
        background-color: var(--chat-primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-send:hover {
        background-color: #0b5ed7;
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="chat-container">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <div class="sidebar-header">
                <h5 class="mb-0">Konsultasi</h5>
            </div>
            <div class="conversation-list">
                <div class="conversation-item active">
                    <div class="conversation-info">
                        <div class="conversation-header">
                            <span class="conversation-name">
                                {{ $consultation->question_subject }}
                            </span>
                        </div>
                        <span class="conversation-preview">
                            {{ Str::limit($consultation->question_text, 50) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-main">
            <div class="chat-header">
                <h5 class="mb-0">{{ $consultation->question_subject }}</h5>
            </div>

            <div class="chat-messages" id="chatMessages">
                @foreach($messages as $message)
                @php
                $isSelf = $message->user_id === Auth::id();
                $user = $message->user;
                $initial = strtoupper(substr($user->name ?? $user->email, 0, 1));
                @endphp
                <div class="message-wrapper {{ $isSelf ? 'sent' : 'received' }}">
                    <div class="avatar-circle {{ $isSelf ? 'sent' : 'received' }}">
                        {{ $initial }}
                    </div>
                    <div class="message-bubble">
                        @if($message->message_type === 'file')
                        <a href="{{ $message->attachment_url }}" target="_blank" class="{{ $isSelf ? 'text-white' : 'text-primary' }}">
                            <i class="fas fa-paperclip me-1"></i>Lampiran
                        </a><br>
                        @endif
                        {{ $message->message }}
                        <div class="message-time">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Input Form -->
            <form id="chatForm" action="{{ route('client.consultations.send-message', $consultation->id) }}" method="POST" class="chat-input-area">
                @csrf
                <div class="chat-input-wrapper">
                    <textarea id="messageInput" name="message" class="chat-input" rows="1" placeholder="Ketik pesan..." required></textarea>
                    <button type="submit" class="btn-send">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatMessages = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');

        // Scroll ke bawah saat halaman dimuat
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Handle submit
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            const formData = new FormData(this);
            const url = this.action;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                alert('CSRF token tidak ditemukan. Silakan reload halaman.');
                return;
            }

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Buat elemen pesan baru
                        const newMessage = document.createElement('div');
                        newMessage.className = 'message-wrapper sent';

                        newMessage.innerHTML = `
                    <div class="avatar-circle sent">U</div>
                    <div class="message-bubble">
                        ${data.message}
                        <div class="message-time">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                    </div>
                `;

                        chatMessages.appendChild(newMessage);
                        messageInput.value = '';
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    } else {
                        alert('Gagal mengirim pesan: ' + (data.error || 'Kesalahan tidak diketahui'));
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Terjadi kesalahan saat mengirim pesan.');
                });
        });

        // Enter untuk kirim
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    });
</script>
@endpush

@endsection