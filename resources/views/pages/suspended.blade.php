@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
            <div class="flex w-full flex-1 flex-col lg:w-1/2">
               <div class="w-full h-full flex items-center justify-center p-4 sm:p-12.5 xl:p-17.5 text-center">
                    <div class="max-w-md w-full">
                        <h2 class="mb-9 text-2xl font-bold text-black dark:text-white sm:text-title-xl2">
                            Account Suspended
                        </h2>

                        <div class="mb-4">
                            <span class="inline-block p-4 rounded-full bg-red-50 text-red-500 dark:bg-red-500/10 mb-4">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 8V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.9945 16H12.0035" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <p class="text-lg font-medium text-black dark:text-white">
                                Your account has been suspended by the administrator.
                            </p>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">
                                 You cannot access the dashboard or perform any actions.
                            </p>
                            <p class="mt-4 text-gray-500 dark:text-gray-400">
                                 If you believe this is a mistake, please contact support.
                            </p>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="mt-8">
                            @csrf
                            <button type="submit"
                                class="w-full cursor-pointer rounded-lg border border-brand-500 bg-brand-500 p-4 text-white transition hover:bg-opacity-90 hover:bg-brand-600">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2 dark:bg-white/5">
                <div class="z-1 flex items-center justify-center">
                     <!-- ===== Common Grid Shape Start ===== -->
                     <x-common.common-grid-shape/>
                    <div class="flex max-w-xs flex-col items-center">
                        <a href="/" class="mb-4 block">
                             <img src="{{ asset('images/logo/auth-logo.svg') }}" alt="Logo" />
                        </a>
                        <p class="text-center text-gray-400 dark:text-white/60">
                            Secure Certificate Management System
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
