@extends('layouts.fullscreen-layout', ['title' => 'Contact Us'])

@section('content')
<div class="relative min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300 overflow-hidden flex flex-col">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2 w-[600px] h-[600px] bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <x-public.navbar />

    <main class="flex-grow pt-32 pb-20 px-4 relative z-10">
        <div class="max-w-xl mx-auto space-y-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Contact Our Team
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Have a question or need legal assistance? We're here to help.
                </p>
            </div>

            @if (session('success'))
                <div class="rounded-2xl bg-green-50 p-4 dark:bg-green-900/30 border border-green-200 dark:border-green-800 shadow-sm animate-pulse-soft">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-bold text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="bg-white px-8 py-10 shadow-2xl rounded-[2.5rem] dark:bg-white/[0.03] border border-gray-100 dark:border-gray-800 backdrop-blur-sm">
                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Name</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                class="w-full rounded-2xl border-gray-200 bg-gray-50/50 px-5 py-4 text-sm text-gray-900 transition focus:ring-brand-500 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            @error('name')<p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="w-full rounded-2xl border-gray-200 bg-gray-50/50 px-5 py-4 text-sm text-gray-900 transition focus:ring-brand-500 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            @error('email')<p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="category" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Category</label>
                        <select name="category" id="category" required
                            class="w-full rounded-2xl border-gray-200 bg-gray-50/50 px-5 py-4 text-sm text-gray-900 transition focus:ring-brand-500 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white appearance-none">
                            <option value="Technical Support">Technical Support</option>
                            <option value="Legal Inquiry">Legal Inquiry</option>
                            <option value="Partnership">Partnership</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Subject</label>
                        <input type="text" name="subject" id="subject" required value="{{ old('subject') }}"
                            class="w-full rounded-2xl border-gray-200 bg-gray-50/50 px-5 py-4 text-sm text-gray-900 transition focus:ring-brand-500 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2">Message</label>
                        <textarea name="message" id="message" rows="4" required
                            class="w-full rounded-2xl border-gray-200 bg-gray-50/50 px-5 py-4 text-sm text-gray-900 transition focus:ring-brand-500 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white resize-none">{{ old('message') }}</textarea>
                    </div>

                    <!-- Turnstile -->
                    <x-turnstile class="mb-5" />
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-2xl bg-brand-500 px-4 py-5 text-sm font-bold text-white shadow-xl shadow-brand-500/30 hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 transition-all active:scale-[0.98]">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <x-public.footer />
</div>
@endsection
