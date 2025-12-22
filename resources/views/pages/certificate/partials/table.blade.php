    <div class="max-w-full overflow-x-auto" 
         x-data="{ anyOpen: false }" 
         :class="anyOpen ? 'pb-32' : 'pb-4'">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Common Name (CN)</th>
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Serial Number</th>
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Alt Names (SAN)</th>
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center hidden xl:table-cell">Validity Period</th>
                    <th class="px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($certificates as $cert)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-5 py-4 text-sm text-gray-500">
                            {{ ($certificates->currentPage() - 1) * $certificates->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $cert->common_name }}</div>
                            <div class="text-xs text-gray-400">{{ $cert->organization }}</div>
                        </td>
                        <td class="px-5 py-4 text-xs font-mono text-gray-500 dark:text-gray-400 hidden sm:table-cell">
                            {{ $cert->serial_number ?: '-' }}
                        </td>
                        <td class="px-5 py-4 hidden lg:table-cell">
                            <div class="max-w-xs truncate text-xs text-gray-500 dark:text-gray-400 font-mono" title="{{ $cert->san }}">
                                {{ $cert->san ?: '-' }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @php
                                $isValid = $cert->valid_to && \Carbon\Carbon::parse($cert->valid_to)->isFuture();
                                $statusClass = $isValid ? 'bg-success-50 text-success-700 dark:bg-success-900/30 dark:text-success-300' : 'bg-error-50 text-error-700 dark:bg-error-900/30 dark:text-error-300';
                                $statusText = $isValid ? 'Valid' : 'Expired';
                                if(!$cert->valid_to) {
                                    $statusClass = 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400';
                                    $statusText = 'Unknown';
                                }
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400 text-center whitespace-nowrap hidden xl:table-cell">
                            @if($cert->valid_from && $cert->valid_to)
                                <div>{{ \Carbon\Carbon::parse($cert->valid_from)->format('Y-m-d') }}</div>
                                <div class="text-gray-400">to</div>
                                <div>{{ \Carbon\Carbon::parse($cert->valid_to)->format('Y-m-d') }}</div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- View Dropdown -->
                                <div x-data="{ open: false }" class="relative" @click.away="open = false; anyOpen = false">
                                    <button @click="open = !open; anyOpen = open" 
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 transition-all active:scale-90 shadow-sm"
                                            title="View Details">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span class="hidden xl:inline">View</span>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="absolute left-0 mt-2 w-32 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-black/5 z-[110] overflow-hidden border border-gray-100 dark:border-gray-700">
                                        <button @click.prevent="openViewModal('{{ route('certificate.view', [$cert->uuid, 'cert']) }}', 'Certificate Content'); open = false" class="w-full flex items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            Cert
                                        </button>
                                        <button @click.prevent="openViewModal('{{ route('certificate.view', [$cert->uuid, 'key']) }}', 'Private Key Content'); open = false" class="w-full flex items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                            Key
                                        </button>
                                        <button @click.prevent="openViewModal('{{ route('certificate.view', [$cert->uuid, 'csr']) }}', 'CSR Content'); open = false" class="w-full flex items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            CSR
                                        </button>
                                    </div>
                                </div>

                                <!-- Download Dropdown -->
                                <div x-data="{ open: false }" class="relative" @click.away="open = false; anyOpen = false">
                                    <button @click="open = !open; anyOpen = open" 
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-white bg-brand-500 rounded-lg hover:bg-brand-600 transition-all active:scale-90 shadow-md"
                                            title="Download Files">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        <span class="hidden xl:inline">Download</span>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="absolute left-0 mt-2 w-36 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-black/5 z-[110] overflow-hidden border border-gray-100 dark:border-gray-700">
                                        <a href="{{ route('certificate.download-zip', $cert->uuid) }}" class="flex items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            ZIP Bundle
                                        </a>
                                        <a href="{{ route('certificate.download-p12', $cert->uuid) }}" class="flex items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            .p12 File
                                        </a>
                                    </div>
                                </div>

                                <!-- Others Dropdown -->
                                <div x-data="{ open: false }" class="relative" @click.away="open = false; anyOpen = false">
                                    <button @click="open = !open; anyOpen = open" 
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-all active:scale-90 shadow-sm"
                                            title="Other Actions">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-2"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="absolute right-0 mt-2 w-32 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-black/5 z-[110] overflow-hidden border border-gray-100 dark:border-gray-700">
                                        <form action="{{ route('certificate.regenerate', $cert->uuid) }}" method="POST" onsubmit="return confirm('Regenerate this certificate? This will replace the current certificate and key.')">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                Regen
                                            </button>
                                        </form>
                                        <form action="{{ route('certificate.delete', $cert->uuid) }}" method="POST" onsubmit="return confirm('Truly delete this certificate?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full flex items-center px-4 py-2 text-xs text-error-600 hover:bg-error-50 dark:hover:bg-error-900/20 transition">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <p class="text-sm text-gray-500 italic">No certificates found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

                {{-- Filler rows to maintain professional layout height --}}
                @php 
                    $currentCount = $certificates->count();
                    $fillCount = 5 - ($currentCount ?: 1); 
                @endphp
                @for($i = 0; $i < $fillCount; $i++)
                    <tr class="border-transparent pointer-events-none select-none">
                        <td class="px-5 py-4" colspan="7">&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

@if ($certificates->hasPages())
    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 ajax-pagination">
        {{ $certificates->links() }}
    </div>
@endif
