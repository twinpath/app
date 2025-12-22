@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 bg-white p-6 sm:p-0 dark:bg-gray-900">
        <div class="relative flex h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
            <div class="flex w-full flex-1 flex-col lg:w-1/2">
               <div class="w-full h-full flex items-center justify-center p-4 sm:p-12.5 xl:p-17.5 text-center">
                    <div class="max-w-md w-full">
                        <h2 class="mb-9 text-2xl font-bold text-black dark:text-white sm:text-title-xl2">
                            Verify Your Email
                        </h2>

                        <div class="mb-6">
                            <span class="inline-block p-4 rounded-full bg-brand-50 text-brand-500 dark:bg-brand-500/10 mb-4">
                                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6ZM20 6L12 11L4 6H20ZM20 18H4V8L12 13L20 8V18Z" fill="currentColor"/>
                                </svg>
                            </span>
                            <p class="text-gray-700 dark:text-gray-400">
                                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
                            </p>
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                If you didn't receive the email, we will gladly send you another.
                            </p>
                        </div>

                         @if (session('success'))
                            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit"
                                class="w-full cursor-pointer rounded-lg border border-brand-500 bg-brand-500 p-4 text-white transition hover:bg-opacity-90 hover:bg-brand-600">
                                Resend Verification Email
                            </button>
                        </form>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 underline">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-brand-950 relative hidden h-full w-full items-center lg:grid lg:w-1/2 dark:bg-white/5">
                <div class="z-1 flex items-center justify-center">
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
