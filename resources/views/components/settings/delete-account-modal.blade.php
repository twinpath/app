@props(['show', 'deleteConfirmation'])

<x-ui.modal x-model="{{ $show }}" containerClass="max-w-[450px]">
    <div class="p-6">
        <div class="mb-4 flex items-center gap-3 text-error-600">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-error-50 dark:bg-error-500/10">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </div>
            <h3 class="text-lg font-semibold">Delete Account</h3>
        </div>
        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
            This action is <span class="font-bold text-error-600">irreversible</span>. All your data will be permanently removed.
            To confirm, please type <span class="font-mono font-bold text-gray-800 dark:text-white">Yes I will delete my account</span> below.
        </p>
        
        <form action="{{ route('settings.destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="mb-6">
                <input type="text" name="confirmation" x-model="{{ $deleteConfirmation }}" placeholder="Type the confirmation phrase"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2 text-sm text-gray-800 placeholder:text-gray-400 focus:border-error-300 focus:outline-hidden focus:ring-3 focus:ring-error-500/10 dark:border-gray-700 dark:bg-white/[0.03] dark:text-white/90">
            </div>

            <div class="flex gap-3">
                <button type="button" @click="open = false" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                    Cancel
                </button>
                <button type="submit" :disabled="{{ $deleteConfirmation }} !== 'Yes I will delete my account'" 
                    class="flex-1 rounded-lg bg-error-500 py-2.5 text-sm font-medium text-white hover:bg-error-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    Delete My Account
                </button>
            </div>
        </form>
    </div>
</x-ui.modal>
