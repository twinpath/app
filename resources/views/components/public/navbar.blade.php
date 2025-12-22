<nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3">
                <div class="p-2 bg-brand-500 rounded-xl shadow-lg shadow-brand-500/20">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-500 dark:from-white dark:to-gray-400">
                    {{ config('app.name') }}
                </span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Home</a>
                <a href="{{ route('home') }}#features" @if(Route::is('home')) @click.prevent="window.appSmoothScroll('#features')" @endif class="text-gray-600 dark:text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Features</a>
                
                <!-- Tools Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-1 text-gray-600 dark:text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">
                        Tools
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        style="display: none;"
                        class="absolute left-0 mt-3 w-64 rounded-2xl bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50 overflow-hidden">
                        <a href="{{ route('tools.chat-id-finder') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-brand-500 transition-all">
                            <div class="w-8 h-8 bg-brand-50 dark:bg-brand-500/10 rounded-lg flex items-center justify-center text-brand-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                            </div>
                            <div>
                                <div class="font-bold">Chat ID Finder</div>
                                <div class="text-[10px] text-gray-400">Find your Telegram ID</div>
                            </div>
                        </a>
                        <a href="{{ route('tools.app-key-generator') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-brand-500 transition-all">
                            <div class="w-8 h-8 bg-blue-50 dark:bg-blue-500/10 rounded-lg flex items-center justify-center text-blue-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                            </div>
                            <div>
                                <div class="font-bold">App Key Generator</div>
                                <div class="text-[10px] text-gray-400">Secure Laravel keys</div>
                            </div>
                        </a>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="text-gray-600 dark:text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Contact</a>
                <a href="{{ route('signin') }}" class="text-gray-600 dark:text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">Sign In</a>
                
                <!-- Theme Toggle -->
                <button @click.prevent="$store.theme.toggle()" class="p-2 ml-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                    <svg x-show="$store.theme.theme === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="$store.theme.theme === 'dark'" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                <a href="{{ route('signup') }}" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-xl font-semibold shadow-lg shadow-brand-500/25 transition-all hover:scale-105">
                    Get Started
                </a>
            </div>

            <!-- Mobile Header Actions -->
            <div class="md:hidden flex items-center gap-2">
                <button @click.prevent="$store.theme.toggle()" class="p-2 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                    <svg x-show="$store.theme.theme === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="$store.theme.theme === 'dark'" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
                <button @click.prevent="$store.sidebar.toggleMobileOpen()" class="p-2 text-gray-600 dark:text-gray-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Navigation Overlay -->
<div x-show="$store.sidebar.isMobileOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    style="display: none;"
    class="fixed inset-0 z-40 md:hidden bg-white dark:bg-gray-900 pt-24 px-6">
    <div class="flex flex-col gap-6">
        <a href="{{ route('home') }}" @click="$store.sidebar.setMobileOpen(false)" class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4">Home</a>
        <a href="{{ route('home') }}#features" @click="$store.sidebar.setMobileOpen(false); if(Route::is('home')) window.appSmoothScroll('#features')" class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4">Features</a>
        
        <div x-data="{ expanded: false }">
            <button @click="expanded = !expanded" class="w-full flex justify-between items-center text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4">
                Tools
                <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="expanded" class="pl-4 pt-4 space-y-4" style="display: none;">
                <a href="{{ route('tools.chat-id-finder') }}" class="block text-gray-600 dark:text-gray-400 font-medium">Chat ID Finder</a>
                <a href="{{ route('tools.app-key-generator') }}" class="block text-gray-600 dark:text-gray-400 font-medium">App Key Generator</a>
            </div>
        </div>

        <a href="{{ route('contact') }}" class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4">Contact</a>
        <a href="{{ route('signin') }}" class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4">Sign In</a>
        <a href="{{ route('signup') }}" class="mt-6 w-full py-4 bg-brand-500 text-white rounded-2xl font-bold text-center shadow-xl shadow-brand-500/20">Get Started</a>
    </div>
</div>
