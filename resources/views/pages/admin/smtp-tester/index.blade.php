@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
    <!-- Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-semibold text-black dark:text-white">
            SMTP Tester
        </h2>

        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                        href="{{ route('dashboard') }}">
                        Dashboard /
                    </a>
                </li>
                <li class="font-medium text-brand-500">SMTP Tester</li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 gap-9 sm:grid-cols-2">
        <!-- Tester Form -->
        <div class="flex flex-col gap-9">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">
                        Run Connection Test
                    </h3>
                </div>
                
                <form action="{{ route('admin.smtp-tester.send') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="mb-2.5 block font-medium text-black dark:text-white">
                            Select Mailer Configuration
                        </label>
                        <div class="relative z-20 bg-transparent dark:bg-form-input">
                            <select name="mailer" id="mailerSelect" class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent px-5 py-3 outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:focus:border-brand-500">
                                @foreach($configs as $key => $config)
                                    <option value="{{ $key }}" {{ old('mailer') == $key ? 'selected' : '' }}>
                                        {{ $config['name'] }} ({{ $config['host'] }}:{{ $config['port'] }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-1/2 z-30 -translate-y-1/2">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.10186 11.1044C5.97864 11.2384 5.91703 11.3857 5.91703 11.5457C5.91703 11.7214 5.97864 11.8726 6.10186 11.9991L11.5597 17.5108C11.6967 17.6492 11.8526 17.7184 12.0274 17.7184C12.2022 17.7184 12.3582 17.6492 12.4951 17.5108L17.8981 11.9991C18.0214 11.8726 18.083 11.7214 18.083 11.5457C18.083 11.3857 18.0214 11.2384 17.8981 11.1044C17.7612 10.9571 17.6052 10.8834 17.4304 10.8834C17.2556 10.8834 17.0997 10.9571 16.9628 11.1044L12.0274 16.1265L7.03714 11.1044C6.90022 10.9571 6.74426 10.8834 6.56948 10.8834C6.39469 10.8834 6.23873 10.9571 6.10186 11.1044Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="mb-2.5 block font-medium text-black dark:text-white">
                            Target Email Address
                        </label>
                        <input type="email" name="email" required placeholder="Enter your email to receive test..." value="{{ auth()->user()->email }}"
                            class="w-full rounded border border-stroke bg-transparent px-5 py-3 outline-none transition focus:border-brand-500 active:border-brand-500 dark:border-gray-700 dark:bg-gray-800 dark:focus:border-brand-500" />
                        <p class="mt-1 text-xs text-gray-500">
                            We will send a raw test email to this address.
                        </p>
                    <div class="mb-4">
                        <label class="mb-2.5 block font-medium text-black dark:text-white">
                            Test Mode
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mode" value="raw" checked class="text-brand-500 focus:ring-brand-500">
                                <span class="text-gray-900 dark:text-white">Raw Text (Connection Check)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mode" value="mailable" class="text-brand-500 focus:ring-brand-500">
                                <span class="text-gray-900 dark:text-white">ContactReply Mailable (Template Check)</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="flex w-full justify-center rounded bg-brand-500 p-3 font-medium text-gray hover:bg-opacity-90">
                        Send Test Email
                    </button>
                </form>                
            </div>
            
            @if(session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/10">
                    <div class="flex gap-3">
                        <div class="text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-green-900 dark:text-green-100">Test Successful</h4>
                            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/10">
                    <div class="flex gap-3">
                        <div class="text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-red-900 dark:text-red-100">Connection Failed</h4>
                            <p class="text-sm text-red-700 dark:text-red-300 break-all">{{ session('error') }}</p>
                            <p class="mt-2 text-xs text-red-600 dark:text-red-400">Please check your .env configuration and ensure your SMTP server is accessible.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Configuration Details -->
        <div class="flex flex-col gap-9">
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">
                        Current Configuration (Read-Only)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($configs as $key => $config)
                            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                                <h4 class="font-bold text-brand-500 mb-3 uppercase text-xs tracking-wider">
                                    {{ $config['name'] }}
                                </h4>
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-gray-500">Host</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $config['host'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-gray-500">Port</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $config['port'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-gray-500">Username</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white text-ellipsis overflow-hidden">{{ $config['username'] ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <dt class="text-xs font-medium text-gray-500">Encryption</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white uppercase">{{ $config['encryption'] ?? 'None' }}</dd>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <dt class="text-xs font-medium text-gray-500">From Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $config['from'] ?? 'N/A' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple script to update expected 'From' display based on selection if needed, 
    // but the backend handles the actual sending.
</script>
@endsection
