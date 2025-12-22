<div x-data="{ selectedKey: null }">
    <x-ui.modal 
        x-on:open-modal.window="if ($event.detail.name === 'delete-api-key') { open = true; selectedKey = $event.detail.apiKey; }"
        :isOpen="false" 
        containerClass="max-w-[700px]">
        <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Delete API Key
                </h4>
                <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                    Are you sure you want to delete this API key? This action cannot be undone.
                </p>
            </div>

            <div class="px-2 text-center mb-8">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/10">
                    <svg class="h-10 w-10 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <p class="mt-4 text-base text-gray-600 dark:text-gray-400">
                    Any applications using the key <span x-text="selectedKey ? selectedKey.name : ''" class="font-bold text-gray-900 dark:text-white"></span> will stop working immediately.
                </p>
            </div>

            <form x-bind:action="selectedKey ? '{{ route('api-keys.index') }}/' + selectedKey.id : '#'" method="POST" class="flex flex-col">
                @csrf
                @method('DELETE')
                <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                    <button @click="open = false" type="button"
                        class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 sm:w-auto">
                        Delete API Key
                    </button>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
