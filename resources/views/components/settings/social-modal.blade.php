@props(['show', 'socialProvider', 'submitAction'])

<x-ui.modal x-model="{{ $show }}" containerClass="max-w-[400px]">
    <div class="p-6 text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-warning-50 dark:bg-warning-500/10">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-warning-500"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">
            <span x-text="socialAction === 'connect' ? 'Connect' : 'Disconnect'"></span> <span x-text="{{ $socialProvider }}"></span>?
        </h3>
        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            Are you sure you want to <span x-text="socialAction"></span> your <span x-text="{{ $socialProvider }}"></span> account?
        </p>
        <div class="flex gap-3">
            <button @click="open = false" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                Cancel
            </button>
            <button @click="{{ $submitAction }}" class="flex-1 rounded-lg py-2.5 text-sm font-medium text-white transition-colors"
                :class="socialAction === 'connect' ? 'bg-brand-500 hover:bg-brand-600' : 'bg-warning-500 hover:bg-warning-600'">
                Confirm
            </button>
        </div>
    </div>
</x-ui.modal>
