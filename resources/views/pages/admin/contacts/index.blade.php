@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            Inbox / Messages
        </h2>

        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                        href="{{ route('dashboard') }}">
                        Dashboard /
                    </a>
                </li>
                <li class="font-medium text-brand-500">Inbox</li>
            </ol>
        </nav>
    </div>

    <!-- Inbox Layout -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-5 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Sender</p>
                        </th>
                        <th class="px-5 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Category & Subject</p>
                        </th>
                        <th class="px-5 py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Date</p>
                        </th>
                        <th class="px-5 py-3 text-right">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Actions</p>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($submissions as $msg)
                        <tr class="group hover:bg-gray-50 dark:hover:bg-white/[0.02] {{ !$msg->is_read ? 'bg-brand-50/30 dark:bg-brand-500/5' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-500/10 flex items-center justify-center text-brand-600 font-bold text-sm">
                                            {{ substr($msg->name, 0, 1) }}
                                        </div>
                                        @if(!$msg->is_read)
                                            <span class="absolute top-0 right-0 h-3 w-3 rounded-full bg-brand-500 border-2 border-white dark:border-gray-900"></span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                            {{ $msg->name }}
                                        </span>
                                        <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                            {{ $msg->email }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase mb-1 {{ $msg->category == 'Legal Inquiry' ? 'bg-purple-100 text-purple-700 dark:bg-purple-500/20' : 'bg-gray-100 text-gray-600 dark:bg-gray-700' }}">
                                    {{ $msg->category }}
                                </span>
                                <p class="text-gray-800 dark:text-white/80 text-theme-sm font-medium line-clamp-1">
                                    {{ $msg->subject }}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-gray-500 text-theme-xs dark:text-gray-400 whitespace-nowrap">
                                    {{ $msg->created_at->format('M d, Y') }}
                                    <span class="block opacity-60">{{ $msg->created_at->format('H:i') }}</span>
                                </p>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.contacts.show', $msg->id) }}" class="p-2 text-gray-400 hover:text-brand-500 dark:hover:text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <form action="{{ route('admin.contacts.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <p class="text-gray-500 font-medium">No messages in your inbox yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($submissions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
