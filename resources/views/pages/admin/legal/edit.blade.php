@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6" x-data="{ 
        preview: false, 
        content: @js($legalPage->currentRevision->content ?? ''),
        currentVersion: @js($legalPage->currentRevision->version ?? '1.0.0'),
        selectedVersionType: 'patch',
        customVersion: '',
        updateExisting: false,
        
        get suggestions() {
            let parts = this.currentVersion.split('.').map(n => parseInt(n) || 0);
            while(parts.length < 3) parts.push(0);
            
            return {
                major: (parts[0] + 1) + '.0.0',
                minor: parts[0] + '.' + (parts[1] + 1) + '.0',
                patch: parts[0] + '.' + parts[1] + '.' + (parts[2] + 1)
            };
        },
        
        get finalVersion() {
            if (this.updateExisting) return this.currentVersion;
            if (this.selectedVersionType === 'custom') return this.customVersion;
            return this.suggestions[this.selectedVersionType];
        },

        markdownToHtml(text) {
             if (!text) return '';
             return text
                .replace(/^# (.*$)/gim, '<h1 class=\'text-2xl font-bold mb-4\'>$1</h1>')
                .replace(/^## (.*$)/gim, '<h2 class=\'text-xl font-bold mb-3\'>$1</h2>')
                .replace(/^### (.*$)/gim, '<h3 class=\'text-lg font-bold mb-2\'>$1</h3>')
                .replace(/\*\*(.*)\*\*/gim, '<strong>$1</strong>')
                .replace(/\*(.*)\*/gim, '<em>$1</em>')
                .replace(/\n/gim, '<br>');
        }
    }">
        <!-- Breadcrumb -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-semibold text-black dark:text-white">
                Edit: {{ $legalPage->title }}
            </h2>

            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-500"
                            href="{{ route('admin.legal-pages.index') }}">
                            Legal Pages /
                        </a>
                    </li>
                    <li class="font-medium text-brand-500">Edit</li>
                </ol>
            </nav>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <form action="{{ route('admin.legal-pages.update', $legalPage->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Left Sidebar (Meta Information) -->
                    <div class="lg:col-span-1 border-r border-gray-100 dark:border-gray-800 pr-0 lg:pr-6">
                        
                        <!-- Toggle Section -->
                        <div class="mb-8 p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div>
                                    <span class="block text-sm font-bold text-gray-800 dark:text-gray-200">Minor Correction</span>
                                    <span class="block text-xs text-gray-400">Fixed typo or small tweaks?</span>
                                </div>
                                <div class="relative inline-block w-10 h-6">
                                    <input type="checkbox" name="update_existing" value="true" x-model="updateExisting" class="sr-only peer">
                                    <div class="w-full h-full bg-gray-300 dark:bg-gray-700 rounded-full peer peer-checked:bg-brand-500 transition-all after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4"></div>
                                </div>
                            </label>
                            
                            <template x-if="updateExisting">
                                <p class="mt-3 text-[10px] leading-tight text-brand-600 dark:text-brand-400 font-medium italic">
                                    * "Minor Correction" mode active. The system will update the existing record without creating a new revision, and the version will remain v<span x-text="currentVersion"></span>.
                                </p>
                            </template>
                        </div>

                        <div class="mb-6" :class="updateExisting ? 'opacity-40 grayscale pointer-events-none' : ''">
                            <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                                Version Selection
                            </label>
                            
                            <input type="hidden" name="version" :value="finalVersion">

                            <div class="grid grid-cols-1 gap-3">
                                <!-- Major -->
                                <button type="button" @click="selectedVersionType = 'major'"
                                    :class="selectedVersionType === 'major' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-gray-200 dark:border-gray-700'"
                                    class="flex items-center justify-between rounded-xl border-2 p-3 text-left transition-all">
                                    <div>
                                        <p class="text-xs font-bold text-brand-600 dark:text-brand-400">MAJOR UPDATE</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="'v' + suggestions.major"></p>
                                    </div>
                                    <div x-show="selectedVersionType === 'major'" class="text-brand-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </button>

                                <!-- Minor -->
                                <button type="button" @click="selectedVersionType = 'minor'"
                                    :class="selectedVersionType === 'minor' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-gray-200 dark:border-gray-700'"
                                    class="flex items-center justify-between rounded-xl border-2 p-3 text-left transition-all">
                                    <div>
                                        <p class="text-xs font-bold text-gray-400">MINOR UPDATE</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="'v' + suggestions.minor"></p>
                                    </div>
                                    <div x-show="selectedVersionType === 'minor'" class="text-brand-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </button>

                                <!-- Patch -->
                                <button type="button" @click="selectedVersionType = 'patch'"
                                    :class="selectedVersionType === 'patch' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-gray-200 dark:border-gray-700'"
                                    class="flex items-center justify-between rounded-xl border-2 p-3 text-left transition-all">
                                    <div>
                                        <p class="text-xs font-bold text-gray-400">PATCH / FIX</p>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200" x-text="'v' + suggestions.patch"></p>
                                    </div>
                                    <div x-show="selectedVersionType === 'patch'" class="text-brand-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </button>

                                <!-- Custom Toggle -->
                                <button type="button" @click="selectedVersionType = 'custom'"
                                    :class="selectedVersionType === 'custom' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10' : 'border-gray-200 dark:border-gray-700'"
                                    class="flex items-center justify-between rounded-xl border-2 p-3 text-left transition-all">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Custom Version</p>
                                </button>
                                
                                <div x-show="selectedVersionType === 'custom'" x-transition class="mt-2">
                                    <input type="text" x-model="customVersion" placeholder="e.g 2.1.3"
                                        class="w-full rounded-lg border-[1.5px] border-gray-200 bg-transparent px-4 py-2 text-sm text-black outline-none transition focus:border-brand-500 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white" />
                                </div>
                            </div>
                            
                            <p class="mt-4 text-xs text-gray-400 text-center">Current active: <span class="font-bold" x-text="'v' + currentVersion"></span></p>
                        </div>

                        <div class="mb-5">
                            <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                                Change Log (Small note for internal audit)
                            </label>
                            <textarea name="change_log" rows="4"
                                class="w-full rounded-lg border-[1.5px] border-gray-200 bg-transparent px-4 py-2 text-black outline-none transition focus:border-brand-500 active:border-brand-500 disabled:cursor-default disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white @error('change_log') border-error-500 @enderror"
                                placeholder="What changed in this version?">{{ old('change_log') }}</textarea>
                            @error('change_log')
                                <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-10">
                            <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-6 py-3 font-medium text-white hover:bg-opacity-90 transition shadow-lg shadow-brand-500/20">
                                <span x-text="updateExisting ? 'Save Changes' : 'Save New Revision'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Right Main Content (Markdown Editor) -->
                    <div class="lg:col-span-2">
                        <div class="mb-4 flex items-center justify-between">
                            <label class="block text-sm font-medium text-black dark:text-white">
                                Content (Markdown)
                            </label>
                            <div class="flex rounded-lg bg-gray-100 p-1 dark:bg-gray-800">
                                <button type="button" @click="preview = false" 
                                    :class="!preview ? 'bg-white dark:bg-gray-700 text-brand-500 shadow-sm' : 'text-gray-500'"
                                    class="px-4 py-1.5 text-xs font-medium rounded-md transition-all">
                                    Editor
                                </button>
                                <button type="button" @click="preview = true"
                                    :class="preview ? 'bg-white dark:bg-gray-700 text-brand-500 shadow-sm' : 'text-gray-500'"
                                    class="px-4 py-1.5 text-xs font-medium rounded-md transition-all">
                                    Preview
                                </button>
                            </div>
                        </div>

                        <div x-show="!preview">
                            <textarea name="content" x-model="content" rows="20"
                                class="w-full rounded-lg border-[1.5px] border-gray-200 bg-transparent px-4 py-4 text-black font-mono text-sm outline-none transition focus:border-brand-500 active:border-brand-500 disabled:cursor-default disabled:bg-gray-100 dark:border-gray-700 dark:bg-gray-900/50 dark:text-white @error('content') border-error-500 @enderror">{{ old('content', $legalPage->currentRevision->content ?? '') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-show="preview" class="min-h-[465px] rounded-lg border border-gray-200 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-900/30 overflow-y-auto">
                            <div class="prose prose-gray dark:prose-invert max-w-none" x-html="markdownToHtml(content)">
                            </div>
                        </div>
                        
                        <p class="mt-4 text-xs text-gray-500 text-center">
                            TIP: Markdown is converted to high-quality typography on the public site.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
