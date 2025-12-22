@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                Ticket Management
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
                    <li class="font-medium text-gray-800 dark:text-white/90">Tickets</li>
                </ol>
            </nav>
        </div>
    </div>

    <x-common.component-card :title="$title">
        <!-- Filters -->
        <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between">
             <div class="flex gap-2">
                 <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" class="px-3 py-1.5 text-sm font-medium rounded-lg {{ request('status') == 'all' || !request('status') ? 'bg-brand-50 text-brand-700 dark:bg-brand-900/20 dark:text-brand-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">All</a>
                 <a href="{{ request()->fullUrlWithQuery(['status' => 'open']) }}" class="px-3 py-1.5 text-sm font-medium rounded-lg {{ request('status') == 'open' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">Open</a>
                 <a href="{{ request()->fullUrlWithQuery(['status' => 'answered']) }}" class="px-3 py-1.5 text-sm font-medium rounded-lg {{ request('status') == 'answered' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">Answered</a>
                 <a href="{{ request()->fullUrlWithQuery(['status' => 'closed']) }}" class="px-3 py-1.5 text-sm font-medium rounded-lg {{ request('status') == 'closed' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">Closed</a>
             </div>
             
             <form method="GET" class="flex gap-2">
                 <input type="hidden" name="status" value="{{ request('status', 'all') }}">
                 <div class="relative">
                     <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Subject or User..." class="pl-9 pr-4 py-1.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-brand-500 focus:border-brand-500 w-64">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                     </div>
                 </div>
                 <button type="submit" class="px-3 py-1.5 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600">Filter</button>
             </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-white/5">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Ticket Info</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">User</th>
                         <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Updated</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                             <td class="px-6 py-4 whitespace-nowrap">
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
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                <div class="font-medium text-gray-800 dark:text-white/90">#{{ $ticket->ticket_number }}</div>
                                <div class="truncate max-w-xs text-gray-500">{{ $ticket->subject }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $ticket->category }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                <div class="font-medium text-gray-800 dark:text-white">{{ $ticket->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 text-xs rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <p>No tickets found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </x-common.component-card>
@endsection
