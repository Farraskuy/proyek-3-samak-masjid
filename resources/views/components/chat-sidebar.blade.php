@props(['conversations', 'activeId' => null])

<div class="w-80 bg-white border-r border-gray-200 flex flex-col h-full">
    <!-- Header -->
    <div class="p-4 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-800">Konsultasi</h2>
        <div class="mt-4 relative">
            <input type="text" placeholder="Cari konsultasi..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
            <div class="absolute left-3 top-2.5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
            </div>
        </div>

        <!-- Conversation List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            {{-- Categories (Optional, visual only for now) --}}
            <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Terbaru
            </div>

            <ul class="space-y-1 px-2">
                @foreach ($conversations as $conversation)
                    @php
                        $isActive = request()->route('id') == $conversation->id;
                        $lastMessage = $conversation->messages->last();
                        $preview = $lastMessage
                            ? Str::limit($lastMessage->message, 30)
                            : Str::limit($conversation->question_text, 30);
                        $time = $lastMessage
                            ? $lastMessage->created_at->format('H:i')
                            : $conversation->created_at->format('H:i');

                        // Determine route based on user role
                        $route =
                            Auth::user()->role === 'ustadz' || Auth::user()->role === 'admin'
                                ? route('admin.consultations.show', $conversation->id)
                                : route('client.consultations.show', $conversation->id);
                    @endphp

                    <li>
                        <a href="{{ $route }}"
                            class="conversation-link block p-3 rounded-lg transition-colors duration-200 {{ $isActive ? 'bg-blue-50' : 'hover:bg-gray-50' }}"
                            data-id="{{ $conversation->id }}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 relative">
                                    <div
                                        class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                        {{ substr($conversation->question_from ?? 'A', 0, 1) }}
                                    </div>
                                    @if ($conversation->status === 'active')
                                        <span
                                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline mb-1">
                                        <h3
                                            class="text-sm font-semibold truncate {{ $isActive ? 'text-blue-900' : 'text-gray-900' }}">
                                            {{ $conversation->question_from ?? 'Anonim' }}
                                        </h3>
                                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $time }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $conversation->question_subject }}
                                    </p>
                                    <p class="text-xs text-gray-400 truncate mt-0.5">
                                        {{ $preview }}
                                    </p>
                                </div>
                                @if ($conversation->unread_count > 0)
                                    class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                    {{ $conversation->unread_count }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
