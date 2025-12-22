@extends('layouts.app')

@section('content')
    <div x-data="{ createOpen: {{ $errors->any() ? 'true' : 'false' }} }">
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                    Certificate Management
                </h2>
                <nav class="mt-1">
                    <ol class="flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
                        <li>
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 hover:text-brand-500 transition">
                                Home
                            </a>
                        </li>
                        <li>
                            <svg class="stroke-current opacity-60" width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.75 2.5L6.25 5L3.75 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </li>
                        <li class="font-medium text-gray-800 dark:text-white/90">Certificate Management</li>
                    </ol>
                </nav>
            </div>
            <div>
                @if($caReady)
                    <button @click="createOpen = true" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Generate New SSL
                    </button>
                @endif
            </div>
        </div>

    @if(!$caReady)
        <div class="p-6 mb-6 border border-yellow-200 rounded-xl bg-yellow-50 dark:bg-yellow-900/10 dark:border-yellow-900/30">
            <div class="flex items-start">
                <div class="p-3 bg-yellow-100 rounded-lg dark:bg-yellow-900/30">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-bold text-yellow-800 dark:text-yellow-200">Setup Required</h4>
                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">Root CA and Intermediate CA have not been initialized in the database yet.</p>
                    
                    @if(Auth::user()->isAdmin())
                        <form action="{{ route('admin.setup-ca') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-semibold text-white transition rounded-lg bg-yellow-600 hover:bg-yellow-700 shadow-theme-xs">
                                Run CA Setup Now
                            </button>
                        </form>
                    @else
                        <div class="mt-4 p-3 bg-yellow-200/50 rounded text-sm text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200">
                             <strong>Action Required:</strong> Please contact your administrator to initialize the Root CA.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6" x-data="{
        search: '{{ $search }}',
        perPage: '{{ $perPage }}',
        loading: false,
        updateTable(url = null) {
            this.loading = true;
            let baseUrl = url || '{{ route('certificate.index') }}';
            let finalUrl = new URL(baseUrl);
            
            if (!url) {
                finalUrl.searchParams.set('search', this.search);
                finalUrl.searchParams.set('per_page', this.perPage);
            }

            fetch(finalUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(html => {
                let container = document.getElementById('certificate-table-container');
                container.innerHTML = html;
                this.loading = false;
                if (typeof Alpine !== 'undefined') {
                    Alpine.initTree(container);
                }
            })
            .catch(err => {
                console.error('Error fetching content:', err);
                this.loading = false;
            });
        },

        // View Modal Logic
        viewOpen: false,
        viewTitle: '',
        viewContent: '',
        viewUrl: '',
        loading: false,
        copied: false,
        
        openViewModal(url, title) {
            this.viewUrl = url;
            this.viewTitle = title;
            this.viewContent = '';
            this.viewOpen = true;
            this.loading = true;
            this.copied = false;

            fetch(url)
                .then(res => res.text())
                .then(text => {
                    this.viewContent = text;
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error fetching content:', err);
                    this.viewContent = 'Failed to load content.';
                    this.loading = false;
                });
        },

        copyToClipboard() {
            navigator.clipboard.writeText(this.viewContent).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        }
    }">
        <x-common.component-card>
            <x-slot:header>
                <h3 class="flex items-center text-base font-medium text-gray-800 dark:text-white/90">
                    <svg class="w-5 h-5 mr-2.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    SSL Certificates List
                </h3>
            </x-slot:header>
            <!-- DataTables Header Utility -->
            <div class="flex flex-col gap-4 mb-6 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-500">Show</span>
                    <select x-model="perPage" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-brand-500">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-500">entries</span>
                </div>

                <div class="relative w-full md:w-64">
                    <input type="text" x-model="search" placeholder="Search certificates..." 
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-brand-500 focus:border-brand-500">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg x-show="!loading" class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <svg x-show="loading" class="w-4 h-4 text-brand-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                </div>
            </div>

            <div id="certificate-table-container" class="relative">
                @include('pages.certificate.partials.table')
            </div>
        </x-common.component-card>

        @if($caReady)
            <x-common.component-card>
                <x-slot:header>
                    <h3 class="flex items-center text-base font-medium text-gray-800 dark:text-white/90">
                        <svg class="w-5 h-5 mr-2.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Download Root CA Certificates
                    </h3>
                </x-slot:header>
                <p class="text-sm text-gray-500 mb-6">These are the authority certificates used to sign your SSLs. Install them on your machine/browser to trust your generated certificates.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('certificate.download-ca', 'root') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-brand-700 bg-brand-50 rounded-lg hover:bg-brand-100 transition dark:bg-brand-900/20 dark:text-brand-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Root CA (.crt)
                    </a>
                    <a href="{{ route('certificate.download-ca', 'int_2048') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Int-2048 CA (.crt)
                    </a>
                    <a href="{{ route('certificate.download-ca', 'int_4096') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Int-4096 CA (.crt)
                    </a>
                    <a href="{{ route('certificate.download-ca-bundle') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-success-700 bg-success-50 rounded-lg hover:bg-success-100 transition dark:bg-success-900/20 dark:text-success-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        CA Bundle (Windows)
                    </a>
                    <a href="{{ route('certificate.download-ca-android') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-warning-700 bg-warning-50 rounded-lg hover:bg-warning-100 transition dark:bg-warning-900/20 dark:text-warning-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        CA Android (.der)
                    </a>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                     <a href="{{ route('certificate.download-installer') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Windows One-Click Installer (.bat)
                    </a>
                </div>
            </x-common.component-card>
        @endif

        @include('pages.certificate.partials.view-modal')
    </div>

    @include('pages.certificate.partials.create-modal')
@endsection
