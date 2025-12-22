<div x-data="{ 
    show: false,
    selectedKey: null, 
    newKey: '', 
    step: 'confirm', 
    loading: false,
    async regenerate() {
        if (this.loading) return;
        this.loading = true;
        try {
            const response = await fetch(`{{ route('api-keys.index') }}/${this.selectedKey.id}/regenerate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                this.newKey = data.new_key;
                this.step = 'success';
                // Update the key in the table if possible
                if (this.selectedKey) {
                    this.selectedKey.key = data.new_key;
                }
            } else {
                alert(data.message || 'Error regenerating API key');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error regenerating API key. Please try again.');
        } finally {
            this.loading = false;
        }
    }
}"
x-on:open-modal.window="if ($event.detail.name === 'confirm-regenerate-api-key') { show = true; selectedKey = $event.detail.apiKey; step = 'confirm'; newKey = ''; loading = false; }">
    <x-ui.modal x-model="show" :isOpen="false" containerClass="max-w-[700px]">
        
        <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11">
            
            <!-- Step: Confirm -->
            <template x-if="step === 'confirm'">
                <div>
                    <div class="px-2 pr-14">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                            Regenerate API Key
                        </h4>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                            Are you sure you want to regenerate this API key?
                        </p>
                    </div>

                    <div class="px-2 text-center mb-8">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-900/10">
                            <svg class="h-10 w-10 text-brand-600 dark:text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </div>
                        <p class="mt-4 text-base text-gray-600 dark:text-gray-400">
                            The old key for <span x-text="selectedKey ? selectedKey.name : ''" class="font-bold text-gray-900 dark:text-white"></span> will stop working immediately.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button" :disabled="loading"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto disabled:opacity-50">
                            Cancel
                        </button>
                        <button @click="regenerate()" type="button" :disabled="loading"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto disabled:opacity-50">
                            <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Regenerating...' : 'Regenerate Key'"></span>
                        </button>
                    </div>
                </div>
            </template>

            <!-- Step: Success -->
            <template x-if="step === 'success'">
                <div>
                    <div class="px-2 pr-14">
                        <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                            New API Key Generated
                        </h4>
                        <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">
                            Please copy your new API key now. You won't be able to see it again!
                        </p>
                    </div>

                    <div class="px-2 mb-8">
                        <div class="flex items-center gap-2">
                            <code x-text="newKey"
                                class="flex-1 bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 font-mono text-brand-600 dark:text-brand-400 break-all text-sm">
                            </code>
                            <button x-data="{ copied: false }"
                                @click="navigator.clipboard.writeText(newKey); copied = true; setTimeout(() => copied = false, 2000)"
                                class="p-4 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors border border-gray-200 dark:border-gray-700">
                                <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg x-show="copied" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="open = false" type="button"
                            class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Done
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </x-ui.modal>
</div>

