@extends('layouts.fullscreen-layout', ['title' => 'Telegram Chat ID Finder'])

@section('content')
<div class="relative min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300 overflow-hidden flex flex-col">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2 w-[600px] h-[600px] bg-brand-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <x-public.navbar />

    <main class="flex-grow pt-32 pb-20 px-4 relative z-10">
        <div class="max-w-3xl mx-auto space-y-12">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 text-brand-600 dark:text-brand-400 text-[10px] font-bold uppercase tracking-widest mb-6">
                    🛠️ Developer Utility
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Chat ID Finder
                </h1>
                <p class="text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
                    Quickly retrieve your Telegram Chat ID using your Bot Token. All processing happens locally in your browser.
                </p>
            </div>

            <div x-data="{ 
                botToken: '',
                chats: [],
                loading: false,
                error: '',
                copiedId: null,
                async findChats() {
                    if (!this.botToken) {
                        this.error = 'Please enter a Bot Token';
                        return;
                    }
                    
                    this.loading = true;
                    this.error = '';
                    this.chats = [];
                    
                    try {
                        const response = await fetch(`https://api.telegram.org/bot${this.botToken}/getUpdates`);
                        const data = await response.json();
                        
                        if (!data.ok) {
                            this.error = data.description || 'Invalid token or API error';
                            return;
                        }
                        
                        if (data.result.length === 0) {
                            this.error = 'No recent messages found. Please send a message to your bot first!';
                            return;
                        }
                        
                        // Extract unique chats
                        const uniqueChats = {};
                        data.result.forEach(update => {
                            const message = update.message || update.edited_message || update.callback_query?.message;
                            if (message && message.chat) {
                                uniqueChats[message.chat.id] = {
                                    id: message.chat.id,
                                    name: message.chat.title || message.chat.first_name || 'Group/Channel',
                                    username: message.chat.username ? `@${message.chat.username}` : 'N/A',
                                    type: message.chat.type
                                };
                            }
                        });
                        
                        this.chats = Object.values(uniqueChats);
                        
                        if (this.chats.length === 0) {
                            this.error = 'Could not find any chat information in recent updates.';
                        }
                    } catch (err) {
                        this.error = 'Network error. Please check your connection.';
                        console.error(err);
                    } finally {
                        this.loading = false;
                    }
                },
                copyToClipboard(text, id) {
                    navigator.clipboard.writeText(text);
                    this.copiedId = id;
                    setTimeout(() => this.copiedId = null, 2000);
                }
            }" class="bg-white px-8 py-10 shadow-2xl rounded-[2.5rem] dark:bg-white/[0.03] border border-gray-100 dark:border-gray-800 backdrop-blur-sm">
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4">Telegram Bot Token</label>
                        <div class="relative group">
                            <input 
                                type="text" 
                                x-model="botToken"
                                placeholder="123456789:ABCDE..."
                                class="w-full rounded-2xl border-gray-200 bg-gray-50/50 p-5 text-sm font-mono text-gray-900 transition focus:ring-brand-500 focus:border-brand-500 dark:border-gray-700 dark:bg-gray-800/50 dark:text-white"
                                @keydown.enter="findChats()"
                            />
                            <button 
                                @click="findChats()"
                                class="absolute right-2 top-2 bottom-2 px-6 bg-brand-500 text-white text-xs font-bold rounded-xl hover:bg-brand-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-brand-500/20"
                                :disabled="loading"
                            >
                                <span x-show="!loading">Fetch Updates</span>
                                <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div x-show="error" x-transition x-cloak class="p-4 rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-600 dark:text-red-400 text-sm flex gap-3">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="error"></span>
                    </div>

                    <!-- Detected Chats -->
                    <div x-show="chats.length > 0" x-transition x-cloak class="space-y-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                             Detected Chats 
                            <span class="px-2 py-0.5 bg-brand-50 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 text-[10px] rounded-full" x-text="chats.length"></span>
                        </h3>
                        <div class="overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                                    <tr>
                                        <th class="px-5 py-4 font-bold text-gray-500 dark:text-gray-400 uppercase text-[10px] tracking-wider">Chat Identity</th>
                                        <th class="px-5 py-4 font-bold text-gray-500 dark:text-gray-400 uppercase text-[10px] tracking-wider">Numeric ID</th>
                                        <th class="px-5 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    <template x-for="chat in chats" :key="chat.id">
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors group">
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-gray-900 dark:text-white" x-text="chat.name"></div>
                                                <div class="text-[11px] text-gray-500 dark:text-gray-500 font-mono" x-text="chat.username"></div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <code class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-brand-600 dark:text-brand-400 rounded-lg font-mono text-xs font-bold" x-text="chat.id"></code>
                                            </td>
                                            <td class="px-5 py-4 text-right">
                                                <button 
                                                    @click="copyToClipboard(chat.id, chat.id)"
                                                    class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-all border border-gray-100 dark:border-gray-700 active:scale-90"
                                                >
                                                    <svg x-show="copiedId !== chat.id" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3" />
                                                    </svg>
                                                    <svg x-show="copiedId === chat.id" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-6 bg-brand-50/50 dark:bg-brand-500/5 rounded-3xl border border-brand-100 dark:border-brand-500/10">
                        <h4 class="text-xs font-bold text-brand-600 dark:text-brand-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Instructions
                        </h4>
                        <ol class="text-xs text-gray-600 dark:text-gray-400 space-y-3 list-decimal ml-4 font-medium">
                            <li>Send a message (e.g., "Hello") to your Bot from the account or group you want the ID from.</li>
                            <li>Paste your <strong>Bot Token</strong> above and click <strong>Fetch Updates</strong>.</li>
                            <li>Your <code>CHAT_ID</code> will appear in the table. Copy it for use in your API or integrations.</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <x-public.footer />
</div>
@endsection
