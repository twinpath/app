<div
    x-data="{
        toasts: [],
        add(data) {
            const id = Date.now();
            this.toasts.push({
                id: id,
                title: data.title || 'Notification',
                body: data.body || '',
                type: data.type || 'info',
                icon: data.icon || 'notification',
                progress: 100
            });

            // Handle progress bar animation
            const duration = 6000;
            const interval = 50;
            const step = (interval / duration) * 100;
            
            const timer = setInterval(() => {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) {
                    toast.progress -= step;
                } else {
                    clearInterval(timer);
                }
            }, interval);

            setTimeout(() => {
                this.remove(id);
                clearInterval(timer);
            }, duration);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    @reverb-notification.window="add($event.detail)"
    class="fixed top-24 right-5 sm:right-10 z-[1000] flex flex-col gap-4 w-auto pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-[-20px] translate-x-12 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 translate-x-0 scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-20"
            class="pointer-events-auto relative overflow-hidden rounded-2xl border border-white/20 dark:border-white/10 bg-white/95 dark:bg-gray-900/95 backdrop-blur-3xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] p-5 flex items-start gap-4 transition-all hover:scale-[1.02] w-80 sm:w-96"
        >
            <!-- Background Accent Glow -->
            <div class="absolute -left-10 -top-10 w-24 h-24 blur-3xl opacity-20 pointer-none"
                :class="{
                    'bg-blue-500': toast.type === 'info',
                    'text-green-500': toast.type === 'success',
                    'text-yellow-500': toast.type === 'warning',
                    'text-red-500': toast.type === 'error'
                }"></div>

            <!-- Icon Ring -->
            <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center shadow-inner"
                :class="{
                    'bg-blue-500/10 text-blue-600 dark:text-blue-400': toast.type === 'info',
                    'bg-green-500/10 text-green-600 dark:text-green-400': toast.type === 'success',
                    'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400': toast.type === 'warning',
                    'bg-red-500/10 text-red-600 dark:text-red-400': toast.type === 'error'
                }">
                <template x-if="toast.icon === 'ticket'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4v-3a2 2 0 002-2V7a2 2 0 00-2-2H5z"></path></svg>
                </template>
                <template x-if="toast.icon === 'chat'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </template>
                <template x-if="toast.icon !== 'ticket' && toast.icon !== 'chat'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0 pr-4">
                <h4 class="text-base font-bold text-gray-900 dark:text-white leading-tight" x-text="toast.title"></h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 lines-2 font-medium" x-text="toast.body"></p>
            </div>

            <!-- Close Button -->
            <button @click="remove(toast.id)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 transition-all ease-linear"
                :style="`width: ${toast.progress}%`"
                :class="{
                    'bg-blue-500': toast.type === 'info',
                    'bg-green-500': toast.type === 'success',
                    'bg-yellow-500': toast.type === 'warning',
                    'bg-red-500': toast.type === 'error'
                }"></div>
        </div>
    </template>
</div>

