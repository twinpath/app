<x-ui.modal x-model="viewOpen" containerClass="w-full max-w-[95vw] sm:w-auto sm:min-w-[500px] sm:max-w-3xl" style="z-index: 100010;">
    <div class="flex flex-col h-[80vh] sm:h-auto sm:max-h-[85vh]">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-white dark:bg-gray-900 sticky top-0 z-10 gap-8">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="viewTitle">Certificate Content</h3>
                <p class="mt-1 text-sm text-gray-500">View and copy the raw content below.</p>
            </div>
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a :href="viewUrl" target="_blank" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Raw View
                </a>
                <button @click="copyToClipboard()" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs">
                    <svg x-show="!copied" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    <svg x-show="copied" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span x-text="copied ? 'Copied!' : 'Copy Content'"></span>
                </button>
                <button @click="viewOpen = false" class="ml-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <span class="sr-only">Close</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-hidden relative bg-gray-50 dark:bg-gray-950">
            <!-- Loading State -->
            <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm z-20">
                <svg class="w-8 h-8 text-brand-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>

            <!-- Editor-like View -->
            <div class="h-full overflow-auto custom-scrollbar p-0">
                <pre class="p-6 text-sm font-mono leading-relaxed text-gray-800 dark:text-gray-200 whitespace-pre-wrap break-all select-all font-fira-code" x-text="viewContent"></pre>
            </div>
        </div>
    </div>
</x-ui.modal>
