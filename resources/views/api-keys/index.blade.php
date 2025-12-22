@extends('layouts.app')

@section('title', 'API Keys')

@section('content')
    <div class="p-6" x-data="apiKeyManager">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">API Keys</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">API keys are used to authenticate requests to the DyDev APP API</p>
            </div>
            <button @click="$dispatch('open-modal', 'generate-api-key')"
                class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white rounded-lg transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Add API Key</span>
            </button>
        </div>

        @if (session('success'))
            <div x-init="setTimeout(() => $el.remove(), 5000)" class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('generated_key'))
            <div class="mb-6 p-6 bg-brand-50 dark:bg-gray-800 border border-brand-200 dark:border-gray-700 rounded-lg">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">New API Key Generated</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Please copy this key immediately. You won't be able to see
                    it again!</p>
                <div class="flex items-center gap-2">
                    <code
                        class="flex-1 bg-white dark:bg-gray-900 p-3 rounded border border-gray-200 dark:border-gray-700 font-mono text-brand-600 dark:text-brand-400 break-all">
                        {{ session('generated_key') }}
                    </code>
                    <button x-data="{ copied: false }"
                        @click="navigator.clipboard.writeText('{{ session('generated_key') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-3 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <span x-show="!copied">Copy</span>
                        <span x-show="copied" x-cloak class="text-green-600">Copied!</span>
                    </button>
                </div>
            </div>
        @endif

        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">Show</span>
                    <select name="per_page" x-model="perPage" @change="updateTable()"
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-lg focus:ring-brand-500 focus:border-brand-500 block p-2">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">entries</span>
                </div>

                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input.debounce.500ms="updateTable()"
                        class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-white focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" 
                        placeholder="Search API keys...">
                </div>
            </div>
        </div>

        <div id="api-keys-table-container" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden relative"
             @click.prevent="if($event.target.closest('a')) handlePagination($event.target.closest('a').href)">
            <div x-show="loading" class="absolute inset-0 bg-white/50 dark:bg-gray-800/50 flex items-center justify-center z-10" x-cloak>
                <svg class="animate-spin h-8 w-8 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            @include('api-keys.partials.table')
        </div>

        @include('api-keys.partials.api-docs')
    </div>

    @include('api-keys.partials.generate-modal')
    @include('api-keys.partials.edit-modal')
    @include('api-keys.partials.delete-modal')
    @include('api-keys.partials.regenerate-modal')

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('apiKeyManager', () => ({
                search: '{{ request('search') }}',
                perPage: '{{ request('per_page', 10) }}',
                loading: false,

                async updateTable(url = null) {
                    this.loading = true;
                    if (!url) {
                        url = new URL('{{ route('api-keys.index') }}');
                        url.searchParams.set('search', this.search);
                        url.searchParams.set('per_page', this.perPage);
                    }

                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const html = await response.text();
                        document.getElementById('api-keys-table-container').innerHTML = html;
                        
                        // Re-initialize Alpine components in the new HTML if necessary
                        // Alpine handles this automatically if innerHTML is swapped and elements have x-data
                    } catch (error) {
                        console.error('Error updating table:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                handlePagination(url) {
                    if (url && url !== '#') {
                        this.updateTable(url);
                    }
                },

                maskKey(key) {
                    if (!key) return '';
                    return key.substring(0, 12) + '********************' + key.substring(key.length - 4);
                },

                async toggleStatus(key) {
                    try {
                        const response = await fetch(`{{ route('api-keys.index') }}/${key.id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            key.is_active = !key.is_active;
                        }
                    } catch (error) {
                        console.error('Error toggling status:', error);
                    }
                }
            }));
        });
    </script>
    @endpush
@endsection
