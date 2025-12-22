@extends('layouts.fullscreen-layout', ['title' => 'Laravel APP_KEY Generator'])

@section('content')
<div class="relative min-h-screen bg-white dark:bg-gray-900 transition-colors duration-300 overflow-hidden flex flex-col">
    <!-- Background Decoration -->
    <div class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -translate-x-1/2 translate-y-1/2 w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[150px] pointer-events-none"></div>

    <x-public.navbar />

    <main class="flex-grow pt-32 pb-20 px-4 relative z-10">
        <div class="max-w-2xl mx-auto space-y-12">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest mb-6">
                    🔐 Security Utility
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Key Generator
                </h1>
                <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                    Generate a production-ready 32-byte <code>APP_KEY</code> for your Laravel application securely in your browser.
                </p>
            </div>

            <div x-data="{ 
                generatedKey: '',
                copying: false,
                generate() {
                    const array = new Uint8Array(32);
                    window.crypto.getRandomValues(array);
                    const binary = Array.from(array, byte => String.fromCharCode(byte)).join('');
                    this.generatedKey = 'base64:' + btoa(binary);
                },
                copy() {
                    if (!this.generatedKey) return;
                    navigator.clipboard.writeText(this.generatedKey);
                    this.copying = true;
                    setTimeout(() => this.copying = false, 2000);
                }
            }" x-init="generate()" class="bg-white px-8 py-10 shadow-2xl rounded-[2.5rem] dark:bg-white/[0.03] border border-gray-100 dark:border-gray-800 backdrop-blur-sm">
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 text-center">Your Generated Security Key</label>
                        <div class="relative group">
                            <div class="w-full rounded-2xl border-gray-200 bg-gray-50/50 p-6 text-sm font-mono text-gray-900 transition dark:border-gray-700 dark:bg-gray-800/50 dark:text-white break-all text-center min-h-[60px] flex items-center justify-center"
                                x-text="generatedKey || 'Generating...'">
                            </div>
                            
                            <button @click="copy()" x-show="generatedKey"
                                class="absolute top-1/2 -right-4 -translate-y-1/2 p-4 bg-brand-500 text-white rounded-2xl shadow-xl shadow-brand-500/30 hover:scale-110 transition-all active:scale-95"
                                title="Copy to clipboard">
                                <svg x-show="!copying" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                <svg x-show="copying" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </button>
                            
                            <div x-show="copying" x-transition style="display: none;" class="absolute -top-12 left-1/2 -translate-x-1/2 bg-brand-600 text-white text-[10px] font-bold px-4 py-1.5 rounded-full shadow-lg">
                                COPIED TO CLIPBOARD
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <button @click="generate()"
                            class="flex-1 rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-6 py-4 text-sm font-bold shadow-lg transition-all hover:-translate-y-0.5 active:scale-95">
                            Generate New Key
                        </button>
                        <button @click="copy()"
                            class="flex-1 rounded-2xl bg-brand-500 text-white px-6 py-4 text-sm font-bold shadow-lg shadow-brand-500/20 transition-all hover:-translate-y-0.5 active:scale-95">
                            Copy to .env
                        </button>
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border border-gray-100 dark:border-gray-700">
                        <h4 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-widest mb-3 flex items-center gap-2">
                             Quick Guide
                        </h4>
                        <ul class="text-[11px] text-gray-500 dark:text-gray-400 space-y-2 font-medium">
                            <li class="flex gap-2">
                                <span class="text-brand-500 font-bold">1.</span>
                                <div>Copy the generated key above.</div>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-brand-500 font-bold">2.</span>
                                <div>Open your <code>.env</code> file in your Laravel project root.</div>
                            </li>
                            <li class="flex gap-2">
                                <span class="text-brand-500 font-bold">3.</span>
                                <div>Update the <code>APP_KEY=</code> variable with this key.</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <x-public.footer />
</div>
@endsection
