@props(['show'])

<x-ui.modal x-model="{{ $show }}" containerClass="max-w-[400px]">
    <div class="p-6 text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 dark:bg-brand-500/10">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-brand-500"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        </div>
        <h3 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">Update Password?</h3>
        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
            Are you sure you want to change your password? You will need to use the new password for your next login.
        </p>
        <div class="flex gap-3">
            <button @click="open = false" class="flex-1 rounded-lg border border-gray-300 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/5">
                Cancel
            </button>
            <button @click="submitPasswordForm()" class="flex-1 rounded-lg bg-brand-500 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Yes, Update
            </button>
        </div>
    </div>
</x-ui.modal>
