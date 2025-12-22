<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 uppercase">
                <th class="px-6 py-4 font-medium">Name</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Created</th>
                <th class="px-6 py-4 font-medium">Last used</th>
                <th class="px-6 py-4 font-medium">Disable/Enable</th>
                <th class="px-6 py-4 font-medium">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($apiKeys as $key)
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" x-data="{ editingKey: { ...@js($key), regenerating: false } }">
                    <td class="px-6 py-4">
                        <div class="space-y-2">
                            <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="editingKey.name"></div>
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1 max-w-xs">
                                    <input type="text" readonly :value="maskKey(editingKey.key)"
                                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg py-1.5 pl-3 pr-10 text-xs font-mono text-gray-600 dark:text-gray-400 focus:outline-none focus:ring-0">
                                </div>
                                <button x-data="{ copied: false }" 
                                        @click="navigator.clipboard.writeText(editingKey.key); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg transition-colors">
                                    <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <svg x-show="copied" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button @click="$dispatch('open-modal', { name: 'confirm-regenerate-api-key', apiKey: editingKey })"
                                        class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                            :class="editingKey.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500'"
                            x-text="editingKey.is_active ? 'Active' : 'Disabled'">
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $key->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $key->last_used_at ? $key->last_used_at->format('m/d/Y, h:i:s A') : 'Never' }}
                    </td>
                    <td class="px-6 py-4">
                        <button @click="toggleStatus(editingKey)" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2"
                            :class="editingKey.is_active ? 'bg-brand-500' : 'bg-gray-200 dark:bg-gray-700'" role="switch" :aria-checked="editingKey.is_active">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="editingKey.is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <button @click="$dispatch('open-modal', { name: 'delete-api-key', apiKey: editingKey })"
                                class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            <button @click="$dispatch('open-modal', { name: 'edit-api-key', apiKey: editingKey })"
                                class="text-gray-400 hover:text-brand-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        @if(request('search'))
                            No API keys found matching "{{ request('search') }}".
                        @else
                            No API keys found. Generate one to get started.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($apiKeys->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
        {{ $apiKeys->links() }}
    </div>
@endif
