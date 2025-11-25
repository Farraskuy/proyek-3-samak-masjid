@props(['consultation', 'messages'])

<div class="flex-1 flex flex-col h-full bg-white relative">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10">
        <div class="flex items-center space-x-4">
            <div
                class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg">
                {{ substr($consultation->question_from ?? 'A', 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $consultation->question_from ?? 'Anonim' }}</h1>
                <div class="flex items-center text-sm text-gray-500 space-x-2">
                    <span>{{ $consultation->user->email ?? 'no-email@example.com' }}</span>
                    <span>&bull;</span>
                    <span>{{ $consultation->question_subject }}</span>
                </div>
            </div>
        </div>

        <!-- Tabs (Visual Only) -->
        <div class="flex space-x-6 text-sm font-medium text-gray-500">
            <button class="text-blue-600 border-b-2 border-blue-600 pb-4 -mb-4">Conversation</button>
            <button class="hover:text-gray-700 pb-4 -mb-4">Profile</button>
            <button class="hover:text-gray-700 pb-4 -mb-4">History</button>
        </div>

        <!-- Actions (Ustadz Only) -->
        @if (Auth::user()->role === 'ustadz')
            <div class="flex space-x-2">
                @if ($consultation->status === 'pending')
                    <form action="{{ route('admin.consultations.accept', $consultation->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium shadow-sm">
                            Terima Konsultasi
                        </button>
                    </form>
                @elseif($consultation->status === 'active')
                    <button onclick="openModal('closeModal')"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium shadow-sm">
                        Selesaikan
                    </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-white" id="messages-container">
        <!-- Initial Question -->
        <div class="flex justify-start">
            <div class="flex items-end space-x-2 max-w-[80%]">
                <div
                    class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-xs font-bold text-gray-500">
                    {{ substr($consultation->question_from ?? 'A', 0, 1) }}
                </div>
                <div>
                    <div class="bg-gray-100 text-gray-800 px-5 py-3 rounded-2xl rounded-bl-none shadow-sm">
                        <p class="text-sm leading-relaxed">{{ $consultation->question_text }}</p>
                    </div>
                    <span
                        class="text-xs text-gray-400 mt-1 block ml-1">{{ $consultation->created_at->format('H:i') }}</span>
                </div>
            </div>
        </div>

        @foreach ($messages as $message)
            @php
                $isMe = $message->user_id === Auth::id();
            @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="flex items-end space-x-2 max-w-[70%] {{ $isMe ? 'flex-row-reverse space-x-reverse' : '' }}">
                    @if (!$isMe)
                        <div
                            class="w-8 h-8 rounded-full bg-gray-200 flex-shrink-0 flex items-center justify-center text-xs font-bold text-gray-500">
                            {{ substr($message->user->name ?? 'U', 0, 1) }}
                        </div>
                    @endif

                    <div>
                        <div
                            class="px-5 py-3 rounded-2xl shadow-sm text-sm leading-relaxed 
                            {{ $isMe ? 'bg-blue-50 text-blue-900 rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                            @if ($message->message_type === 'file')
                                <a href="{{ asset($message->attachment_url) }}" target="_blank"
                                    class="flex items-center space-x-2 underline hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                        </path>
                                    </svg>
                                    <span>Lampiran</span>
                                </a>
                                <p class="mt-1">{{ $message->message }}</p>
                            @else
                                <p>{{ $message->message }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 mt-1 block {{ $isMe ? 'text-right mr-1' : 'ml-1' }}">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach

        @if ($consultation->status === 'closed')
            <div class="flex justify-center my-4">
                <span class="px-4 py-1 bg-gray-100 text-gray-500 text-xs rounded-full font-medium">
                    Konsultasi telah selesai
                </span>
            </div>
        @endif
    </div>

    <!-- Input Area -->
    @if (
        $consultation->status === 'active' ||
            ($consultation->status === 'pending' && Auth::user()->id === $consultation->user_id))
        <div class="p-4 bg-white border-t border-gray-100">
            <form id="message-form" class="relative">
                @csrf
                <div
                    class="border border-gray-200 rounded-xl bg-white shadow-sm focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500 transition-all">
                    <!-- Toolbar -->
                    <div class="flex items-center space-x-2 px-3 py-2 border-b border-gray-100">
                        <button type="button" class="p-1.5 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </button>
                        <button type="button" class="p-1.5 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                        </button>
                        <div class="h-4 w-px bg-gray-200 mx-2"></div>
                        <button type="button"
                            class="p-1.5 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-50 font-bold">B</button>
                        <button type="button"
                            class="p-1.5 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-50 italic">I</button>
                    </div>

                    <div class="flex items-end p-2">
                        <textarea name="message" id="message-input" rows="1"
                            class="w-full bg-transparent border-0 focus:ring-0 p-2 text-gray-800 placeholder-gray-400 resize-none max-h-32"
                            placeholder="Tulis pesan Anda..."></textarea>
                        <button type="submit" id="send-btn"
                            class="ml-2 mb-1 p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
@elseif($consultation->status === 'closed')
    <div class="p-6 bg-gray-50 border-t border-gray-200 text-center">
        <p class="text-gray-500">Sesi konsultasi ini telah berakhir.</p>
        @if ($consultation->conclusion)
            <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200 text-left max-w-2xl mx-auto">
                <h4 class="font-bold text-gray-900 mb-2">Kesimpulan:</h4>
                <p class="text-gray-700">{{ $consultation->conclusion }}</p>
            </div>
        @endif
    </div>
    @endif
</div>

<!-- Close Modal (Ustadz) -->
@if (Auth::user()->role === 'ustadz')
    <div id="closeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal('closeModal')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.consultations.close', $consultation->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Selesaikan Konsultasi
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Berikan kesimpulan atau catatan akhir untuk konsultasi
                                ini.</p>
                            <textarea name="conclusion" rows="4"
                                class="mt-3 w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500"
                                required></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Selesaikan
                        </button>
                        <button type="button" onclick="closeModal('closeModal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
    // Auto-resize textarea
    const textarea = document.getElementById('message-input');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Scroll to bottom
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) container.scrollTop = container.scrollHeight;
    }
    scrollToBottom();

    // Re-bindable script for SPA
    window.initChatScripts = function() {
        scrollToBottom();

        // Re-attach form listener if needed (though simpler to inline or delegate)
        const form = document.getElementById('message-form');
        if (form) {
            form.onsubmit = function(e) {
                e.preventDefault();
                const input = document.getElementById('message-input');
                const message = input.value.trim();
                if (!message) return;

                // Optimistic UI
                const container = document.getElementById('messages-container');
                const tempId = Date.now();
                const myBubble = `
                    <div class="flex justify-end" id="msg-${tempId}">
                        <div class="flex items-end space-x-2 max-w-[70%] flex-row-reverse space-x-reverse">
                            <div>
                                <div class="px-5 py-3 rounded-2xl shadow-sm text-sm leading-relaxed bg-blue-50 text-blue-900 rounded-br-none">
                                    <p>${message}</p>
                                </div>
                                <span class="text-xs text-gray-400 mt-1 block text-right mr-1">Now</span>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', myBubble);
                scrollToBottom();
                input.value = '';
                input.style.height = 'auto';

                // Send AJAX
                const route =
                    "{{ Auth::user()->role === 'ustadz' ? route('admin.consultations.send-message', $consultation->id) : route('client.consultations.send-message', $consultation->id) }}";

                axios.post(route, {
                        message: message
                    })
                    .then(res => {
                        // Success (Real-time will handle the rest, or update status)
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal mengirim pesan');
                        document.getElementById(`msg-${tempId}`).remove();
                        input.value = message;
                    });
            };
        }
    };

    // Run initially
    window.initChatScripts();

    // Modal helpers
    window.openModal = function(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    window.closeModal = function(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
