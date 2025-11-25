@extends('client.layout')

@section('title', 'Chat Konsultasi')

@push('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endpush

@section('content')
    <div class="h-[calc(100vh-64px)] bg-gray-100 flex overflow-hidden">
        <!-- Sidebar -->
        <x-chat-sidebar :conversations="$conversations" :activeId="$consultation->id" />

        <!-- Main Chat Area -->
        <x-chat-area :consultation="$consultation" :messages="$messages" />
    </div>
@endsection

@push('scripts')
    <script type="module">
        const consultationId = {{ $consultation->id }};
        const userId = {{ Auth::id() }};
        const messagesContainer = document.getElementById('messages-container');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');

        // Scroll to bottom
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        scrollToBottom();

        // Listen for new messages
        Echo.private('consultation.' + consultationId)
            .listen('.message.sent', (e) => {
                console.log('New message:', e);
                appendMessage(e.message, e.user);
                scrollToBottom();
            });

        // Append message to DOM
        function appendMessage(message, user) {
            const isMe = user.id === userId;
            const alignClass = isMe ? 'flex-row-reverse' : '';
            const bgClass = isMe ? 'bg-blue-50 border-blue-100 text-blue-900' : 'bg-white border-gray-100 text-gray-800';
            const userInitial = user.name.charAt(0);
            const avatarColor = user.role === 'ustadz' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600';
            const avatarText = user.role === 'ustadz' ? 'U' : userInitial;

            const html = `
            <div class="flex gap-4 ${alignClass}">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full ${avatarColor} flex items-center justify-center font-bold text-sm">
                        ${avatarText}
                    </div>
                </div>
                <div class="flex-1 max-w-2xl">
                    <div class="flex items-baseline justify-between ${alignClass}">
                        <h4 class="text-sm font-bold text-gray-900">${user.name}</h4>
                        <span class="text-xs text-gray-500 mx-2">Baru saja</span>
                    </div>
                    <div class="mt-1 p-4 rounded-lg shadow-sm border ${bgClass}">
                        <p class="whitespace-pre-wrap">${message.message}</p>
                    </div>
                </div>
            </div>
        `;

            messagesContainer.insertAdjacentHTML('beforeend', html);
        }

        // Handle form submit
        if (chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = messageInput.value.trim();
                if (!message) return;

                // Optimistic UI update (optional, but good for UX)
                // appendMessage({ message: message }, { id: userId, name: '{{ Auth::user()->name }}', role: '{{ Auth::user()->role }}' });
                // scrollToBottom();

                messageInput.value = '';

                axios.post(`{{ route('client.consultations.send-message', $consultation->id) }}`, {
                        message: message
                    })
                    .then(response => {
                        console.log('Message sent:', response.data);
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        alert('Gagal mengirim pesan');
                    });
            });

            // Handle Enter key to submit
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });
        }
    </script>
@endpush
