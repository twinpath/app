<div x-data="{
    isOpen: false,
    search: '',
    results: {},
    selectedIndex: -1,
    isLoading: false,
    
    init() {
        // Global Hotkey
        window.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                this.toggle();
            }
            if (e.key === 'Escape' && this.isOpen) {
                this.toggle();
            }
        });

        // Listen for internal trigger
        window.addEventListener('open-global-search', () => this.open());
    },

    open() {
        this.isOpen = true;
        this.search = '';
        this.results = {};
        this.selectedIndex = -1;
        this.$nextTick(() => {
            $refs.searchInput.focus();
            this.fetchResults(); // Fetch initial navigation
        });
        document.body.classList.add('overflow-hidden');
    },

    close() {
        this.isOpen = false;
        document.body.classList.remove('overflow-hidden');
    },

    toggle() {
        this.isOpen ? this.close() : this.open();
    },

    async fetchResults() {
        // We still fetch if search.length < 2 to get the default navigation
        this.isLoading = true;
        try {
            const query = this.search.length >= 2 ? encodeURIComponent(this.search) : '';
            const response = await fetch(`/search/global?q=${query}`);
            this.results = await response.json();
            this.selectedIndex = -1;
        } catch (error) {
            console.error('Search failed:', error);
        } finally {
            this.isLoading = false;
        }
    },

    get flatResults() {
        const flat = [];
        Object.keys(this.results).forEach(group => {
            this.results[group].forEach(item => {
                flat.push({ ...item, group });
            });
        });
        return flat;
    },

    navigate(direction) {
        const total = this.flatResults.length;
        if (total === 0) return;

        if (direction === 'down') {
            this.selectedIndex = (this.selectedIndex + 1) % total;
        } else {
            this.selectedIndex = (this.selectedIndex - 1 + total) % total;
        }
        
        // Scroll select into view if needed
        this.$nextTick(() => {
            const el = document.getElementById(`search-result-${this.selectedIndex}`);
            if (el) el.scrollIntoView({ block: 'nearest' });
        });
    },

    select() {
        const item = this.flatResults[this.selectedIndex];
        if (item) {
            window.location.href = item.url;
        }
    }
}" @keydown.window.escape="close()" x-show="isOpen" 
   class="fixed inset-0 z-[99999] flex items-start justify-center pt-20 sm:pt-32"
   style="display: none;" x-cloak>
    
    <!-- Backdrop (Click to close) -->
    <div x-show="isOpen" 
         @click="close()" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm shadow-inner"></div>

    <!-- Modal Content -->
    <div x-show="isOpen" 
         @click.away="close()"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
         class="relative w-full max-w-2xl px-4 mx-auto">
        
        <div class="overflow-hidden bg-white rounded-2xl shadow-2xl dark:bg-gray-900 border border-gray-200 dark:border-gray-800 ring-1 ring-black/5">
            <!-- Search Input -->
            <div class="relative flex items-center px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input x-ref="searchInput" 
                       x-model="search" 
                       @input.debounce.300ms="fetchResults()"
                       @keydown.down.prevent="navigate('down')"
                       @keydown.up.prevent="navigate('up')"
                       @keydown.enter.prevent="select()"
                       type="text" 
                       class="w-full py-2 text-xl text-gray-800 bg-transparent border-none focus:ring-0 dark:text-gray-200 placeholder:text-gray-400" 
                       placeholder="Search certificates, tickets, or try 'Settings'...">
                
                <!-- Loading Indicator -->
                <div x-show="isLoading" class="absolute right-20 top-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 animate-spin text-brand-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- ESC Button UI -->
                <div @click="close()" 
                     class="cursor-pointer ml-4 hidden sm:flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold text-gray-400 bg-gray-100 hover:bg-gray-200 rounded-lg dark:bg-white/5 dark:text-gray-500 dark:hover:bg-white/10 border border-gray-200 dark:border-gray-800 transition-all active:scale-95">
                    <span class="text-[10px]">ESC</span>
                </div>
            </div>

            <!-- Results Area -->
            <div class="max-h-[60vh] overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-800">
                <template x-if="Object.keys(results).length === 0 && !isLoading">
                    <div class="px-4 py-12 text-center text-gray-500">
                        <template x-if="search.length < 2">
                            <p class="text-sm">Type to search for specific items...</p>
                        </template>
                        <template x-if="search.length >= 2">
                            <p class="text-sm">No results found for "<span class="font-medium text-gray-800 dark:text-gray-200" x-text="search"></span>"</p>
                        </template>
                        <div class="mt-6 flex flex-wrap justify-center gap-2 opacity-50">
                            <span class="px-2 py-1 text-[11px] bg-gray-50 dark:bg-white/5 rounded border border-gray-100 dark:border-gray-800">⌘ K to toggle</span>
                            <span class="px-2 py-1 text-[11px] bg-gray-50 dark:bg-white/5 rounded border border-gray-100 dark:border-gray-800">↑↓ to navigate</span>
                            <span class="px-2 py-1 text-[11px] bg-gray-50 dark:bg-white/5 rounded border border-gray-100 dark:border-gray-800">↵ to select</span>
                        </div>
                    </div>
                </template>

                <div class="space-y-4">
                    <template x-for="(items, group) in results" :key="group">
                        <div>
                            <h3 class="px-3 py-2 text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500" x-text="group"></h3>
                            <div class="space-y-1">
                                <template x-for="(item, index) in items" :key="item.url">
                                    @php $flatIndex = 'flatResults.findIndex(f => f.url === item.url)'; @endphp
                                    <a :id="'search-result-' + flatResults.findIndex(f => f.url === item.url)"
                                       :href="item.url" 
                                       @mouseenter="selectedIndex = flatResults.findIndex(f => f.url === item.url)"
                                       class="flex items-center gap-3 px-3 py-3 text-sm transition-colors rounded-xl group"
                                       :class="selectedIndex === flatResults.findIndex(f => f.url === item.url) ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'">
                                        
                                        <!-- Icon Wrapper -->
                                        <div class="flex items-center justify-center w-9 h-9 min-w-9 rounded-lg bg-gray-100 group-hover:bg-white dark:bg-gray-800 dark:group-hover:bg-gray-700 transition-colors"
                                             :class="selectedIndex === flatResults.findIndex(f => f.url === item.url) ? 'bg-white shadow-sm dark:bg-gray-700' : ''">
                                            <template x-if="item.icon === 'certificate'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A11.955 11.955 0 0121.056 12a11.955 11.955 0 01-2.944 5.96z" /></svg>
                                            </template>
                                            <template x-if="item.icon === 'user'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            </template>
                                            <template x-if="item.icon === 'ticket'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" /></svg>
                                            </template>
                                            <template x-if="item.icon === 'home'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                            </template>
                                            <template x-if="item.icon === 'key'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 11-7.743-5.743L11 7.001M11 7H9v2H7v2H4v3l2 2h3.5" /></svg>
                                            </template>
                                            <template x-if="item.icon === 'shield'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A11.955 11.955 0 0121.056 12a11.955 11.955 0 01-2.944 5.96z" /></svg>
                                            </template>
                                            <template x-if="item.icon === 'users'">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                            </template>
                                            <template x-if="!['certificate', 'user', 'ticket', 'home', 'shield', 'users', 'key'].includes(item.icon)">
                                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </template>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="font-medium truncate" x-text="item.label"></p>
                                                <span x-show="selectedIndex === flatResults.findIndex(f => f.url === item.url)" class="text-[10px] text-brand-500 font-bold">ENTER ↵</span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="item.sublabel || item.url"></p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-4 py-3 bg-gray-50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-[11px] text-gray-400">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm font-sans">↑↓</kbd> Navigate</span>
                    <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm font-sans">↵</kbd> Select</span>
                    <span class="flex items-center gap-1"><kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-sm font-sans">ESC</kbd> Close</span>
                </div>
                <div class="font-medium">Command Palette</div>
            </div>
        </div>
    </div>
</div>
