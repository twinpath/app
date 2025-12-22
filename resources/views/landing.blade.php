@extends('layouts.fullscreen-layout', ['title' => 'Secure Certificate & API Management'])

@section('meta_description', 'Manage Root CA, Intermediate CAs, and API keys through a powerful developer portal. Fast, secure, and ready for production.')
@section('meta_keywords', 'ssl certificate, tls issuance, api management, ca authority, security dashboard')

@section('content')
<script>
    // Define global scroll function immediately
    window.appSmoothScroll = function(selector) {
        const element = document.querySelector(selector);
        
        if (!element) {
            console.error('[App] Scroll target not found:', selector);
            return;
        }

        const navbarOffset = 80;
        const elementPosition = element.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - navbarOffset;


        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });

        // Clean URL after a short delay
        setTimeout(() => {
            if (window.location.hash) {
                history.replaceState(null, null, window.location.pathname);
            }
        }, 500);
    };

    // Handle initial hash
    window.addEventListener('DOMContentLoaded', () => {
        if (window.location.hash) {
            setTimeout(() => window.appSmoothScroll(window.location.hash), 500);
        }
    });
</script>

<div class="relative min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300 overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -translate-x-1/4 translate-y-1/4 w-[400px] h-[400px] bg-brand-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <!-- Navbar -->
    <x-public.navbar />


    <!-- Hero Section -->
    <header class="relative pt-32 pb-20 overflow-hidden" id="home">
        <!-- Background Shapes -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full -z-10 opacity-30 dark:opacity-20">
            <div class="absolute top-20 left-10 w-72 h-72 bg-brand-500 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-blue-500 rounded-full blur-[150px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 text-brand-600 dark:text-brand-400 text-xs font-bold uppercase tracking-widest mb-8 animate-bounce">
                🚀 Unified Certificate Management
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                Secure Your Assets with <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-blue-600">
                    Trusted Certificate Authority
                </span>
            </h1>
            <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-10">
                Issue, manage, and track SSL/TLS certificates and API keys through a powerful, developer-friendly management system.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('signup') }}" class="w-full sm:w-auto px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl font-bold shadow-xl transition-all hover:-translate-y-1 hover:shadow-2xl">
                    Create Global Account
                </a>
                <a href="#features" @click.prevent="window.appSmoothScroll('#features')" class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 rounded-2xl font-bold transition-all hover:bg-gray-50 dark:hover:bg-gray-700">
                    Explore Features
                </a>
            </div>

            <!-- Preview/Abstract UI -->
            <div class="mt-20 relative mx-auto max-w-5xl">
                <div class="aspect-video bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-2xl p-4 overflow-hidden group">
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-red-400"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <div class="flex-1 ml-4 h-6 bg-gray-100 dark:bg-gray-900/50 rounded-lg max-w-xs"></div>
                    </div>
                    <!-- Mock Dashboard Content -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 space-y-4">
                            <div class="h-40 bg-brand-500/5 rounded-2xl border border-brand-500/10"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="h-24 bg-gray-50 dark:bg-gray-900/50 rounded-2xl"></div>
                                <div class="h-24 bg-gray-50 dark:bg-gray-900/50 rounded-2xl"></div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-full bg-gray-50 dark:bg-gray-900/50 rounded-2xl"></div>
                        </div>
                    </div>
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-gray-900 via-transparent to-transparent pointer-events-none"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-50 dark:bg-gray-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Powerful Features for Modern Apps</h2>
                <p class="text-gray-600 dark:text-gray-400">Everything you need to manage your security layer efficiently.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-brand-50 dark:bg-brand-500/10 rounded-2xl flex items-center justify-center text-brand-500 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Custom CA Issuance</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Issue professional Root and Intermediate CA certificates with a single click. Fully compliant with standard encryption protocols.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11 9 13.536 7.464 12 4.929 14.536V17h2.472l4.243-4.243a6 6 0 018.828-5.743zM16.5 13.5V18h6v-4.5h-6z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">API Management</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Secure your external services with granular API keys. Track usage patterns and revoke access instantly when needed.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-green-50 dark:bg-green-500/10 rounded-2xl flex items-center justify-center text-green-500 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Real-time Tracking</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Monitor issuance trends and expiring certificates through intuitive analytical dashboards and automated alerts.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-brand-600 rounded-[3rem] p-12 md:p-16 text-center text-white relative overflow-hidden shadow-2xl">
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to secure your application?</h2>
                    <p class="text-brand-100 mb-10 max-w-lg mx-auto">Join hundreds of developers managing their security infrastructure with {{ config('app.name') }}.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('signup') }}" class="px-8 py-4 bg-white text-brand-600 rounded-2xl font-bold hover:scale-105 transition-transform">
                            Create Free Account
                        </a>
                        <a href="{{ route('signin') }}" class="px-8 py-4 bg-brand-700 text-white rounded-2xl font-bold hover:bg-brand-800 transition-colors">
                            Sign In to Portal
                        </a>
                    </div>
                </div>
                <!-- Abstract Design -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                    <x-common.common-grid-shape/>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-public.footer />

    <!-- Back to Top Button -->
    <button 
        x-data="{ show: false }"
        x-on:scroll.window="show = window.pageYOffset > 500"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        @click="window.appSmoothScroll('#home')"
        class="fixed bottom-8 right-8 z-50 p-4 bg-brand-500 hover:bg-brand-600 text-white rounded-2xl shadow-2xl shadow-brand-500/40 transition-all hover:-translate-y-1 active:scale-95"
        aria-label="Back to top"
    >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
</div>

@endsection
