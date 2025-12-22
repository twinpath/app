@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Ticket #{{ $ticket->ticket_number }}
            </h2>
            <nav class="mt-1">
                <ol class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('support.index') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                            <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Support Tickets
                        </a>
                    </li>
                    <li>
                        <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </li>
                    <li class="font-medium text-gray-800 dark:text-white/90">View Ticket</li>
                </ol>
            </nav>
        </div>
        <div>
            @if($ticket->status !== 'closed')
                <form action="{{ route('support.close', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to close this ticket?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-red-500 hover:bg-red-600 shadow-theme-xs">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Close Ticket
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chat Area -->
        <div class="lg:col-span-2 space-y-6">
            <x-common.component-card>
                <x-slot:header>
                     <div class="flex items-start justify-between">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $ticket->subject }}</h3>
                        <div class="flex flex-wrap gap-2">
                             <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                {{ $ticket->category }}
                            </span>
                            @php
                                $statusClass = match($ticket->status) {
                                    'open' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                    'answered' => 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400',
                                    'closed' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
                                    default => 'bg-gray-100 text-gray-600'
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </div>
                </x-slot:header>

                <div id="ticket-chat-container" class="space-y-8">
                    @foreach($ticket->replies as $reply)
                        @php
                            $isMe = $reply->user_id === Auth::id();
                        @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            @if(!$isMe)
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                </div>
                            @endif
                            <div class="max-w-xl">
                                <div class="text-xs text-gray-500 mb-1 {{ $isMe ? 'text-right' : 'text-left' }}">
                                    {{ $reply->user->name }} • {{ $reply->created_at->format('M d, Y H:i A') }}
                                </div>
                                <div class="px-4 py-3 rounded-lg {{ $isMe ? 'bg-brand-500 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-tl-none' }}">
                                    <p class="whitespace-pre-wrap text-sm">{{ $reply->message }}</p>
                                    @if($reply->attachment_path)
                                        <div class="mt-3 pt-3 border-t {{ $isMe ? 'border-brand-400' : 'border-gray-200 dark:border-gray-600' }}">
                                            <a href="{{ Storage::url($reply->attachment_path) }}" target="_blank" class="flex items-center text-xs {{ $isMe ? 'text-brand-100 hover:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($isMe)
                                <div class="flex-shrink-0 ml-3">
                                     <div class="w-8 h-8 rounded-full bg-brand-200 flex items-center justify-center text-xs font-bold text-brand-700">
                                        Me
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($ticket->status !== 'closed')
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Post a Reply</h3>
                        <form id="ticket-reply-form" action="{{ route('support.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <textarea id="reply-message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="Type your reply here..." required></textarea>
                            </div>
                            <div class="mb-4" x-data="{ fileName: '' }">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="attachment">Attachment (Optional)</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="attachment" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex flex-col items-center justify-center pt-3 pb-4 p-2 text-center" x-show="!fileName">
                                            <svg class="w-6 h-6 mb-2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-1 text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF or DOCX (MAX. 2MB)</p>
                                        </div>
                                         <div class="flex flex-col items-center justify-center pt-3 pb-4 p-2 text-center" x-show="fileName" style="display: none;">
                                            <svg class="w-6 h-6 mb-2 text-brand-500 dark:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="mb-1 text-xs text-gray-700 dark:text-gray-300 truncate max-w-xs" x-text="fileName"></p>
                                        </div>
                                        <input id="attachment" name="attachment" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                                    </label>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" id="submit-reply" class="flex items-center text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-brand-500 dark:hover:bg-brand-600 dark:focus:ring-brand-800 transition-all disabled:opacity-50">
                                    <span id="submit-text">Send Reply</span>
                                    <svg id="submit-spinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 text-center">
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                            <span class="font-medium">This ticket is closed.</span> You cannot reply to this ticket anymore. Please open a new ticket if you need further assistance.
                        </div>
                    </div>
                @endif
            </x-common.component-card>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <x-common.component-card title="Ticket Info">

                 <dl class="space-y-3 text-sm">
                     <div class="flex justify-between">
                         <dt class="text-gray-500 dark:text-gray-400">Created</dt>
                         <dd class="text-gray-900 dark:text-white font-medium">{{ $ticket->created_at->format('M d, Y') }}</dd>
                     </div>
                     <div class="flex justify-between">
                         <dt class="text-gray-500 dark:text-gray-400">Last Updated</dt>
                         <dd class="text-gray-900 dark:text-white font-medium">{{ $ticket->updated_at->diffForHumans() }}</dd>
                     </div>
                     <div class="flex justify-between">
                         <dt class="text-gray-500 dark:text-gray-400">Priority</dt>
                         <dd>
                             <span class="px-2 py-0.5 text-xs rounded bg-gray-100 dark:bg-gray-700">{{ ucfirst($ticket->priority) }}</span>
                         </dd>
                     </div>
                 </dl>
            </x-common.component-card>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ticketId = "{{ $ticket->id }}";
        const currentUserId = "{{ Auth::id() }}";
        const chatContainer = document.getElementById('ticket-chat-container');
        const replyForm = document.getElementById('ticket-reply-form');
        const submitBtn = document.getElementById('submit-reply');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        const messageInput = document.getElementById('reply-message');

        const appendMessage = (data) => {
            // Check if message already exists to avoid duplicates
            if (document.getElementById(`reply-${data.id}`)) return;

            const isMe = data.user_id === currentUserId;
            
            const messageHtml = `
                <div id="reply-${data.id}" class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-fade-in-up mb-8 last:mb-0">
                    ${!isMe ? `
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300">
                                ${data.user_name.substring(0, 1)}
                            </div>
                        </div>
                    ` : ''}
                    <div class="max-w-xl">
                        <div class="text-xs text-gray-500 mb-1 ${isMe ? 'text-right' : 'text-left'}">
                            ${data.user_name} • ${data.created_at}
                        </div>
                        <div class="px-4 py-3 rounded-lg ${isMe ? 'bg-brand-500 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-tl-none'}">
                            <p class="whitespace-pre-wrap text-sm">${data.message}</p>
                            ${data.attachment_url ? `
                                <div class="mt-3 pt-3 border-t ${isMe ? 'border-brand-400' : 'border-gray-200 dark:border-gray-600'}">
                                    <a href="${data.attachment_url}" target="_blank" class="flex items-center text-xs ${isMe ? 'text-brand-100 hover:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        Attachment
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    ${isMe ? `
                        <div class="flex-shrink-0 ml-3">
                             <div class="w-8 h-8 rounded-full bg-brand-200 flex items-center justify-center text-xs font-bold text-brand-700">
                                Me
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;

            chatContainer.insertAdjacentHTML('beforeend', messageHtml);
            
            // Scroll to bottom
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        };

        // Listen for realtime messages
        if (window.Echo) {
            window.Echo.private(`ticket.${ticketId}`)
                .listen('.ticket.message.sent', (e) => {
                    console.log('Realtime message received:', e);
                    appendMessage(e);
                });
        }

        // Handle AJAX form submission
        if (replyForm) {
            replyForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(replyForm);
                
                // UI States
                submitBtn.disabled = true;
                submitText.innerText = 'Sending...';
                submitSpinner.classList.remove('hidden');

                window.axios.post(replyForm.action, formData)
                    .then(response => {
                        if (response.data.success) {
                            // Append message locally immediately
                            appendMessage(response.data.reply);
                            
                            // Reset form
                            replyForm.reset();
                            // If Alpine.js is used for fileName, it might need manual reset or Alpine would handle it if we used x-model
                            // But fileName is in a separate x-data on the label's parent.
                        }
                    })
                    .catch(error => {
                        console.error('Error sending reply:', error);
                        alert('Failed to send reply. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.innerText = 'Send Reply';
                        submitSpinner.classList.add('hidden');
                    });
            });
        }
    });
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
</style>
@endpush
