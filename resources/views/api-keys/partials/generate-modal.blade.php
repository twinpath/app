<x-ui.modal @open-modal.window="if ($event.detail === 'generate-api-key') open = true" :isOpen="false" containerClass="max-w-[700px]">
    <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
        <div class="px-2 pr-14">
            <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                Generate API Key
            </h4>
            <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                To enable secure access to the web services, your app requires an API key with permissions for resources.
            </p>
        </div>

        <form action="{{ route('api-keys.store') }}" method="POST" class="flex flex-col">
            @csrf
            <div class="px-2">
                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Application Name
                        </label>
                        <input type="text" name="name" id="name" required
                            placeholder="e.g. My Awesome App"
                            class="dark:bg-dark-900 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                            Naming your application makes it easier to recognize your API key in the future.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                <button @click="open = false" type="button"
                    class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                    Close
                </button>
                <button type="submit"
                    class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                    Generate API Key
                </button>
            </div>
        </form>
    </div>
</x-ui.modal>
