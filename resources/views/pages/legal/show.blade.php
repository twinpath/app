@extends('layouts.fullscreen-layout', ['title' => $page->title])

@section('content')
<div class="relative min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300 overflow-hidden flex flex-col">
    <!-- Background Decoration -->
    <div class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -translate-x-1/2 translate-y-1/2 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <x-public.navbar />

    <main class="flex-grow pt-32 pb-20 px-4 relative z-10">
        <div class="max-w-4xl mx-auto px-6">
            <!-- Header -->
            <header class="mb-12 border-b border-gray-100 pb-8 dark:border-gray-800 text-center sm:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-6">
                    📜 Legal Document
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-6">
                    {{ $page->title }}
                </h1>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-2 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <svg class="text-brand-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Last updated: {{ $revision->created_at->format('M d, Y') }}
                    </span>
                    <span class="flex items-center gap-2 bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                        <svg class="text-brand-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                        Version {{ $revision->version }}
                    </span>
                </div>
            </header>

            <!-- Content -->
            <article class="prose prose-lg prose-gray dark:prose-invert max-w-none 
                prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white
                prose-p:text-gray-600 dark:prose-p:text-gray-400 prose-p:leading-relaxed
                prose-strong:text-brand-600 dark:prose-strong:text-brand-400
                prose-a:text-brand-500 hover:prose-a:text-brand-600 prose-a:font-semibold
                prose-li:text-gray-600 dark:prose-li:text-gray-400
                prose-pre:bg-gray-50 dark:prose-pre:bg-gray-800/50 prose-pre:border prose-pre:border-gray-100 dark:prose-pre:border-gray-700">
                {!! Str::markdown($revision->content) !!}
            </article>
        </div>
    </main>

    <x-public.footer />
</div>
@endsection
