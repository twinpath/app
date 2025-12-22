@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <!-- Breadcrumb -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-semibold text-black dark:text-white">
                Legal Pages Management
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                            href="{{ route('dashboard') }}">
                            Dashboard /
                        </a>
                    </li>
                    <li class="font-medium text-brand-500">Legal Pages</li>
                </ol>
            </nav>
        </div>

        <!-- Alert (Moved to Toast) -->

        <!-- Table Section -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Page Title
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Slug
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Current Version
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Last Updated
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Actions
                                </p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($pages as $page)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        {{ $page->title }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        /legal/{{ $page->slug }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500">
                                        v{{ $page->currentRevision->version ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $page->currentRevision ? $page->currentRevision->updated_at->format('M d, Y') : 'N/A' }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.legal-pages.edit', $page->id) }}" class="text-brand-500 hover:text-brand-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        </a>
                                        <a href="{{ route('legal.show', $page->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
