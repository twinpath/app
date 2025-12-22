@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Message Details
        </h2>

        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                        href="{{ route('admin.contacts.index') }}">
                        Inbox /
                    </a>
                </li>
                <li class="font-medium text-brand-500">View Message</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm dark:bg-white/[0.03] dark:border-gray-800 overflow-hidden">
        <!-- Message Header -->
        <div class="border-b border-gray-100 dark:border-gray-800 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-brand-500/10 flex items-center justify-center text-brand-600 font-bold text-xl">
                        {{ substr($contactSubmission->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                            {{ $contactSubmission->name }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $contactSubmission->email }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase {{ $contactSubmission->category == 'Legal Inquiry' ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/20' : 'bg-brand-50 text-brand-700 dark:bg-brand-500/20' }}">
                        {{ $contactSubmission->category }}
                    </span>
                    <p class="mt-2 text-xs text-gray-400 font-medium">
                        Received on {{ $contactSubmission->created_at->format('M d, Y \a\t H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Message Body -->
        <div class="p-6 sm:p-8 bg-gray-50/30 dark:bg-transparent">
            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Subject</h4>
            <p class="text-lg font-semibold text-gray-900 dark:text-white mb-8">
                {{ $contactSubmission->subject }}
            </p>

            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Message</h4>
            <div class="prose prose-gray dark:prose-invert max-w-none bg-white dark:bg-gray-800/20 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 mb-10">
                {!! nl2br(e($contactSubmission->message)) !!}
            </div>

            <!-- Quick Reply Form -->
            <div class="mt-12 pt-10 border-t border-gray-100 dark:border-gray-800">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-500"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    Quick Reply via Portal
                </h4>

                <form action="{{ route('admin.contacts.reply', $contactSubmission->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Subject</label>
                        <input type="text" name="subject" value="Re: {{ $contactSubmission->subject }}" required
                            class="w-full rounded-xl border-gray-200 bg-white px-4 py-3 text-gray-900 transition focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Message Body</label>
                        <textarea name="message" rows="6" required placeholder="Type your response here..."
                            class="w-full rounded-xl border-gray-200 bg-white px-4 py-3 text-gray-900 transition focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white"></textarea>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <p class="text-[10px] text-gray-400 italic">
                            * Sending from: <span class="font-bold text-brand-500">support@lab.dyzulk.com</span>
                        </p>
                        <button type="submit" class="px-8 py-3 bg-brand-500 text-white rounded-xl font-bold hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">
                            Send Reply Now
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-6 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50 dark:bg-transparent">
            <div class="flex items-center gap-4">
                <a href="mailto:{{ $contactSubmission->email }}?subject=Re: {{ $contactSubmission->subject }}" 
                    class="text-sm font-bold text-gray-500 hover:text-brand-500 transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    External Email App
                </a>
            </div>

            <form action="{{ route('admin.contacts.destroy', $contactSubmission->id) }}" method="POST" onsubmit="return confirm('Delete this message permanently?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 font-bold hover:underline">
                    Delete Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
