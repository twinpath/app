@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Manage Ticket #{{ $ticket->ticket_number }}
            </h2>
            <nav class="mt-1">
                <ol class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                            Admin
                        </a>
                    </li>
                    <li>
                        <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </li>
                    <li>
                        <a href="{{ route('admin.tickets.index') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                            Tickets
                        </a>
                    </li>
                    <li>
                        <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </li>
                    <li class="font-medium text-gray-800 dark:text-white/90">Manage</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chat Area -->
        <div class="lg:col-span-2 space-y-6">
            <x-common.component-card>
                <x-slot:header>
                    <div class="mb-6 pb-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-start">
                        <div>
                            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ $ticket->subject }}</h1>
                            <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                {{ $ticket->category }}
                            </span>
                        </div>
                    </div>
                </x-slot:header>

                <div id="admin-ticket-chat-container" class="space-y-8">
                    @foreach($ticket->replies as $reply)
                        @php
                            // In Admin view: User messages on LEFT, Admin messages (ours) on RIGHT
                            // But since multiple admins might exist, we check if reply user is Admin Role or specific user
                            // Simpler: If reply->user_id == Auth::id() -> Right (It's ME)
                            // If reply->user->isAdmin() -> Right (It's a Colleague)
                            // Else (Customer) -> Left
                            $isStaff = $reply->user->isAdmin();
                        @endphp
                        <div class="flex {{ $isStaff ? 'justify-end' : 'justify-start' }}">
                            @if(!$isStaff)
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-600 dark:text-blue-300">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                </div>
                            @endif
                            <div class="max-w-xl">
                                <div class="text-xs text-gray-500 mb-1 {{ $isStaff ? 'text-right' : 'text-left' }}">
                                    {{ $reply->user->name }} {{ $isStaff ? '(Staff)' : '' }} • {{ $reply->created_at->format('M d, Y H:i A') }}
                                </div>
                                <div class="px-4 py-3 rounded-lg {{ $isStaff ? 'bg-brand-500 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-tl-none' }}">
                                    <p class="whitespace-pre-wrap text-sm">{{ $reply->message }}</p>
                                    @if($reply->attachment_path)
                                        <div class="mt-3 pt-3 border-t {{ $isStaff ? 'border-brand-400' : 'border-gray-200 dark:border-gray-600' }}">
                                            <a href="{{ Storage::url($reply->attachment_path) }}" target="_blank" class="flex items-center text-xs {{ $isStaff ? 'text-brand-100 hover:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                Attachment
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($isStaff)
                                <div class="flex-shrink-0 ml-3">
                                     <div class="w-8 h-8 rounded-full bg-brand-200 flex items-center justify-center text-xs font-bold text-brand-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Post Staff Reply</h3>
                    <form id="admin-ticket-reply-form" action="{{ route('admin.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <textarea id="reply-message" name="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="Type your reply here..." required></textarea>
                        </div>
                        <div class="mb-4" x-data="{ fileName: '' }">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="admin_attachment">Attachment (Optional)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="admin_attachment" class="flex flex-col items-center justify-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-colors">
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
                                    <input id="admin_attachment" name="attachment" type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" id="submit-admin-reply" class="flex items-center text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-brand-500 dark:hover:bg-brand-600 dark:focus:ring-brand-800 transition-all disabled:opacity-50">
                                <span id="submit-text">Send Staff Reply</span>
                                <svg id="submit-spinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </x-common.component-card>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Ticket Status Control -->
            <x-common.component-card title="Ticket Status">

                 <form action="{{ route('admin.tickets.update-status', $ticket->id) }}" method="POST">
                     @csrf
                     @method('PATCH')
                     <div class="space-y-4">
                         <div>
                             <label class="block mb-1.5 text-xs font-medium text-gray-700 dark:text-gray-400">Status</label>
                             <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                 <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                 <option value="answered" {{ $ticket->status == 'answered' ? 'selected' : '' }}>Answered</option>
                                 <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                             </select>
                         </div>
                         <div>
                             <label class="block mb-1.5 text-xs font-medium text-gray-700 dark:text-gray-400">Priority</label>
                             <select name="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                 <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                 <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                 <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                             </select>
                         </div>
                         <button type="submit" class="w-full text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-800">
                             Update Status
                         </button>
                     </div>
                 </form>
            </x-common.component-card>

            <!-- User Info -->
            <x-common.component-card title="Customer Profile">

                 <div class="flex items-center gap-3 mb-4">
                     <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-lg font-bold text-brand-600">
                         {{ substr($ticket->user->name, 0, 1) }}
                     </div>
                     <div>
                         <div class="font-medium text-gray-900 dark:text-white">{{ $ticket->user->name }}</div>
                         <div class="text-xs text-gray-500">{{ $ticket->user->email }}</div>
                     </div>
                 </div>
                 <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                     <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Recent Tickets</h4>
                     @if($ticket->user->tickets->count() > 1)
                         <ul class="space-y-2">
                             @foreach($ticket->user->tickets as $userTicket)
                                 @if($userTicket->id !== $ticket->id)
                                    <li>
                                        <a href="{{ route('admin.tickets.show', $userTicket->id) }}" class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 hover:text-brand-500">
                                            <span>#{{ $userTicket->ticket_number }}</span>
                                            <span class="px-1.5 py-0.5 rounded-sm bg-gray-100 dark:bg-gray-800 text-gray-500">{{ $userTicket->status }}</span>
                                        </a>
                                    </li>
                                 @endif
                             @endforeach
                         </ul>
                     @else
                        <p class="text-xs text-gray-400 italic">No other tickets.</p>
                     @endif
                 </div>
            </x-common.component-card>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ticketId = "{{ $ticket->id }}";
        const currentUserId = "{{ Auth::id() }}";
        const chatContainer = document.getElementById('admin-ticket-chat-container');
        const replyForm = document.getElementById('admin-ticket-reply-form');
        const submitBtn = document.getElementById('submit-admin-reply');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        const messageInput = document.getElementById('reply-message');

        const appendMessage = (data) => {
            // Check if message already exists to avoid duplicates
            if (document.getElementById(`reply-${data.id}`)) return;

            const isStaff = data.is_staff;
            
            const messageHtml = `
                <div id="reply-${data.id}" class="flex ${isStaff ? 'justify-end' : 'justify-start'} animate-fade-in-up mb-8 last:mb-0">
                    ${!isStaff ? `
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-xs font-bold text-blue-600 dark:text-blue-300">
                                ${data.user_name.substring(0, 1)}
                            </div>
                        </div>
                    ` : ''}
                    <div class="max-w-xl">
                        <div class="text-xs text-gray-500 mb-1 ${isStaff ? 'text-right' : 'text-left'}">
                            ${data.user_name} ${isStaff ? '(Staff)' : ''} • ${data.created_at}
                        </div>
                        <div class="px-4 py-3 rounded-lg ${isStaff ? 'bg-brand-500 text-white rounded-tr-none' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-tl-none'}">
                            <p class="whitespace-pre-wrap text-sm">${data.message}</p>
                            ${data.attachment_url ? `
                                <div class="mt-3 pt-3 border-t ${isStaff ? 'border-brand-400' : 'border-gray-200 dark:border-gray-600'}">
                                    <a href="${data.attachment_url}" target="_blank" class="flex items-center text-xs ${isStaff ? 'text-brand-100 hover:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        Attachment
                                    </a>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                    ${isStaff ? `
                        <div class="flex-shrink-0 ml-3">
                             <div class="w-8 h-8 rounded-full bg-brand-200 flex items-center justify-center text-xs font-bold text-brand-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
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
                        }
                    })
                    .catch(error => {
                        console.error('Error sending staff reply:', error);
                        alert('Failed to send reply. Please try again.');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.innerText = 'Send Staff Reply';
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
