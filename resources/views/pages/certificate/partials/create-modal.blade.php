<x-ui.modal x-model="createOpen" containerClass="max-w-3xl" style="z-index: 100009;">
    <div class="p-6 sm:p-10">
        <div class="mb-6 pr-10">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Generate SSL Certificate</h3>
            <p class="mt-1 text-sm text-gray-500 text-balance">Create a new self-signed certificate using your Local CA. This will be signed by your private root authority.</p>
        </div>

        <form action="{{ route('certificate.generate') }}" method="POST" class="space-y-8"
              x-data="{ 
                  common_name: '{{ old('common_name') }}', 
                  config_mode: '{{ old('config_mode', 'default') }}',
                  key_bits: '2048',
                  san: '{{ old('san') }}',
                  isValid() {
                      return this.common_name.length > 3 && this.common_name.includes('.');
                  }
              }">
            @csrf
            
            <!-- Configuration Mode -->
            <div>
                <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Configuration Mode</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="relative flex p-4 cursor-pointer rounded-xl border transition-all"
                        :class="config_mode === 'default' ? 'border-brand-500 ring-1 ring-brand-500 bg-brand-50/50 dark:bg-brand-900/10 dark:border-brand-400' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'">
                        <input type="radio" name="config_mode" value="default" x-model="config_mode" class="sr-only">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-5 h-5 mt-0.5 border rounded-full transition-colors"
                                :class="config_mode === 'default' ? 'border-brand-500 bg-brand-500' : 'border-gray-300 dark:border-gray-600'">
                                <div class="w-2 h-2 bg-white rounded-full" x-show="config_mode === 'default'"></div>
                            </div>
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">Default Presets</span>
                                <span class="block mt-0.5 text-xs text-gray-500 dark:text-gray-400">Use system defaults for Organization, Locality, and Country settings.</span>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex p-4 cursor-pointer rounded-xl border transition-all"
                        :class="config_mode === 'manual' ? 'border-brand-500 ring-1 ring-brand-500 bg-brand-50/50 dark:bg-brand-900/10 dark:border-brand-400' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'">
                        <input type="radio" name="config_mode" value="manual" x-model="config_mode" class="sr-only">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center flex-shrink-0 w-5 h-5 mt-0.5 border rounded-full transition-colors"
                                :class="config_mode === 'manual' ? 'border-brand-500 bg-brand-500' : 'border-gray-300 dark:border-gray-600'">
                                <div class="w-2 h-2 bg-white rounded-full" x-show="config_mode === 'manual'"></div>
                            </div>
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">Manual Configuration</span>
                                <span class="block mt-0.5 text-xs text-gray-500 dark:text-gray-400">Customise all Distinguised Name (DN) attributes manually.</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Primary Details -->
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Common Name (Domain) <span class="text-error-500">*</span></label>
                    <input type="text" name="common_name" x-model="common_name" placeholder="e.g. example.com or e.g. 127.0.0.1" required
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-colors"
                        :class="common_name && !isValid() ? 'border-error-500 focus:ring-error-500/20 focus:border-error-500' : ''">
                    <p x-show="common_name && !isValid()" class="mt-1.5 text-xs text-error-500 animate-pulse">Please enter a valid domain name containing at least one dot.</p>
                    <p class="mt-1.5 text-xs text-gray-500">The primary Fully Qualified Domain Name (FQDN) or IP Address to be secured.</p>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Subject Alternative Names (SAN)</label>
                    <input type="text" name="san" x-model="san" placeholder="e.g. api.local, 192.168.1.50"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-colors">
                    <p class="mt-1.5 text-xs text-gray-500">Optional comma-separated list of additional domains or IPs.</p>
                </div>
            </div>

            <!-- Manual Mode Fields -->
            <div x-show="config_mode === 'manual'" x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 class="p-5 border border-gray-100 rounded-xl bg-gray-50 dark:bg-gray-800/50 dark:border-gray-700/50">
                
                <h4 class="mb-4 text-xs font-semibold tracking-wider text-gray-500 uppercase font-lexend">Distinguished Name Attributes</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Organization (O)</label>
                        <input type="text" name="organization" value="{{ $defaults['organizationName'] ?? '' }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Country (C)</label>
                        <input type="text" name="country" value="{{ $defaults['countryName'] ?? 'ID' }}" maxlength="2"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">State / Province (ST)</label>
                        <input type="text" name="state" value="{{ $defaults['stateOrProvinceName'] ?? '' }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">Locality (L)</label>
                        <input type="text" name="locality" value="{{ $defaults['localityName'] ?? '' }}"
                            class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-brand-500 focus:border-brand-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                    </div>
                </div>
            </div>

            <!-- Key Size -->
            <div>
                <label class="block mb-3 text-sm font-medium text-gray-700 dark:text-gray-300">Private Key Size</label>
                <div class="flex flex-wrap gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="key_bits" value="2048" x-model="key_bits" class="peer sr-only">
                        <div class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg peer-checked:bg-brand-50 peer-checked:text-brand-700 peer-checked:border-brand-500 hover:bg-gray-50 transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:peer-checked:bg-brand-900/30 dark:peer-checked:text-brand-300 dark:peer-checked:border-brand-500">
                            2048 Bit (Standard)
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="key_bits" value="4096" x-model="key_bits" class="peer sr-only">
                        <div class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg peer-checked:bg-brand-50 peer-checked:text-brand-700 peer-checked:border-brand-500 hover:bg-gray-50 transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:peer-checked:bg-brand-900/30 dark:peer-checked:text-brand-300 dark:peer-checked:border-brand-500">
                            4096 Bit (High Security)
                        </div>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-100 dark:border-gray-800">
                <button type="button" @click="createOpen = false" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition shadow-sm">
                    Cancel
                </button>
                <button type="submit" :disabled="!isValid()"
                    class="px-6 py-2.5 text-sm font-semibold text-white transition rounded-lg bg-brand-500 hover:bg-brand-600 shadow-theme-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    Generate Certificate
                </button>
            </div>
        </form>
    </div>
</x-ui.modal>
